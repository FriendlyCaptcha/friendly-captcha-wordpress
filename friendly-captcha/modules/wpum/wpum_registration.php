<?php

add_action( 'wpum_before_submit_button_registration_form', 'frcaptcha_wpum_registration_show_widget', 500 );

function frcaptcha_wpum_registration_show_widget() {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_wpum_registration_active()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    frcaptcha_enqueue_widget_scripts();
}
