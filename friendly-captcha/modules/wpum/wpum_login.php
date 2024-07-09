<?php

add_action('wpum_before_submit_button_login_form', 'frcaptcha_wpum_login_show_widget', 500);
add_action('wpum_before_submit_button_two_factor_login_form', 'frcaptcha_wpum_login_show_widget', 500);

function frcaptcha_wpum_login_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    frcaptcha_enqueue_widget_scripts();
}

require_once __DIR__ . '/wpum_validate.php';
