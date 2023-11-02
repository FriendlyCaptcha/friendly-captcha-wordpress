<?php

/**
 * Plugin Name: Friendly Captcha for WordPress
 * Description: Protect WordPress website forms from spam and abuse with Friendly Captcha, a privacy-first anti-bot solution.
 * Version: 1.12.2
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

define('FRIENDLY_CAPTCHA_VERSION', '1.12.2');
define('FRIENDLY_CAPTCHA_FRIENDLY_CHALLENGE_VERSION', '0.9.12');
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
]);

register_activation_hook(__FILE__, 'frcaptcha_activate');

function frcaptcha_activate()
{
}

register_deactivation_hook(__FILE__, 'frcaptcha_deactivate');

function frcaptcha_deactivate()
{
}

require plugin_dir_path(__FILE__) . 'includes/core.php';
