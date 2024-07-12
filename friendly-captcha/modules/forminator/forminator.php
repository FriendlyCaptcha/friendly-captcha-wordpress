<?php

add_action('forminator_render_button_markup', 'frcaptcha_forminator_add_captcha', 10, 2);
add_filter('forminator_cform_form_is_submittable', 'frcaptcha_forminator_verify', 10, 3);

function frcaptcha_forminator_add_captcha($html, $button)
{
	$plugin = FriendlyCaptcha_Plugin::$instance;
	if (!$plugin->is_configured()) {
		return;
	}

	frcaptcha_enqueue_widget_scripts();

	$elements = frcaptcha_generate_widget_tag_from_plugin($plugin);

	return str_replace('<button ', $elements . '<button ', $html);
}

function frcaptcha_forminator_verify($can_show, $id, $form_settings)
{
	$plugin = FriendlyCaptcha_Plugin::$instance;
	if (!$plugin->is_configured()) {
		return $can_show;
	}

	$solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();
	if (empty($solution)) {
		return [
			'can_submit' => false,
			'error'      => FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha"),
		];
	}

	$verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());
	if (!$verification["success"]) {
		return [
			'can_submit' => false,
			'error'      => FriendlyCaptcha_Plugin::default_error_user_message(),
		];
	}

	return $can_show;
}
