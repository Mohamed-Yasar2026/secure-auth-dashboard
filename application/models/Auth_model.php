<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function insert_user($data)
    {
        return $this->db->insert('authentication', $data);
    }


    public function get_user_by_email($email)
    {
        return $this->db->where('email', $email)->get('authentication')->row();
    }

    public function failedAttempts($email, $minutes = 5)
    {
        return $this->db
            ->where('email', $email)
            ->where('status', 'failed')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")))
            ->count_all_results('login_logs');
    }

    public function lockAccount($email, $minutes = 5)
    {
        return $this->db
            ->where('email', $email)
            ->where('locked_until IS NULL', null, false)
            ->update('authentication', [
                'status' => 'blocked',
                'locked_until' => date('Y-m-d H:i:s', strtotime("+{$minutes} minutes"))
            ]);
    }

    public function unlockIfExpired($user)
    {
        if ($user->status === 'blocked' && $user->locked_until <= date('Y-m-d H:i:s')) {
            $this->db->where('id', $user->id)->update('authentication', [
                'status' => 'active',
                'locked_until' => null
            ]);
            $user->status = 'active';
        }
        return $user;
    }

    public function increaseFailed($id)
    {
        $this->db->set('failed_attempts', 'failed_attempts+1', false)
            ->where('id', $id)
            ->update('authentication');
    }

    public function resetFailures($id)
    {
        $this->db->where('id', $id)->update('authentication', [
            'failed_attempts' => 0
        ]);
    }
}
