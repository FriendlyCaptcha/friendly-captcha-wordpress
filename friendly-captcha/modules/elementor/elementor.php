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

// if ( ! defined( 'ABSPATH' ) ) {
// 	exit; // Exit if accessed directly.
// }

/**
 * Register Currency Control.
 *
 * Include control file and register control class.
 *
 * @since 1.0.0
 * @param \Elementor\Controls_Manager $controls_manager Elementor controls manager.
 * @return void
 */
function register_currency_control( $controls_manager ) {

	require_once( __DIR__ . '/controls/currency.php' );

    $controls_manager->register( new \Elementor_Currency_Control() );

}
add_action( 'elementor/controls/register', 'register_currency_control' );

/**
 * Register Currency Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_currency_widget( $widgets_manager ) {

	require_once( __DIR__ . '/widgets/currency-widget.php' );

	$widgets_manager->register( new \Elementor_Currency_Widget() );

}
add_action( 'elementor/widgets/register', 'register_currency_widget' );