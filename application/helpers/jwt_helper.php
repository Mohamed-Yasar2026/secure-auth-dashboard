<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/Firebase/JWT/JWT.php';
require_once APPPATH . 'libraries/Firebase/JWT/ExpiredException.php';
require_once APPPATH . 'libraries/Firebase/JWT/BeforeValidException.php';
require_once APPPATH . 'libraries/Firebase/JWT/SignatureInvalidException.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

function verify_jwt()
{
    $CI = &get_instance();
    $CI->config->load('jwt');

    $token = $CI->input->cookie('access_token', true);

    if (!$token) {
        return false;
    }

    try {
        return JWT::decode(
            $token,
            $CI->config->item('jwt_key'),
            ['HS256']
        );
    } catch (ExpiredException $e) {
        // log_message('error', $e->getMessage());
        return false;
    } catch (SignatureInvalidException $e) {
        // log_message('error', 'JWT signature invalid');
        return false;
    } catch (BeforeValidException $e) {
        // log_message('error', 'JWT not active yet');
        return false;
    } catch (UnexpectedValueException $e) {
        // log_message('error', 'JWT malformed');
        return false;
    } catch (Exception $e) {
        // log_message('error', 'JWT error: ' . $e->getMessage());
        return false;
    }
}
