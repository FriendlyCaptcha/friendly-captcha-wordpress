<?php

add_filter('wppb_recover_password_generate_password_input', 'frcaptcha_pb_reset_password_show_widget', 10, 1);

function frcaptcha_pb_reset_password_show_widget($output)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $output;
    }

    frcaptcha_enqueue_widget_scripts();

    $widget = frcaptcha_generate_widget_tag_from_plugin($plugin);
    return str_replace('</ul>', '<li class="wppb-form-field wppb-recaptcha">' . $widget . '</li>' . '</ul>', $output);
}

add_filter('wppb_recover_password_sent_message1', 'frcaptcha_pb_reset_password_sent_message', 10, 1);

function frcaptcha_pb_reset_password_sent_message($message)
{
    //    We are using the plugins ReCaptcha functionality to display the error
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $message;
    }
    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        return 'wppb_recaptcha_error';
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'profile-builder');

    if (!$verification['success']) {
        return 'wppb_recaptcha_error';
    }

    return $message;
}


add_filter('wppb_recover_password_displayed_message1', 'frcaptcha_pb_reset_password_display_message', 10, 1);

function frcaptcha_pb_reset_password_display_message($message)
{
    //    This will only be triggered when the captcha error occurred, so we can safely send this error.
    //    We can't check again because then we will get an error from FriendlyCaptcha
    return $message = $message . '<p class="wppb-warning">' . FriendlyCaptcha_Plugin::default_error_user_message() . '</p>';
}
