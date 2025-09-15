<?php

add_action('woocommerce_login_form', 'frcaptcha_wc_login_show_widget', 10, 0);

function frcaptcha_wc_login_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // it just slightly overflows..
    echo '<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>';

    frcaptcha_enqueue_widget_scripts();
    wp_enqueue_script(
        'frcaptcha_wc-friendly-captcha',
        plugin_dir_url(__FILE__) . 'script.js',
        array('friendly-captcha-widget-module', 'friendly-captcha-widget-fallback'),
        FriendlyCaptcha_Plugin::$version,
        true
    );
}

add_action('woocommerce_process_login_errors', 'frcaptcha_wc_login_validate', 20, 3);

function frcaptcha_wc_login_validate($validation_error)
{
    if (empty($_POST)) {
        return $validation_error;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $validation_error;
    }

    if ($plugin->get_integration_active('wp_login')) {
        // If the WP login form is active, the captcha is already validated by the 'authenticate' filter.
        return $validation_error;
    }

    $errorPrefix = '<strong>' . __('Error', 'wp-captcha') . '</strong>: ';
    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        $error_message = $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(' (captcha missing)', 'frcaptcha');
        $validation_error->add('frcaptcha-empty-error', $error_message);
        return $validation_error;
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'woocommerce');

    if (!$verification['success']) {
        $error_message = $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message();
        $validation_error->add('frcaptcha-solution-error', $error_message);
    }

    return $validation_error;
}
