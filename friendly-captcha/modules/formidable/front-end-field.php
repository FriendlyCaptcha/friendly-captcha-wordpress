<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

$plugin = FriendlyCaptcha_Plugin::$instance;
if (!$plugin->is_configured() or !$plugin->get_wp_login_active()) {
	return;
}

echo frcaptcha_generate_widget_tag_from_plugin($plugin);

// it just slightly overflows..
echo "<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>";

frcaptcha_enqueue_widget_scripts();
