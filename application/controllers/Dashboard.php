<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('jwt');
        $this->load->model('Dashboard_model');
        $this->config->load('jwt');

        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
            exit;
        }

        $uid          = get_login_staff_id();
        $sessionFp    = $this->session->userdata('device_hash');
        $jwt          = verify_jwt();

        // basic JWT check
        if (!$jwt || $jwt->uid != $uid) {
            $this->forceLogout();
        }
        // get fingerprint from DB
        $dbFp = $this->Dashboard_model->getFingerprint($uid);

        // verify device fingerprint
        if (!$dbFp || !hash_equals($dbFp, $sessionFp) || !hash_equals($dbFp, $jwt->fp)) {
            $this->forceLogout();
        }
    }


    public function view()
    {
        $user = get_login_staff_id();

        $userDetails = $this->Dashboard_model->getUserById($user);

        if (!$userDetails) {
            show_error('Unauthorized access', 401);
        }

        $data = [
            'total_attempts'     => $total = $this->Dashboard_model->totalAttempts($userDetails->email),
            'successful_login'   => $success = $this->Dashboard_model->successfulLogins($userDetails->email),
            'failed_login'       => $failed = $this->Dashboard_model->failedLogins($userDetails->email),
            'status'             => ucfirst($userDetails->status),
            'ip'                 => $userDetails->last_login_ip,
            'locations'          => $this->Dashboard_model->topLoginLocations(),
            'success_login_rate' => ($total > 0) ? round(($success / $total) * 100) : 0,
            'failed_login_rate'  => ($total > 0) ? round(($failed / $total) * 100) : 0,
            'mfa_status'         => get_mfa_status(),
        ];

        $data['title'] = 'Dashboard';

        $this->load->view('dashboard/index', $data);
    }

    public function get_dashboard_data()
    {
        $id = get_login_staff_id();
        $user = $id ? $this->Dashboard_model->getUserById($id) : null;

        if (!$user) {
            echo json_encode(['success' => FALSE, 'message' => 'unAuthorized']);
            return;
        }

        $chartData = $this->Dashboard_model->loginChart($id);
        echo json_encode([
            'chart' => [
                'total'   => (int) $chartData->total,
                'success' => (int) $chartData->success,
                'failed'  => (int) $chartData->failed,
            ],
            'activity'    => $this->Dashboard_model->get_recent_activity(6),
        ]);
    }

    public function logout()
    {
        $this->forceLogout();
    }
}
