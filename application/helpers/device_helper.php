<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('get_device_fingerprint')) {
    function get_device_fingerprint()
    {
        $fingerprint_data = [
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'accept_language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
            'accept_encoding' => $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '',
            'accept' => $_SERVER['HTTP_ACCEPT'] ?? ''
        ];
        return hash('sha256', json_encode($fingerprint_data));
    }
}

if (!function_exists('get_device_info')) {
    function get_device_info()
    {
        $CI = &get_instance();
        $CI->load->library('user_agent');

        $browser = $CI->agent->browser();
        $version = $CI->agent->version();
        $platform = $CI->agent->platform();

        $device_type = 'desktop';
        if ($CI->agent->is_mobile()) {
            $device_type = 'mobile';
        } elseif ($CI->agent->is_robot()) {
            $device_type = 'bot';
        }

        return [
            'browser' => $browser ?: 'Unknown',
            'browser_version' => $version ?: 'Unknown',
            'platform' => $platform ?: 'Unknown',
            'device_type' => $device_type
        ];
    }
}

if (!function_exists('get_device_name')) {
    function get_device_name()
    {
        $device_info = get_device_info();
        return $device_info['browser'] . ' on ' . $device_info['platform'];
    }
}

if (!function_exists('get_mfa_status')) {
    function get_mfa_status()
    {
        $CI = &get_instance();
        $status = $CI->db->select('totp_enabled')->where('id', get_login_staff_id())->get('authentication')->row();
        return (bool) !empty($status->totp_enabled) ? $status->totp_enabled : false;
    }
}
