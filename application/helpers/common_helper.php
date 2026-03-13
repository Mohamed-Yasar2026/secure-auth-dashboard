<?php

defined('BASEPATH') or exit('No direct script access allowed');

function initHeader()
{
    $CI = &get_instance();
    $CI->load->view('layouts/header');
}

function initFooter()
{
    $CI = &get_instance();
    $CI->load->view('layouts/footer');
}

function deviceFingerprint()
{
    $CI = &get_instance();
    $deviceId = $CI->input->post('device_id', FALSE);
    $browser = $CI->agent->browser() . ' ' . $CI->agent->version();
    $os      = $CI->agent->platform();
    $ip      = $CI->input->ip_address();

    return hash_hmac(
        'sha256',
        $browser . $os . $ip . $deviceId,
        $CI->config->item('encryption_key')
    );
}


function getIpLocation($ip)
{
    // Skip local IPs
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return [
            'country' => 'Localhost',
            'region'  => 'Local',
            'city'    => 'Local',
            'isp'     => 'Localhost'
        ];
    }

    // $url = "http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,isp";
    $url = "https://ipapi.co/{$ip}/json/";
    $response = @file_get_contents($url);

    if (!$response) {
        return null;
    }

    $data = json_decode($response, true);

    if ($data['status'] !== 'success') {
        return null;
    }

    return [
        'country' => $data['country'],
        'region'  => $data['regionName'],
        'city'    => $data['city'],
        'isp'     => $data['isp']
    ];
}

function get_login_staff_id()
{
    return get_instance()->session->userdata('user_id');
}
