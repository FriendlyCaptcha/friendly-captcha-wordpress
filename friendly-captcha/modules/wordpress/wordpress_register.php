<?php

add_action('register_form', 'frcaptcha_wp_register_show_widget', 10, 0);

function frcaptcha_wp_register_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // it just slightly overflows without this.
    echo "<style>.frc-captcha {max-width:100%;}</style>";

    frcaptcha_enqueue_widget_scripts();
}

add_action('register_post', 'frcaptcha_wp_register_validate', 10, 3);

function frcaptcha_wp_register_validate($user_login, $user_email, $errors)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $errors;
    }

    $errorPrefix = '<strong>' . __('Error', 'wp-captcha') . '</strong> : ';
    if (empty($_POST)) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (empty body)", "frcaptcha"));
    }

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        return $errors->add('frcaptcha_error_message', $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha"));
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        return $errors->add('frcaptcha_error_message', $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message());
    }

    return $errors;
}
