<?php

add_filter('hf_form_html', function ($html) {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    frcaptcha_enqueue_widget_scripts();

    $widget = frcaptcha_generate_widget_tag_from_plugin($plugin);
    $html = str_replace('</form>', $widget . '</form>', $html);
    return $html;
});

add_filter('hf_validate_form', function ($error_code, $form, $data) {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $error_code;
    }

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        return 'frcaptcha_empty';
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        return 'frcaptcha_invalid';
    }

    return $error_code;
}, 10, 3);

add_filter('hf_form_message_frcaptcha_empty', function ($message) {
    return FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
});

add_filter('hf_form_message_frcaptcha_invalid', function ($message) {
    return FriendlyCaptcha_Plugin::default_error_user_message();
});
