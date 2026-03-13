<?php
class Dashboard_model extends CI_Model
{

    public function totalAttempts($email)
    {
        return $this->db
            ->where('email', $email)
            ->count_all_results('login_logs');
    }

    public function successfulLogins($email)
    {
        return $this->db
            ->where('email', $email)
            ->where('status', 'success')
            ->count_all_results('login_logs');
    }

    public function failedLogins($email)
    {
        return $this->db
            ->where('email', $email)
            ->where('status', 'failed')
            ->count_all_results('login_logs');
    }

    public function userDetails($email)
    {
        return $this->db
            ->where('email', $email)
            ->get('authentication')
            ->row();
    }
    public function getUserById($id)
    {
        return $this->db
            ->where('id', $id)
            ->get('authentication')
            ->row();
    }
    public function getFingerprint($id)
    {
        return $this->db->where('id', $id)->get('authentication')->row()->device_fingerprint;
    }

    public function get_recent_activity($limit = 10)
    {
        $test = $this->db->select('ll.*, u.name as user_name')->where('u.id', get_login_staff_id())
            ->from('login_logs ll')
            ->join('authentication u', 'u.id = ll.user_id', 'left')
            ->order_by('ll.created_at', 'DESC')
            ->limit($limit)
            ->get()->result();
        return $test;
    }

    public function loginChart($id)
    {
        return $this->db->select("
            COUNT(*) as total,
            SUM(status = 'success') as success,
            SUM(status = 'failed') as failed
        ")
            ->where('user_id', $id)
            ->get('login_logs')
            ->row();
    }


    public function topLoginLocations()
    {
        return $this->db->select('country, COUNT(*) as total, ip_address')
            ->from('login_logs')
            ->where('country IS NOT NULL')
            // ->group_by('country')
            ->order_by('total', 'DESC')
            // ->limit(3)
            ->get()
            ->result();
    }
}
