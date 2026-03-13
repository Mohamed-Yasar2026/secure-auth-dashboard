<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Security Model - Security Features & Logging
 */
class Security_model extends CI_Model
{

    public function get_security_config()
    {
        $config = [];
        $settings = $this->db->get('system_settings')->result();
        foreach ($settings as $setting) {
            $config[$setting->setting_key] = $setting->setting_value;
        }
        return $config;
    }


    public function is_ip_blocked($ip)
    {
        $count = $this->db->where('ip_address', $ip)->where('is_blocked', 1)
            ->where('(blocked_until IS NULL OR blocked_until >', 'NOW()', false)
            ->count_all_results('ip_blacklist');
        return $count > 0;
    }

    public function block_ip($ip, $reason, $duration_minutes = null)
    {
        $data = [
            'ip_address' => $ip,
            'reason' => $reason,
            'is_blocked' => 1,
            'auto_blocked' => 1,
            'blocked_at' => date('Y-m-d H:i:s'),
            'blocked_until' => $duration_minutes ? date('Y-m-d H:i:s', strtotime("+{$duration_minutes} minutes")) : null
        ];

        $existing = $this->db->where('ip_address', $ip)->get('ip_blacklist')->row();
        if ($existing) {
            $this->db->where('ip_address', $ip)->update('ip_blacklist', $data);
        } else {
            $this->db->insert('ip_blacklist', $data);
        }
    }

    public function count_recent_attempts($email, $minutes)
    {
        return $this->db->where('email', $email)->where('status', 'failed')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")))
            ->count_all_results('login_logs');
    }

    public function log_login_attempt($data)
    {
        return $this->db->insert('login_logs', $data);
    }

    public function log_security_event($data)
    {
        return $this->db->insert('security_events', $data);
    }

    public function log_suspicious_activity($user_id, $ip, $email, $activity_type, $risk_score, $details = null)
    {
        return $this->db->insert('suspicious_activity', [
            'user_id' => $user_id,
            'ip_address' => $ip,
            'email' => $email,
            'activity_type' => $activity_type,
            'risk_score' => $risk_score,
            'details' => $details ? json_encode($details) : null
        ]);
    }

    public function check_rate_limit($identifier, $action, $max_attempts = 10, $window_minutes = 1)
    {
        $window_start = date('Y-m-d H:i:s', strtotime("-{$window_minutes} minutes"));

        $count = $this->db->where('identifier', $identifier)->where('action', $action)
            ->where('window_start >=', $window_start)->count_all_results('rate_limits');

        if ($count >= $max_attempts) {
            return false;
        }

        $this->db->insert('rate_limits', [
            'identifier' => $identifier,
            'action' => $action,
            'window_start' => date('Y-m-d H:i:s')
        ]);

        return true;
    }

    public function create_session($user_id, $session_id, $ip, $fingerprint, $user_agent)
    {
        $device_info = get_device_info();

        return $this->db->insert('user_sessions', [
            'user_id' => $user_id,
            'session_id' => $session_id,
            'ip_address' => $ip,
            'device_fingerprint' => $fingerprint,
            'user_agent' => substr($user_agent, 0, 500),
            'device_name' => get_device_name(),
            'browser' => $device_info['browser'],
            'platform' => $device_info['platform'],
            'is_active' => 1,
            'last_activity' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+7 days'))
        ]);
    }

    public function invalidate_session($session_id)
    {
        return $this->db->where('session_id', $session_id)->update('user_sessions', [
            'is_active' => 0
        ]);
    }

    public function get_active_sessions($user_id)
    {
        return $this->db->where('user_id', $user_id)->where('is_active', 1)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->order_by('last_activity', 'DESC')
            ->get('user_sessions')->result();
    }
}
