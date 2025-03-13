<?php

add_filter('login_form_middle', 'frcaptcha_pb_login_show_widget');

function frcaptcha_pb_login_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    frcaptcha_enqueue_widget_scripts();

    $widget = frcaptcha_generate_widget_tag_from_plugin($plugin);
    return $widget;
}

add_filter('authenticate', 'frcaptcha_pb_login_validate', 9);

function frcaptcha_pb_login_validate($user)
{
    // make sure we are on a login form (copied from original plugins recaptcha code)
    if (isset($_POST['wp-submit']) && !is_wp_error($user) && !isset($_POST['pms_login']) && isset($_POST['wppb_login']) && $_POST['wppb_login']) {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return $user;
        }

        $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();
        if (empty($solution)) {
            remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
            remove_filter('authenticate', 'wp_authenticate_email_password', 20, 3);
            return new WP_Error('wpbb_recaptcha_error', FriendlyCaptcha_Plugin::default_error_user_message() . __(' (captcha missing)', 'frcaptcha'));
        }

        $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'profile-builder');
        if (!$verification['success']) {
            remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
            remove_filter('authenticate', 'wp_authenticate_email_password', 20, 3);
            return new WP_Error('wppb_recaptcha_error', FriendlyCaptcha_Plugin::default_error_user_message());
        }
    }

    return $user;
}
