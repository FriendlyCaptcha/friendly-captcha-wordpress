<?php

function frcaptcha_log_remote_request($url, $request, $response)
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

function frcaptcha_get_sanitized_frcaptcha_solution_from_post()
{
	if (FriendlyCaptcha_Plugin::$instance->get_enable_v2()) {
		$postValue = $_POST['frc-captcha-response'];
	} else {
		// TODO: update all other places where this is referenced
		$postValue = $_POST['frc-captcha-solution'];
	}

	$solution = isset($postValue) ? trim(sanitize_text_field($postValue)) : '';
	return $solution;
}
