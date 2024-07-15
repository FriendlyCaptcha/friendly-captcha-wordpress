<?php

class frcaptcha_divi_core_addon extends ET_Core_API_Spam_Provider
{
    public $name = 'FriendlyCaptcha';

    /**
     * @inheritDoc
     */
    public $slug = 'frcaptcha';

    public $custom_fields = null; // avoid notice from \ET_Core_API_Email_Providers::_initialize which expects this field

    public function __construct($owner = 'frcaptcha', $account_name = '', $api_key = '')
    {
        parent::__construct($owner, $account_name, $api_key);

        $this->_add_actions_and_filters();
    }

    protected function _add_actions_and_filters()
    {
        if (!is_admin() && !et_core_is_fb_enabled()) {
            add_action('wp_enqueue_scripts', array($this, 'action_wp_enqueue_scripts'));
        }
    }

    public function action_wp_enqueue_scripts()
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;

        if (!$plugin->is_configured()) {
            return;
        }

        if (!$this->is_enabled()) {
            return;
        }

        frcaptcha_enqueue_widget_scripts(true);

        wp_dequeue_script('et-core-api-spam-recaptcha');
    }

    public function is_enabled()
    {
        $has_frcaptcha_module = true;

        if (class_exists('ET_Dynamic_Assets')) {
            $et_dynamic_module_framework  = et_builder_dynamic_module_framework();
            $is_dynamic_framework_enabled = et_builder_is_frontend() && 'on' === $et_dynamic_module_framework;
            $is_dynamic_css_enabled       = et_builder_is_frontend() && et_use_dynamic_css();

            if ($is_dynamic_framework_enabled && $is_dynamic_css_enabled) {
                $et_dynamic_assets    = ET_Dynamic_Assets::init();
                $saved_shortcodes     = $et_dynamic_assets->get_saved_page_shortcodes();
                $frcaptcha_modules    = array('et_pb_contact_form', 'et_pb_signup');
                $has_frcaptcha_module = !empty(array_intersect($saved_shortcodes, $frcaptcha_modules));
            }
        }

        return $has_frcaptcha_module;
    }

    public function verify_form_submission()
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;

        if (!$plugin->is_configured()) {
            return array(
                'success' => true,
                'score' => 100000,
            );
        }

        if (!$this->is_enabled()) {
            return array(
                'success' => true,
                'score' => 100000,
            );
        }

        $solution = et_()->array_get_sanitized($_POST, 'token');
        if (empty($solution)) {
            return 'Captcha missing';
        }

        $plugin = FriendlyCaptcha_Plugin::$instance;
        $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

        if ($verification["success"]) {
            return array(
                'success' => true,
                'score' => 100000,
            );
        } else {
            return 'Captcha error';
        }
    }

    public function get_account_fields()
    {
        return array();
    }
}
