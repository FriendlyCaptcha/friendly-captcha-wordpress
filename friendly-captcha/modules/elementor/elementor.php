<?php

function register_friendlycaptcha_control( $controls_manager ) {

	require_once( __DIR__ . '/controls/friendly-captcha-control.php' );

    $controls_manager->register( new \Elementor_Friendly_Captcha_Control() );

}
add_action( 'elementor/controls/register', 'register_friendlycaptcha_control' );