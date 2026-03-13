<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/Firebase/JWT/JWT.php';
require_once APPPATH . '../vendor/autoload.php';

use Firebase\JWT\JWT;
use RobThree\Auth\TwoFactorAuth;

/**
 * Auth Controller
 *
 * Login Flow A — TOTP already enrolled:
 *   POST /auth/login  →  temp_user session  →  GET /auth/totp_challenge
 *   POST /auth/verify_login_totp  →  _finalize_login()  →  /dashboard
 *
 * Login Flow B — TOTP not yet enrolled:
 *   POST /auth/login  →  full session (no JWT yet)  →  GET /auth/enable_totp
 *   POST /auth/verify_totp  →  totp_enabled = 1  →  _finalize_login()  →  /dashboard
 */
class Auth extends CI_Controller
{
    // ── Tuneable constants ────────────────────────────────────────────────────
    private const MAX_LOGIN_ATTEMPTS = 5;       // password failures before lock
    private const MAX_TOTP_ATTEMPTS  = 5;       // TOTP failures before session kill
    private const TOKEN_TTL          = 2592000; // JWT / cookie lifetime (30 days)
    private const JWT_ISS            = 'SecureAuth';
    private const TFA_ISSUER         = 'SecureAuthDashboard';
    private const TOTP_FAIL_KEY      = '_totp_fails'; // session key for TOTP fail counter

    /** @var object|null */
    private $security_config;

