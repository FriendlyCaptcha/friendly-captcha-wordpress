<?php

add_action('woocommerce_lostpassword_form', 'frcaptcha_wc_lost_password_show_widget', 10, 0);

function frcaptcha_wc_lost_password_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // it just slightly overflows..
    echo '<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>';

    frcaptcha_enqueue_widget_scripts();
}
