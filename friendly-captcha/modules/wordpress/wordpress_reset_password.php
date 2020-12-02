<?php

add_action( 'lostpassword_form', 'frcaptcha_wp_reset_password_show_widget', 10, 0 );

function frcaptcha_wp_reset_password_show_widget() {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_wp_reset_password_active()) {
        return;
    }

    echo frcaptcha_generate_widget_tag($plugin->get_sitekey());

    // it just slightly overflows..
    echo "<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>";

    frcaptcha_enqueue_widget_scripts();
}

add_filter( 'lostpassword_post', 'frcaptcha_wp_reset_password_validate', 10, 1 );	

function frcaptcha_wp_reset_password_validate($val) {

    if ( empty( $_POST ) ) {
        return;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_wp_reset_password_active()) {
        return;
    }

    $errorPrefix = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : ';
	$solution = isset( $_POST['frc-captcha-solution'] ) ? trim( $_POST['frc-captcha-solution'] ) : '';
	
	if ( empty( $solution ) ) {
        wp_die($errorPrefix . FriendlyCaptcha_Plugin::$default_error_user_message . " (captcha missing)");
        // return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::$default_error_user_message . " (captcha missing)" );
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        wp_die($errorPrefix . FriendlyCaptcha_Plugin::$default_error_user_message);
        // return new WP_Error("frcaptcha-solution-error", $errorPrefix . FriendlyCaptcha_Plugin::$default_error_user_message);
    }
    
    return $val;
}