<?php
/**
* Plugin Name: Friendly Captcha for Wordpress
* Plugin URI: https://friendlycaptcha.com/wp
* Description: Official Friendly Captcha Wordpress integration plugin.
* Version: 0.1.0
* Requires at least: 5.0
* Requires PHP: 7.2
* Author: Friendly Captcha GmbH
* Author URI: https://friendlycaptcha.com
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: frcaptcha
* Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'FRIENDLY_CAPTCHA_VERSION', '0.1.0' );
define( 'FRIENDLY_CAPTCHA_FRIENDLY_CHALLENGE_VERSION', '0.6.1' );

register_activation_hook( __FILE__, 'frcaptcha_activate' );

function frcaptcha_activate() { 

}

register_deactivation_hook( __FILE__, 'frcaptcha_deactivate' );

function frcaptcha_deactivate() { 

}

require plugin_dir_path( __FILE__ ) . 'includes/core.php';
