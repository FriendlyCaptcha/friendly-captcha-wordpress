<?php

// Load the addon
add_action('gform_loaded', array('frcaptcha_gform_load_addon', 'load'), 5);
class frcaptcha_gform_load_addon
{
    public static function load()
    {
        if (!method_exists('GFForms', 'include_addon_framework')) {
            return;
        }
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return;
        }

        require_once('addon.php');
        GFAddOn::register('GFFormsFriendlyCaptchaAddOn');
    }
}
