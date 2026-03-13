<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function forceLogout()
    {
        delete_cookie('access_token');
        $this->session->sess_destroy();
        redirect('auth');
        exit;
    }
}
