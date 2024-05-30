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

    $response_body = array(
        'secret' => $api_key,
        'sitekey' => $sitekey,
        'solution' => $solution,
    );

    $request = array(
        'body' => $response_body,
    );

    $response = wp_remote_post(esc_url_raw($endpoint), $request);
    $status = wp_remote_retrieve_response_code($response);

    // Useful for debugging
    // $body = json_encode($response_body);
    // trigger_error($body);

    $response_body = wp_remote_retrieve_body($response);
    $response_body = json_decode($response_body, true);

    if (200 != $status) {
        if (WP_DEBUG) {
            frcaptcha_log_remote_request($endpoint, $response);
        }

        FriendlyCaptcha_Plugin::$instance->show_verification_failed_alert();

        // Better safe than sorry, if the request is non-200 we can not verify the response
        // Either the user's credentials are wrong (e.g. wrong sitekey, api key) or the friendly
        // captcha servers are unresponsive.

        return array(
            "success" => true,
            "status" => $status,
            "errors" => array()
        );
    }

    $success = isset($response_body['success'])
        ? $response_body['success']
        : false;

    $errorCodes = isset($response_body['errors'])
        ? $response_body['errors']
        : array();


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

        FriendlyCaptcha_Plugin::$instance->show_verification_failed_alert();

        // Better safe than sorry, when we can not verify the response
        // Either the user's credentials are wrong (e.g. wrong sitekey, api key) or the friendly
        // captcha servers are unresponsive.

        return array(
            "success" => true,
            "status" => $result->status,
            "errors" => array()
        );
    }

    $errorCodes = $result->getErrorCode() ? [$result->getErrorCode()] : [];
    return array(
        "success" => $result->shouldAccept(),
        "status" => $result->status,
        "error_codes" => $errorCodes
    );
}
