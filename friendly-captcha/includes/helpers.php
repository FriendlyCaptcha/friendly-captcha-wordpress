<?php

function frcaptcha_log_remote_request($url, $response)
{
	$log = sprintf(
		/* translators: 1: response code, 2: message, 3: body, 4: URL */
		__('HTTP Response: %1$s %2$s %3$s from %4$s', 'frcaptcha'),
		(int) wp_remote_retrieve_response_code($response),
		wp_remote_retrieve_response_message($response),
		wp_remote_retrieve_body($response),
		$url
	);

	trigger_error($log);
}

function frcaptcha_v2_log_verify_response($url, $status, $errorCode)
{
	$log = sprintf(
		/* translators: 1: response status, 2: error code, 3: error detail, 4: URL */
		__('Friendly Captcha: %1$s %2$s from %3$s', 'frcaptcha'),
		$status,
		$errorCode,
		$url
	);

	trigger_error($log);
}

function frcaptcha_get_sanitized_frcaptcha_solution_from_post()
{
	$fieldName = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();

	$postValue = $_POST[$fieldName];
	$solution = isset($postValue) ? trim(sanitize_text_field($postValue)) : '';
	return $solution;
}
