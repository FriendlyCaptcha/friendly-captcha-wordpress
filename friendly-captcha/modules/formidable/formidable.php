<?php
/*
Original Author URI: https://netsuccess.sk/
Original Author: SlimCo
*/

function frcaptcha_load_formidable_field() {
	spl_autoload_register( 'frcaptcha_forms_autoloader' );
}
add_action( 'plugins_loaded', 'frcaptcha_load_formidable_field' );

/**
 * @since 3.0
 */
function frcaptcha_forms_autoloader( $class_name ) {
	// Only load Frcaptcha classes here
	if ( ! preg_match( '/^Frcaptcha.+$/', $class_name ) ) {
		return;
	}

	$filepath = dirname( __FILE__ );
	$filepath .= '/' . $class_name . '.php';

	if ( file_exists( $filepath ) ) {
		require( $filepath );
	}
}

/**
 * Tell Formidable where to find the new field type.
 * @return string The name of the new class that extends FrmFieldType.
 */
function frcaptcha_get_field_type_class( $class, $field_type ) {
	if ( $field_type === 'frcaptcha' ) {
		$class = 'FrcaptchaFieldNewType';
	}
	return $class;
}
add_filter( 'frm_get_field_type_class', 'frcaptcha_get_field_type_class', 10, 2 );

function frcaptcha_add_new_field( $fields ) {
	$fields['frcaptcha'] = array(
		'name' => 'Friendly Captcha',
		'icon' => 'frm_icon_font frm_shield_check_icon', // Set the class for a custom icon here.
	);
	return $fields;
}
add_filter( 'frm_available_fields', 'frcaptcha_add_new_field' );
