<?php
/*
Plugin Name: Formidable Forms Field Friendly Captcha
Description: Friendly Captcha field type in Formidable Forms.
Version: 1.0
Plugin URI: https://formidableforms.com/
Author URI: https://netsuccess.sk/
Author: SlimCo
*/


// TODO: replace 'frmfrcaptcha_' and 'Frcaptcha' with your own.
// TODO: replace 'new-type' with the slug for your field.

/**
 * Add the autoloader.
 */
function frmfrcaptcha_load_formidable_field() {
	spl_autoload_register( 'frmfrcaptcha_forms_autoloader' );
}
add_action( 'plugins_loaded', 'frmfrcaptcha_load_formidable_field' );

/**
 * @since 3.0
 */
function frmfrcaptcha_forms_autoloader( $class_name ) {
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
function frmfrcaptcha_get_field_type_class( $class, $field_type ) {
	if ( $field_type === 'frcaptcha' ) { // Replace 'frcaptcha' with your field slug.
		$class = 'FrcaptchaFieldNewType'; // Set your class name here.
	}
	return $class;
}
add_filter( 'frm_get_field_type_class', 'frmfrcaptcha_get_field_type_class', 10, 2 );

function frmfrcaptcha_add_new_field( $fields ) {
	$fields['frcaptcha'] = array(
		'name' => 'Friendly Captcha',
		'icon' => 'frm_icon_font frm_pencil_icon', // Set the class for a custom icon here.
	);
	return $fields;
}
add_filter( 'frm_available_fields', 'frmfrcaptcha_add_new_field' );
