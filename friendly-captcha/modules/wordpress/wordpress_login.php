<?php

add_action( 'login_form', 'frcaptcha_wp_login_show_widget', 10, 0 );

function frcaptcha_wp_login_show_widget() {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_wp_login_active()) {
        return;
    }

    echo frcaptcha_generate_widget_tag($plugin->get_sitekey());

    // it just slightly overflows..
    echo "<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>";

    frcaptcha_enqueue_widget_scripts();
}

add_filter( 'authenticate', 'frcaptcha_wp_login_validate', 20, 3 );	

function frcaptcha_wp_login_validate($user, $username, $password) {

    if ( empty( $_POST ) ) {
        return;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_wp_login_active()) {
        return;
    }

    $errorPrefix = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : ';
	$solution = isset( $_POST['frc-captcha-solution'] ) ? trim( $_POST['frc-captcha-solution'] ) : '';
	
	if ( empty( $solution ) ) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::$default_error_user_message . " (captcha missing)" );
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        return new WP_Error("frcaptcha-solution-error", $errorPrefix . FriendlyCaptcha_Plugin::$default_error_user_message);
    }

    return $user;
}
