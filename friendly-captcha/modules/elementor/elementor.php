<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


function change_form_widget_content( $widget_content, $widget ) {

	if ( 'form' === $widget->get_name() ) {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured() or !$plugin->get_elementor_active()) {
            return;
        }
        $widget_content .= frcaptcha_generate_widget_tag_from_plugin($plugin);
	}

	return $widget_content;

}
add_filter( 'elementor/widget/render_content', 'change_form_widget_content', 10, 2 );


function register_friendlycaptcha_control( $controls_manager ) {

	require_once( __DIR__ . '/controls/friendly-captcha-control.php' );

   \Elementor\Plugin::instance()->controls_manager->register( new \Elementor_Friendly_Captcha_Control() );

}
add_action( 'elementor/controls/register', 'register_friendlycaptcha_control' );

function register_friendlycaptcha_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/friendly-captcha-widget.php' );

	$widgets_manager->register( new \Elementor_Friendly_Captcha_Widget() );

}
add_action( 'elementor/widgets/register', 'register_friendlycaptcha_widget' );