    // =========================================================================
    // CONSTRUCTOR — auto-redirect authenticated users away from auth pages
    // =========================================================================

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['Auth_model', 'Dashboard_model', 'Security_model']);
        $this->load->driver('cache', ['adapter' => 'file']);
        $this->load->library('form_validation');
        $this->config->load('jwt');
        $this->load->helper('jwt');

        $this->security_config = $this->Security_model->get_security_config();

        // Already fully logged in with a valid JWT + matching device fingerprint?
        // Redirect away from all auth pages immediately.
        $uid       = get_login_staff_id();
        $sessionFp = $this->session->userdata('device_hash');
        $jwt       = verify_jwt();

        if (!$uid || !$sessionFp || !$jwt) {
            return; // not authenticated — let each action method decide
        }

        $dbFp = $this->Dashboard_model->getFingerprint($uid);

        if (
            $this->session->userdata('logged_in') &&
            $jwt->uid == $uid                     &&
            $dbFp                                  &&
            hash_equals($dbFp, $sessionFp)         &&
            hash_equals($dbFp, $jwt->fp)
        ) {
            redirect('dashboard');
            exit;
        }
    }

    // =========================================================================
    // PAGE VIEWS
    // =========================================================================

    /**
     * GET /auth  →  login page
     */
    public function index()
    {
        $this->_set_security_headers();
        $this->load->view('auth/login');
    }

    public function totp_challenge()
    {
        if (!$this->session->userdata('temp_user')) {
            redirect('auth');
            return;
        }

        $user_id = $this->session->userdata('temp_user');
        $user    = $this->_fetch_user($user_id);

        // If user has not enrolled 2FA yet, send them to setup
        if ((int) $user->totp_enabled === 0) {
            redirect('auth/enable_totp');
            return;
        }

        // Guard: already fully authenticated
        if ($this->session->userdata('logged_in')) {
            $user_id = (int) $this->session->userdata('user_id');
            $user    = $this->_fetch_user($user_id);
            $this->_finalize_login($user);
            return;
        }

        $this->_set_security_headers();
        $this->load->view('auth/totp_challenge');
    }

    /**
     * GET /auth/enable_totp
     * TOTP setup — shows QR code. Requires a full session (logged_in = true).
     */
    public function enable_totp()
    {
        $this->_require_login();

        $user_id = (int) $this->session->userdata('user_id');
        $user    = $this->_fetch_user($user_id);

        // Guard against re-enrollment on an already-active 2FA account
        if ((int) $user->totp_enabled === 1) {
            redirect('auth/totp_challenge');
            return;
        }

        $tfa = $this->_tfa();

        // Generate secret only once; preserve any pending secret on reload
        if (empty($user->totp_secret)) {
            $secret = $tfa->createSecret();
            $this->db->where('id', $user_id)->update('authentication', [
                'totp_secret'  => $secret,
                'totp_enabled' => 0,
            ]);
        } else {
            $secret = $user->totp_secret;
        }

        $data['qrCode'] = $tfa->getQRCodeImageAsDataUri($user->email, $secret);
        $data['title']  = 'Enable 2FA — SecureAuth';

        $this->_set_security_headers();
        $this->load->view('auth/enable_totp', $data);
    }

    /**
     * POST /auth/skip_totp
     *
     * Lets a user skip 2FA setup and go straight to the dashboard.
     * Only reachable from the enable_totp page (requires logged_in session).
     * Uses a separate form so CodeIgniter's CSRF token is validated automatically.
     */
    public function skip_totp()
    {
        // Reject anything that isn't a real form POST
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        // enable_totp requires _require_login() which sets user_id + logged_in.
        // temp_user is NOT set in this flow — check the correct session key.
        $user_id = $this->session->userdata('user_id');

        if (empty($user_id) || !$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Invalid session. Please log in again.');
            redirect('auth');
            return;
        }

        $user = $this->_fetch_user((int) $user_id);

        if (!$user) {
            $this->session->sess_destroy();
            redirect('auth');
            return;
        }

        // Safety: if 2FA is already active, don't let them "skip" — send to challenge
        if ((int) $user->totp_enabled === 1) {
            redirect('auth/totp_challenge');
            return;
        }

        // Audit trail
        log_message('info', 'User ID ' . $user_id . ' skipped 2FA setup at ' . date('Y-m-d H:i:s'));

        // Complete login without 2FA — _finalize_login handles JWT + redirect
        $this->_finalize_login($user);
    }

    // =========================================================================
    // AUTH ACTIONS
    // =========================================================================

    /**
     * POST /auth/login  (AJAX only)
     */
    public function login()
    {
        $this->_require_ajax();

        $this->form_validation->set_rules('email',    'Email',    'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[128]');

        if (!$this->form_validation->run()) {
            return $this->_json(false, strip_tags(validation_errors()));
        }

        $email    = $this->input->post('email');
        $password = $this->input->post('password', FALSE); // NOT xss_clean — raw for password_verify
        $ip       = $this->_real_ip();
        $device   = deviceFingerprint();

        $user = $this->Auth_model->get_user_by_email($email);

        // Unknown email
        if (!$user) {
            $this->_log(null, $email, $ip, $device, 'failed');
            return $this->_json(false, 'Invalid email or password');
        }

        // Auto-unlock expired lockout
        $user = $this->Auth_model->unlockIfExpired($user);

        if ($user->status !== 'active') {
            return $this->_json(false, 'Account temporarily locked. Please try again later.');
        }

        // Wrong password
        if (!password_verify($password, $user->password)) {
            $this->Auth_model->increaseFailed($user->id);
            $this->_log($user->id, $email, $ip, $device, 'failed');

            $newCount = (int) $user->failed_attempts + 1;

            if ($newCount >= self::MAX_LOGIN_ATTEMPTS) {
                $this->Auth_model->lockAccount($email);
                return $this->_json(false, 'Too many failed attempts. Account locked temporarily.');
            }

            $remaining = self::MAX_LOGIN_ATTEMPTS - $newCount;
            return $this->_json(false, "Invalid email or password. {$remaining} attempt(s) remaining.");
        }

        // Correct password
        $this->Auth_model->resetFailures($user->id);

        // Flow A: TOTP already enrolled
        if ((int) $user->totp_enabled === 1) {
            $this->session->sess_regenerate(true);
            $this->session->set_userdata([
                'temp_user'         => $user->id,
                self::TOTP_FAIL_KEY => 0,
            ]);

            return $this->_json(true, 'TOTP required', [
                'redirect' => base_url('auth/totp_challenge'),
            ]);
        }

        // Flow B: First login — full session so /enable_totp is accessible.
        // JWT is NOT issued here; _finalize_login() issues it after TOTP is verified (or skipped).
        $this->session->sess_regenerate(true);
        $this->session->set_userdata([
            'user_id'     => $user->id,
            'device_hash' => $device,
            'logged_in'   => true,
        ]);

        return $this->_json(true, 'Redirecting to 2FA setup', [
            'redirect' => base_url('auth/enable_totp'),
        ]);
    }

    /**
     * POST /auth/verify_totp  (standard form POST)
     *
     * Verifies the first-time TOTP setup code, activates 2FA on the account,
     * then completes login by calling _finalize_login().
     */
    public function verify_totp()
    {
        $this->_require_post();
        $this->_require_login();

        $user_id = (int) $this->session->userdata('user_id');
        $code    = trim((string) $this->input->post('code'));
        if (empty($code)) {
            $this->session->set_flashdata('error', 'Please enter the 6-digit code from your app.');
            redirect('auth/enable_totp');
            return;
        }

        if (!$this->_is_valid_totp_code($code)) {
            $this->session->set_flashdata('error', 'Invalid code format. Enter the 6-digit number from your app.');
            redirect('auth/enable_totp');
            return;
        }

        $user = $this->_fetch_user($user_id);

        if (empty($user->totp_secret)) {
            $this->session->set_flashdata('error', 'TOTP secret missing. Please restart the setup process.');
            redirect('auth/enable_totp');
            return;
        }

        // Guard against double-submit — if already activated, skip ahead
        if ((int) $user->totp_enabled === 1) {
            $this->_finalize_login($user);
            return;
        }

        if (!$this->_tfa()->verifyCode($user->totp_secret, $code, 2)) {
            $this->session->set_flashdata('error', 'Invalid authentication code. Please try again.');
            redirect('auth/enable_totp');
            return;
        }

        // Activate 2FA on the account
        $this->db->where('id', $user_id)->update('authentication', ['totp_enabled' => 1]);

        // Refresh user object so _finalize_login sees totp_enabled = 1
        $user = $this->_fetch_user($user_id);
        $this->_finalize_login($user);
    }

    /**
     * POST /auth/verify_login_totp  (standard form POST)
     *
     * Verifies the TOTP code during login (Flow A). Completes login on success.
     */
    public function verify_login_totp()
    {
        $this->_require_post();

        // Guard: no temp session means they haven't authenticated with password yet
        if (!$this->session->userdata('temp_user')) {
            redirect('auth');
            return;
        }

        $user_id = (int) $this->session->userdata('temp_user');
        $user    = $this->_fetch_user($user_id);

        // Guard: if 2FA is NOT enrolled, redirect to setup instead
        // FIX: was (=== 1) which was inverted — caused infinite redirect loop
        if (!$user || (int) $user->totp_enabled === 0) {
            redirect('auth/enable_totp');
            return;
        }

        // Guard: already fully authenticated — just finalize
        // FIX: was missing return, causing execution to fall through
        if ($this->session->userdata('logged_in')) {
            $logged_user_id = (int) $this->session->userdata('user_id');
            $logged_user    = $this->_fetch_user($logged_user_id);
            $this->_finalize_login($logged_user);
            return;
        }

        $failCount = (int) $this->session->userdata(self::TOTP_FAIL_KEY);

        // TOTP brute-force protection
        if ($failCount >= self::MAX_TOTP_ATTEMPTS) {
            $this->session->sess_destroy();
            $this->session->set_flashdata('error', 'Too many failed attempts. Please log in again.');
            redirect('auth');
            return;
        }

        $code = trim((string) $this->input->post('code'));

        if (!$this->_is_valid_totp_code($code)) {
            $this->session->set_flashdata('error', 'Invalid code format. Enter the 6-digit number from your app.');
            redirect('auth/totp_challenge');
            return;
        }

        if (!$this->_tfa()->verifyCode($user->totp_secret, $code, 1)) {
            $newFailCount = $failCount + 1;
            $this->session->set_userdata(self::TOTP_FAIL_KEY, $newFailCount);

            $remaining = self::MAX_TOTP_ATTEMPTS - $newFailCount;
            $message   = $remaining > 0
                ? "Invalid authentication code. {$remaining} attempt(s) remaining."
                : 'Too many failed attempts. Please log in again.';

            $this->session->set_flashdata('error', $message);
            redirect('auth/totp_challenge');
            return;
        }

        // SUCCESS — clean up temp session keys
        $this->session->unset_userdata('temp_user');
        $this->session->unset_userdata(self::TOTP_FAIL_KEY);

        $this->_finalize_login($user);
    }

    /**
     * POST /auth/register  (AJAX only)
     */
    public function register()
    {
        $this->_require_ajax();

        $this->form_validation->set_rules('name',             'Name',             'required|trim|min_length[3]|max_length[64]');
        $this->form_validation->set_rules('email',            'Email',            'required|trim|valid_email|max_length[200]|is_unique[authentication.email]', ['is_unique' => 'That email is already registered.']);
        $this->form_validation->set_rules('password',         'Password',         'required|min_length[8]|max_length[64]|regex_match[/^(?=.*[A-Za-z])(?=.*\d).+$/]', ['regex_match' => 'Password must contain at least one letter and one number.']);
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]', ['matches' => 'Passwords do not match.']);

        if (!$this->form_validation->run()) {
            return $this->_json(false, strip_tags(validation_errors()));
        }

        $data = [
            'name'       => $this->input->post('name', true),
            'email'      => $this->input->post('email', true),
            'password'   => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (!$this->Auth_model->insert_user($data)) {
            return $this->_json(false, 'Registration failed. Please try again.');
        }

        return $this->_json(true, 'Account created successfully.');
    }

    /**
     * POST /auth/check_email  (AJAX only)
     * Real-time email availability check used by the registration form.
     */
    public function check_email()
    {
        $this->_require_ajax();

        $email = trim($this->input->post('email', true));

        if ($email === '') {
            // FIX: was using raw json_encode, inconsistent with rest of controller
            return $this->_json(true, '', ['available' => true]);
        }

        $exists = (bool) $this->Auth_model->get_user_by_email($email);

        return $this->_json(true, '', ['available' => !$exists]);
    }

    /**
     * GET /auth/logout
     */
    public function logout()
    {
        $this->input->set_cookie([
            'name'     => 'access_token',
            'value'    => '',
            'expire'   => time() - 3600,
            'path'     => '/',
            'secure'   => is_https(),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        $this->session->sess_destroy();
        redirect('auth');
    }

    /**
     * Finalizes login for any auth flow (password-only, TOTP verify, TOTP skip).
     *
     * Responsibilities:
     *  1. Clean up ALL temporary session keys from previous flow steps
     *  2. Regenerate session ID to prevent session fixation
     *  3. Set the full authenticated session
     *  4. Issue a signed JWT in an HttpOnly cookie
     *  5. Persist login metadata to DB
     *  6. Write an audit log entry
     *  7. Redirect to dashboard
     */
    private function _finalize_login(object $user): void
    {
        // ── 1. Wipe every temp key regardless of which flow got here ──────────
        // Safe to call even if the keys don't exist — CI silently ignores them.
        $this->session->unset_userdata([
            'temp_user',            // set during Flow A (TOTP challenge)
            self::TOTP_FAIL_KEY,    // brute-force counter for TOTP challenge
            'totp_skipped',         // set during skip_totp()
        ]);

        $ip     = $this->_real_ip();

        // ── 2. Reuse device hash from session if already set (Flow B sets it  ──
        // in login()); otherwise generate fresh. This prevents a mismatch
        // between the value stored in the session and the one persisted to DB.
        $device = $this->session->userdata('device_hash') ?: deviceFingerprint();

        // ── 3. Regenerate + set authenticated session ─────────────────────────
        $this->session->sess_regenerate(true);
        $this->session->set_userdata([
            'user_id'     => (int) $user->id,
            'device_hash' => $device,
            'logged_in'   => true,
        ]);

        // ── 4. Signed JWT cookie ───────────────────────────────────────────────
        $now = time();

        $payload = [
            'iss' => self::JWT_ISS,
            'iat' => $now,
            'exp' => $now + self::TOKEN_TTL,
            'uid' => (int) $user->id,
            'fp'  => $device,
        ];

        $token = JWT::encode($payload, $this->config->item('jwt_key'), 'HS256');

        $this->input->set_cookie([
            'name'     => 'access_token',
            'value'    => $token,
            'expire'   => self::TOKEN_TTL,  // FIX: CI expects seconds from now,
            // NOT a Unix timestamp.
            // time() + TTL was setting expire
            // ~55 years into the future.
            'path'     => '/',
            'secure'   => is_https(),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        // ── 5. Persist login metadata ─────────────────────────────────────────
        $this->db->where('id', $user->id)->update('authentication', [
            'last_login_ip'      => $ip,
            'last_login_at'      => date('Y-m-d H:i:s'),
            'device_fingerprint' => $device,
        ]);

        // ── 6. Audit log ──────────────────────────────────────────────────────
        $this->_log((int) $user->id, $user->email, $ip, $device, 'success');

        // ── 7. Redirect ───────────────────────────────────────────────────────
        // CI's redirect() calls exit() internally so nothing after this runs.
        redirect('dashboard');
    }
    // =========================================================================
    // PRIVATE — GUARDS & UTILITIES
    // =========================================================================

    /**
     * Abort with 403 if the request is not XMLHttpRequest.
     */
    private function _require_ajax(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('No direct access allowed.', 403);
        }
    }

    /**
     * Abort with 405 if the HTTP method is not POST.
     */
    private function _require_post(): void
    {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_error('Method not allowed.', 405);
        }
    }

    /**
     * Redirect to login if the session does not have logged_in = true.
     */
    private function _require_login(): void
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
            exit;
        }
    }

    /**
     * Resolve the real client IP (maps loopback 127/::1 to machine IP).
     */
    private function _real_ip(): string
    {
        $ip = $this->input->ip_address();
        return ($ip === '::1') ? gethostbyname(gethostname()) : $ip;
    }

    /**
     * Return true if $code is exactly 6 decimal digits.
     */
    private function _is_valid_totp_code(string $code): bool
    {
        return (bool) preg_match('/^[0-9]{6}$/', $code);
    }

    /**
     * Fetch an authentication row by primary key.
     * Halts execution via show_error() if the row does not exist.
     */
    private function _fetch_user(int $user_id): object
    {
        $user = $this->db->get_where('authentication', ['id' => $user_id])->row();

        if (!$user) {
            show_error('User record not found.', 500);
        }

        return $user;
    }

    /**
     * Return a TwoFactorAuth instance.
     */
    private function _tfa(): TwoFactorAuth
    {
        return new TwoFactorAuth(self::TFA_ISSUER);
    }

    /**
     * Set hardened HTTP security response headers.
     */
    private function _set_security_headers(): void
    {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }

    /**
     * Emit a JSON response body and rotate the CSRF token for the next request.
     *
     * NOTE: Do NOT add a bare `exit` after return — CI's output class buffers
     * the response and sends it correctly. A bare `exit` after `return` is
     * unreachable anyway (FIX: removed the dead `exit` from the original).
     *
     * @param bool   $success
     * @param string $message
     * @param array  $extra   Additional keys merged into the response root
     */
    private function _json(bool $success, string $message = '', array $extra = [])
    {
        $response = array_merge([
            'success' => $success,
            'message' => $message,
            'csrf'    => [
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash(),
            ],
        ], $extra);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
        // FIX: removed dead `exit` that appeared after a `return` statement
    }

    /**
     * Insert a login event into login_logs with GeoIP metadata.
     *
     * @param int|null $userId
     * @param string   $email
     * @param string   $ip
     * @param string   $device
     * @param string   $status  'success' | 'failed'
     */
    private function _log(?int $userId, string $email, string $ip, string $device, string $status): void
    {
        $location = getIpLocation($ip);
        $device_name = get_device_name();
        $this->db->insert('login_logs', [
            'user_id'    => $userId,
            'email'      => $email,
            'ip_address' => $ip,
            'device'     => $device,
            'device_name' => $device_name,
            'status'     => $status,
            'country'    => $location['country'] ?? 'Unknown',
            'region'     => $location['region']  ?? 'Unknown',
            'city'       => $location['city']    ?? 'Unknown',
            'isp'        => $location['isp']     ?? 'Unknown',
        ]);
    }
}
