<?php

add_filter('et_core_get_third_party_components', array('frcaptcha_divi_load_addon', 'register'));
add_filter('et_core_api_spam_enabled_providers', array('frcaptcha_divi_load_addon', 'enable'));
add_filter('option_et_core_api_spam_options', array('frcaptcha_divi_load_addon', 'injectAccount'));
add_action('init', array('frcaptcha_divi_load_addon', 'fakeToken'));
add_filter('do_shortcode_tag', array('frcaptcha_divi_load_addon', 'addWidget'), 10, 2);

class frcaptcha_divi_load_addon
{
    const SUPPORTED_SHORTCODES = ['et_pb_contact_form'];

    const INSERT_BEFORE = '<button type="submit"';

    public static function register($instances = [], $group = '')
    {
        if ($group !== 'api/spam' && $group !== '') {
            return $instances;
        }

        require_once 'frcaptcha_divi_core_addon.php';

        $instances['frcaptcha'] = new frcaptcha_divi_core_addon();

        return $instances;
    }

    public static function enable($_names_by_slug)
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;

        if (!$plugin->is_configured()) {
            return;
        }

        $_names_by_slug['third-party']['frcaptcha'] = 'FriendlyCaptcha verification';
        return $_names_by_slug;
    }

    public static function injectAccount($value, $option = 'et_core_api_spam_options')
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;

        if (!$plugin->is_configured()) {
            return;
        }

        $value = maybe_unserialize($value);
        $value['accounts']['frcaptcha']['default'] = [
            'site_key' => 'default',
            'secret_key' => 'default'
        ];

        return $value;
    }

    public static function addWidget($output, $tag = null)
    {
        if (!in_array($tag, self::SUPPORTED_SHORTCODES)) {
            return $output;
        }

        $plugin = FriendlyCaptcha_Plugin::$instance;

        if (!$plugin->is_configured()) {
            return;
        }

        $output = str_replace(
            self::INSERT_BEFORE,
            frcaptcha_generate_widget_tag_from_plugin(FriendlyCaptcha_Plugin::$instance) . self::INSERT_BEFORE,
            $output
        );

        return $output;
    }

    /**
     * Fake Token in POST
     *
     * @see \ET_Builder_Module_Type_WithSpamProtection::is_spam_submission
     */
    public static function fakeToken()
    {
        $fieldName = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();
        if (isset($_POST[$fieldName])) {
            $_POST['token'] = $_POST[$fieldName];
        }
    }
}
