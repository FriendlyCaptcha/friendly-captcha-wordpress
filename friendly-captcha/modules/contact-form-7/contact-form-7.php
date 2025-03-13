<?php

add_action('wp_enqueue_scripts', 'frcaptcha_wpcf7_friendly_captcha_enqueue_scripts', 10, 0);

function frcaptcha_wpcf7_friendly_captcha_enqueue_scripts()
{
	$plugin = FriendlyCaptcha_Plugin::$instance;
	if (!$plugin->is_configured()) {
		return;
	}

	// See if wpcf7 is even enabled
	if (!class_exists('WPCF7_Service')) {
		return;
	}

	frcaptcha_enqueue_widget_scripts();
	wp_enqueue_script(
		'frcaptcha_wpcf7-friendly-captcha',
		plugin_dir_url(__FILE__) . 'script.js',
		array('friendly-captcha-widget-module', 'friendly-captcha-widget-fallback'),
		FriendlyCaptcha_Plugin::$version,
		true
	);
}

add_filter(
	'wpcf7_form_elements',
	'frcaptcha_wpcf7_friendly_captcha_add_widget_if_missing',
	100,
	1
);

function frcaptcha_wpcf7_friendly_captcha_add_widget_if_missing($elements)
{
	$plugin = FriendlyCaptcha_Plugin::$instance;
	if (!$plugin->is_configured()) {
		return $elements;
	}

	// Check if a widget is already present (probably through a shortcode)
	if (preg_match('/<div.*class=".*frc-captcha.*".*<\/div>/', $elements)) {
		return $elements;
	}

	$elements .= frcaptcha_generate_widget_tag_from_plugin($plugin);

	return $elements;
}

add_filter('wpcf7_spam', 'frcaptcha_wpcf7_friendly_captcha_verify_response', 9, 1);

function frcaptcha_wpcf7_friendly_captcha_verify_response($spam)
{
	if ($spam) {
		return $spam;
	}

	$plugin = FriendlyCaptcha_Plugin::$instance;
	if (!$plugin->is_configured()) {
		return $spam;
	}

	if ($plugin->get_integration_active("f12_cf7_doubleoptin")) {
		// Forge12 Double Opt-In triggers a form submit when clicking the link in the email.
		// That form submit will be a GET request and does not have the frc-captcha-solution field, so we need to let it pass.
		if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['optin'])) {
			return $spam;
		}
	}

	$solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();
	$submission = WPCF7_Submission::get_instance();

	if (empty($solution)) {
		$submission->add_spam_log(array(
			'agent' => 'friendly-captcha',
			'reason' => __('FriendlyCaptcha solution value frc-captcha-solution was missing', 'frcaptcha'),
		));
		return true;
	}

	$verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'contact-form-7');

	if ($verification["success"]) {
		$spam = false;
	} else {
		$spam = true;
		if ('.UNSTARTED' === $solution) {
			$submission->add_spam_log(array(
				'agent' => 'friendly-captcha',
				'reason' => __('FriendlyCaptcha widget was not started yet', 'frcaptcha'),
			));
		} elseif ('.FETCHING' === $solution) {
			$submission->add_spam_log(array(
				'agent' => 'friendly-captcha',
				'reason' => __('FriendlyCaptcha widget was still fetching a puzzle', 'frcaptcha'),
			));
		} elseif ('.UNFINISHED' === $solution) {
			$submission->add_spam_log(array(
				'agent' => 'friendly-captcha',
				'reason' => __('FriendlyCaptcha widget was not done solving yet', 'frcaptcha'),
			));
		} elseif ('.ERROR' === $solution) {
			$submission->add_spam_log(array(
				'agent' => 'friendly-captcha',
				'reason' => __('FriendlyCaptcha widget had an (internal) error', 'frcaptcha'),
			));
		} else {
			$submission->add_spam_log(array(
				'agent' => 'friendly-captcha',
				'reason' => sprintf(
					__('Problem with FriendlyCaptcha solution: %s', 'frcaptcha'),
					reset($verification["error_codes"])
				),
			));
		}
	}

	return $spam;
}

function frcaptcha_wpcf7_friendly_captcha_widget_shortcode($form_tag)
{
	$plugin = FriendlyCaptcha_Plugin::$instance;
	if (!$plugin->is_configured()) {
		return;
	}

	return frcaptcha_generate_widget_tag_from_plugin($plugin);
}

add_action('wpcf7_init', 'frcaptcha_wpcf7_friendly_captcha_add_form_tag_friendly_captcha', 10, 0);

function frcaptcha_wpcf7_friendly_captcha_add_form_tag_friendly_captcha()
{
	$plugin = FriendlyCaptcha_Plugin::$instance;
	if (!$plugin->is_configured()) {
		return;
	}

	wpcf7_add_form_tag("friendlycaptcha", "frcaptcha_wpcf7_friendly_captcha_widget_shortcode", array("theme"));
}
