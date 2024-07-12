<?php
if (!defined('ABSPATH')) {
	die('You are not allowed to call this page directly.');
}

$plugin = FriendlyCaptcha_Plugin::$instance;
if (!$plugin->is_configured()) {
	return;
}

echo frcaptcha_generate_widget_tag_from_plugin($plugin);

frcaptcha_enqueue_widget_scripts();
