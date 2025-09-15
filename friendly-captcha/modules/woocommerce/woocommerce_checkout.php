<?php

add_action('woocommerce_after_checkout_billing_form', 'frcaptcha_wc_checkout_show_widget', 10, 0);

function frcaptcha_wc_checkout_show_widget()
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

add_action('woocommerce_checkout_process', 'frcaptcha_wc_checkout_validate', 20, 3);

function frcaptcha_wc_checkout_validate()
{

    if (empty($_POST)) {
        return;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    $errorPrefix = '<strong>' . __('Error', 'frcaptcha') . '</strong>: ';
    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        $error_message = $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(' (captcha missing)', 'frcaptcha');
        wc_add_notice($error_message, 'error');
        return;
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'woocommerce');

    if (!$verification['success']) {
        $error_message = $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message();
        wc_add_notice($error_message, 'error');
    }
}
