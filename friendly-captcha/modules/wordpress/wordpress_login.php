<?php

add_action('login_form', 'frcaptcha_wp_login_show_widget', 10, 0);

function frcaptcha_wp_login_show_widget()
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

add_filter('wp_authenticate_user', 'frcaptcha_wp_login_validate', 20, 2);

function frcaptcha_wp_login_validate($user, $password)
{
    if ($user instanceof WP_Error) {
        return $user;
    }

    // When 2FA is configured using Wordfence, the filter is run twice. We want to skip the second run.
    // See https://github.com/wordpress-premium/wordfence/blob/master/modules/login-security/classes/controller/wordfencels.php#L568
    $is2FA = (defined('WORDFENCE_LS_AUTHENTICATION_CHECK') && WORDFENCE_LS_AUTHENTICATION_CHECK);
    if ($is2FA) {
        return $user;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $user;
    }

    $errorPrefix = '<strong>' . __('Error', 'wp-captcha') . '</strong> : ';
    if (empty($_POST)) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (empty body)", "frcaptcha"));
    }

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha"));
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        return new WP_Error("frcaptcha-solution-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message());
    }

    return $user;
}


/* Remove the captcha hook, when Ultimate Member is used, so it does not get called twice */
add_filter('um_submit_form_errors_hook_login', 'remove_filter_authenticate');

function remove_filter_authenticate($credentials)
{
    remove_filter('wp_authenticate_user', 'frcaptcha_wp_login_validate', 20);

    return $credentials;
}
