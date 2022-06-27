<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


add_action( 'elementor/element/heading/section_title/inject_into_form_widget', '', 10, 2);
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


function change_form_widget_content( $widget_content, $widget ) {

	if ( 'form' === $widget->get_name() ) {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured() or !$plugin->get_elementor_active()) {
            return $widget_content .= '<h1>Testing</h1>';
        }
        $widget_content .= frcaptcha_generate_widget_tag_from_plugin($plugin);
	}

	return $widget_content;

}
add_filter( 'elementor/widget/render_content', 'change_form_widget_content', 10, 2 );

// function inject_into_form_widget ( $element, $args ) {
//     $element->add_control( 'friendly_captcha',
//         [
//             'label' => 'Frienldy Captcha' ,
//             'type' => 'frcaptcha',
//             'section' => 'section_title',
//             'tab' => 'content',
//         ]
//     );
// }

// add_action( 'elementor/element/heading/section_title/before_section_end', 'inject_into_form_widget');

// add_action( 'elementor/element/heading/section_title/before_section_end', function( $element, $args ) {
//     $element->add_control( 'title_color',
//         [
//             'label' => 'Color' ,
//             'type' => \Elementor\Controls_Manager::SELECT,
//             'default' => 'red',
//             'options' => [
//                 'red' => 'Red',
//                 'blue' => 'Blue',
//             ],
//             'section' => 'section_title',
//             'tab' => 'content',
//         ]
//     );
// }, 10, 2);

function register_field_type($field_types) {
    $customControls = array(
        'frcaptcha' => esc_html( 'Friendly Captcha')
    );
    $newTypes = array_merge($field_types, $customControls);
    return $newTypes;
}
add_filter( 'elementor_pro/forms/field_types', 'register_field_type' );