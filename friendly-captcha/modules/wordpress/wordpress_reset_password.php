<?php

add_action('lostpassword_form', 'frcaptcha_wp_reset_password_show_widget', 10, 0);

function frcaptcha_wp_reset_password_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // it just slightly overflows..
    echo "<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>";

    frcaptcha_enqueue_widget_scripts();
}

add_filter('lostpassword_post', 'frcaptcha_wp_reset_password_validate', 10, 1);

function frcaptcha_wp_reset_password_validate($val)
{

    // If the user is logged in, do nothing and return the original value
    if (is_user_logged_in()) {
        return $val;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $val;
    }

    $errorPrefix = '<strong>' . __('Error', 'wp-captcha') . '</strong> : ';
    if (empty($_POST)) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (empty body)", "frcaptcha"));
    }

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        wp_die($errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha"));
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        wp_die($errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message());
    }

    return $val;
}
