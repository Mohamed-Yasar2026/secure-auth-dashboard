<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_https')) {
    function is_https() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }
}

if (!function_exists('get_ip_location')) {
    function get_ip_location($ip) {
        $url = "http://ip-api.com/json/{$ip}?fields=status,country,countryCode,region,regionName,city,lat,lon,timezone,isp";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            $data = json_decode($response, true);
            if ($data && isset($data['status']) && $data['status'] === 'success') {
                return [
                    'country' => $data['country'] ?? 'Unknown',
                    'country_code' => $data['countryCode'] ?? 'XX',
                    'region' => $data['regionName'] ?? 'Unknown',
                    'city' => $data['city'] ?? 'Unknown',
                    'lat' => $data['lat'] ?? null,
                    'lon' => $data['lon'] ?? null,
                    'timezone' => $data['timezone'] ?? 'Unknown',
                    'isp' => $data['isp'] ?? 'Unknown'
                ];
            }
        }
        
        return [
            'country' => 'Unknown', 'country_code' => 'XX', 'region' => 'Unknown',
            'city' => 'Unknown', 'lat' => null, 'lon' => null,
            'timezone' => 'Unknown', 'isp' => 'Unknown'
        ];
    }
}

if (!function_exists('sanitize_input')) {
    function sanitize_input($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = sanitize_input($value);
            }
            return $input;
        }
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }
}
