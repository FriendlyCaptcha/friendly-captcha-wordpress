<?php

/**
 * Plugin Name: Friendly Captcha for WordPress
 * Description: Protect WordPress website forms from spam and abuse with Friendly Captcha, a privacy-first anti-bot solution.
 * Version: 1.18.0
 * Requires at least: 5.0
 * Requires PHP: 7.3
 * Author: Friendly Captcha GmbH
 * Author URI: https://friendlycaptcha.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: frcaptcha
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('FRIENDLY_CAPTCHA_VERSION', '1.18.0');
define('FRIENDLY_CAPTCHA_FRIENDLY_CHALLENGE_VERSION', '0.9.19');
define('FRIENDLY_CAPTCHA_FRIENDLY_CAPTCHA_SDK_VERSION', '0.1.25');
define('FRIENDLY_CAPTCHA_SUPPORTED_LANGUAGES', [
	"en" => "English",
	"de" => "German",
	"nl" => "Dutch",
	"fr" => "French",
	"it" => "Italian",
	"pt" => "Portuguese",
	"es" => "Spanish",
	"ca" => "Catalan",
	"ja" => "Japanese",
	"da" => "Danish",
	"ru" => "Russian",
	"sv" => "Swedish",
	"tr" => "Turkish",
	"el" => "Greek",
	"uk" => "Ukrainian",
	"bg" => "Bulgarian",
	"cs" => "Czech",
	"sk" => "Slovak",
	"no" => "Norwegian",
	"fi" => "Finnish",
	"lv" => "Latvian",
	"lt" => "Lithuanian",
	"pl" => "Polish",
	"et" => "Estonian",
	"hr" => "Croatian",
	"sr" =>	"Serbian",
	"hu" => "Hungarian",
	"ro" => "Romanian",
	"zh" => "Chinese (simplified)",
	"vi" => "Vietnamese",
	"he" => "Hebrew",
	"th" => "Thai",
	"kr" => "Korean",
	"ar" => "Arabic"
]);

register_activation_hook(__FILE__, 'frcaptcha_activate');

function frcaptcha_activate()
{
	// A site without a sitekey isn't verifying anything yet, so treat it as a new
	// install and default it to Friendly Captcha v2. Configured sites keep whatever
	// they had: v2 uses different credentials and the application has to be enabled
	// for v2 in the dashboard first, so flipping them would break their forms.
	// add_option() rather than update_option() so an explicit opt-out survives a
	// deactivate/reactivate.
	if (FriendlyCaptcha_Plugin::$instance->get_sitekey() === '') {
		add_option(FriendlyCaptcha_Plugin::$option_enable_v2_name, 1);
	}
}

register_deactivation_hook(__FILE__, 'frcaptcha_deactivate');

function frcaptcha_deactivate() {}

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';
require plugin_dir_path(__FILE__) . 'includes/core.php';
