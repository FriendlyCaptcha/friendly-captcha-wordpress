<?php

add_filter( 'awsm_application_form_is_recaptcha_visible', 'wpjo_fc_disable_recaptcha' );
function wpjo_fc_disable_recaptcha() {
    if ( get_option( 'awsm_jobs_enable_friendlycaptcha' ) === 'enable' ) {
        return false;
    }
    return true;
}

/* ---------------------------------------------------------
 * 4. FRONTEND — LOAD FRIENDLY CAPTCHA SCRIPT
 * --------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', 'wpjo_fc_enqueue_script' );
function wpjo_fc_enqueue_script() {

    if ( get_option( 'awsm_jobs_enable_friendlycaptcha' ) !== 'enable' ) {
        return;
    }

    wp_enqueue_script(
        'friendly-captcha',
        'https://cdn.jsdelivr.net/npm/friendly-challenge/widget.module.min.js',
        array(),
        null,
        true
    );
}

add_action( 'awsm_application_form_field_init', 'wpjo_fc_display_widget', 20 );

function wpjo_fc_display_widget() {

	$plugin = FriendlyCaptcha_Plugin::$instance;
	if ( ! $plugin->is_configured() ) {
		return;
	}

	// Load scripts
	frcaptcha_enqueue_widget_scripts();

	// Output Friendly Captcha widget HTML
	echo frcaptcha_generate_widget_tag_from_plugin( $plugin );

	// Optional: style fix
	echo '<style>.frc-captcha{max-width:100%;margin-bottom:1em;}</style>';
}