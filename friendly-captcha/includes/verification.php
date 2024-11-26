<?php

use FriendlyCaptcha\SDK\{Client, ClientConfig};

function frcaptcha_verify_captcha_solution($solution, $sitekey, $api_key)
{
    if (FriendlyCaptcha_Plugin::$instance->get_enable_v2()) {
        return frcaptcha_v2_verify_captcha_solution($solution, $sitekey, $api_key);
    } else {
        return frcaptcha_v1_verify_captcha_solution($solution, $sitekey, $api_key);
    }
}

function frcaptcha_v1_verify_captcha_solution($solution, $sitekey, $api_key)
{
    $endpoint = 'https://api.friendlycaptcha.com/api/v1/siteverify';
    if (FriendlyCaptcha_Plugin::$instance->get_eu_puzzle_endpoint_active()) {
        $endpoint = 'https://eu-api.friendlycaptcha.eu/api/v1/siteverify';
    }

    $request_body = array(
        'secret' => $api_key,
        'sitekey' => $sitekey,
        'solution' => $solution,
    );

    $request = array(
        'body' => $request_body,
    );

    $response = wp_remote_post(esc_url_raw($endpoint), $request);
    $status = wp_remote_retrieve_response_code($response);

    // Useful for debugging
    // $body = json_encode($request_body);
    // trigger_error($body);

    $raw_response_body = wp_remote_retrieve_body($response);
    $response_body = json_decode($raw_response_body, true);

    if (200 != $status) {
        if (WP_DEBUG) {
            frcaptcha_log_remote_request($endpoint, $response);
        }

        FriendlyCaptcha_Plugin::$instance->show_verification_failed_alert($raw_response_body);

        // Better safe than sorry, if the request is non-200 we can not verify the response
        // Either the user's credentials are wrong (e.g. wrong sitekey, api key) or the friendly
        // captcha servers are unresponsive.

        return array(
            "success" => true,
            "status" => $status,
            "error_codes" => array()
        );
    }

    $success = isset($response_body['success'])
        ? $response_body['success']
        : false;

    $errorCodes = isset($response_body['errors'])
        ? $response_body['errors']
        : array();

    // Useful for debugging with customers
    if (!$success && WP_DEBUG) {
        frcaptcha_log_unsuccessful_verification($request_body, $response_body);
    }

    return array(
        "success" => $success,
        "status" => $status,
        "error_codes" => $errorCodes
    );
}

function frcaptcha_v2_verify_captcha_solution($solution, $sitekey, $api_key)
{
    $config = new ClientConfig();
    $config->setAPIKey($api_key)->setSitekey($sitekey);
    if (FriendlyCaptcha_Plugin::$instance->get_eu_puzzle_endpoint_active()) {
        $config->setSiteverifyEndpoint("eu");
    }

    $captchaClient = new Client($config);

    $result = $captchaClient->verifyCaptchaResponse($solution);

    if (!$result->wasAbleToVerify()) {
        if (WP_DEBUG) {
            frcaptcha_v2_log_verify_response(
                $config->siteverifyEndpoint,
                $result->status,
                $result->response->error->error_code
            );
        }

        $raw_response = json_encode($result->response);
        FriendlyCaptcha_Plugin::$instance->show_verification_failed_alert($raw_response);

        // Better safe than sorry, when we can not verify the response
        // Either the user's credentials are wrong (e.g. wrong sitekey, api key) or the friendly
        // captcha servers are unresponsive.

        return array(
            "success" => true,
            "status" => $result->status,
            "error_codes" => array()
        );
    }

    // Useful for debugging with customers
    if (!$result->shouldAccept() && WP_DEBUG) {
        frcaptcha_log_unsuccessful_verification($solution, $result->response);
    }

    $errorCodes = $result->getErrorCode() ? [$result->getErrorCode()] : [];
    return array(
        "success" => $result->shouldAccept(),
        "status" => $result->status,
        "error_codes" => $errorCodes
    );
}

function frcaptcha_verify_auth_info($sitekey, $api_key)
{
    $endpoint = 'https://eu-api.friendlycaptcha.eu/api/v1/authInfo';

    $request_body = array(
        'secret' => $api_key,
        'sitekey' => $sitekey,
    );

    $request = array(
        'body' => $request_body,
    );

    $response = wp_remote_post(esc_url_raw($endpoint), $request);
    $status = wp_remote_retrieve_response_code($response);
    if ($status >= 500) {
        if (WP_DEBUG) {
            frcaptcha_log_remote_request($endpoint, $response);
        }

        return array(
            'success' => true
        );
    }

    // Useful for debugging
    // $body = json_encode($request_body);
    // trigger_error($body);

    $raw_response_body = wp_remote_retrieve_body($response);
    $response_body = json_decode($raw_response_body, true);

    $success = isset($response_body['success'])
        ? $response_body['success']
        : false;

    $errorCodes = isset($response_body['errors'])
        ? $response_body['errors']
        : array();

    if ($success) {
        return array(
            'success' => true
        );
    }

    $message = 'Unknown error. Please check your sitekey and API key.';
    if (count($errorCodes) > 0) {
        $errorCode = $errorCodes[0];
        switch ($errorCode) {
            case 'sitekey_missing':
            case 'sitekey_invalid':
                $message = 'Invalid sitekey. Please get a valid sitekey from the Friendly Captcha dashboard.';
                break;
            case 'sitekey_account_mismatch':
                $message = 'Sitekey and API key do not belong to the same Friendly Captcha account.';
                break;
            case 'secret_missing':
            case 'secret_invalid':
                $message = 'Invalid API key. Please get a valid API key from the Friendly Captcha dashboard.';
                break;
        }
    }

    return array(
        'success' => false,
        'message' => $message
    );
}
