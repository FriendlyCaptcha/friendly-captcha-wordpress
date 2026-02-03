<?php

/* Main entry point */
class FriendlyCaptcha_Plugin
{

    /**
     * Singleton global instance
     * @var FriendlyCaptcha_Plugin
     */
    public static $instance;
    public $plugin_name;

    public static $version;
    // JavaScript client SDK for v1
    public static $friendly_challenge_version;
    // JavaScript client SDK for v2
    public static $friendly_captcha_sdk_version;

    // Global constants
    public static $option_group = "frcaptcha_options";
    public static $option_sitekey_name = "frcaptcha_sitekey";
    public static $option_api_key_name = "frcaptcha_api_key";
    public static $option_skip_style_injection_name = "frcaptcha_skip_style_injection";
    public static $option_enable_mutation_observer_name = "frcaptcha_enable_mutation_observer";
    public static $option_enable_v2_name = "frcaptcha_enable_v2";

    public static $option_widget_language_name = "frcaptcha_widget_language";
    public static $option_widget_dark_theme_active_name = "frcaptcha_widget_dark_theme_active";

    public static $option_global_puzzle_endpoint_active_name = "frcaptcha_global_endpoint_active";
    public static $option_eu_puzzle_endpoint_active_name = "frcaptcha_eu_endpoint_active";

    public static $option_verification_failed_alert_name = "frcaptcha_verification_failed_alert_v2";

    public static $integrations = array(
        array(
            "name" => "Contact Form 7",
            "slug" => 'contact_form_7',
            "entry" => "contact-form-7/contact-form-7.php",
            "plugins" => array("contact-form-7/wp-contact-form-7.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/contact-form-7/\" target=\"_blank\">Contact Form 7</a> forms.",
        ),
        array(
            "name" => "CF7 Double-Opt-In",
            "slug" => 'f12_cf7_doubleoptin',
            "entry" => "contact-form-7/contact-form-7.php",
            "plugins" => array("contact-form-7/wp-contact-form-7.php"),
            "settings_description" => "Enable support for the Forge12 Double Opt-In plugin for Contact Form 7. You need to enable Contact Form 7 as well.",
        ),
        array(
            "name" => "WPForms",
            "slug" => 'wpforms',
            "entry" => "wpforms/wpforms.php",
            "plugins" => array("wpforms/wpforms.php", "wpforms-lite/wpforms.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/wpforms/\" target=\"_blank\">WPForms</a> and <a href=\"https://wordpress.org/plugins/wpforms-lite/\"  target=\"_blank\">WPForms lite</a> forms.",
        ),
        array(
            "name" => "Gravity Forms",
            "slug" => 'gravity_forms',
            "entry" => "gravityforms/gravityforms.php",
            "plugins" => array("gravityforms/gravityforms.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://gravityforms.com\" target=\"_blank\">Gravity Forms</a> forms.<br> The widget is available under <i>Advanced Fields</i> in the form builder. For the best protection add the widget to the last page in multi-page forms.",
        ),
        array(
            "name" => "CoBlocks Forms",
            "slug" => 'coblocks',
            "entry" => "coblocks/coblocks.php",
            "plugins" => array("coblocks/class-coblocks.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/coblocks/\" target=\"_blank\">CoBlocks</a> forms.",
        ),
        array(
            "name" => "Fluent Forms",
            "slug" => 'fluentform',
            "entry" => "fluentform/fluentform.php",
            "plugins" => array("fluentform/fluentform.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/fluentform/\" target=\"_blank\">Fluentform</a> forms.",
        ),
        array(
            "name" => "Elementor Pro Forms",
            "slug" => 'elementor',
            "entry" => "elementor/elementor.php",
            "plugins" => array("elementor/elementor.php", "elementor-pro/elementor-pro.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/elementor/\" target=\"_blank\">Elementor Pro</a> forms.<br> The widget is available as a field type in Elementor Pro form editor. Add it as a field to the forms that you want to protect.",
        ),
        array(
            "name" => "HTML Forms",
            "slug" => 'html_forms',
            "entry" => "html-forms/html-forms.php",
            "plugins" => array("html-forms/html-forms.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/html-forms/\" target=\"_blank\">HTML Forms</a>.",
        ),
        array(
            "name" => "Forminator",
            "slug" => 'forminator',
            "entry" => "forminator/forminator.php",
            "plugins" => array("forminator/forminator.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/forminator/\" target=\"_blank\">Forminator</a>.",
        ),
        array(
            "name" => "Formidable",
            "slug" => 'formidable',
            "entry" => "formidable/formidable.php",
            "plugins" => array("formidable/formidable.php"),
            "settings_description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/formidable/\" target=\"_blank\">Formidable</a>.<br /><strong>Important:</strong> Make sure to add the new Friendly Captcha field to your forms.",
        ),
        array(
            "name" => "Avada Forms",
            "slug" => 'avada_forms',
            "entry" => "avada-forms/avada-forms.php",
            "plugins" => array("avada-forms/avada-forms.php"),
            "settings_description" => "Enable Friendly Captcha for Avada Form Builder.",
        ),
        array(
            "name" => "WordPress Register",
            "slug" => 'wp_register',
            "entry" => "wordpress/wordpress_register.php",
            "settings_description" => "Enable Friendly Captcha for the WordPress sign up form.",
        ),
        array(
            "name" => "WordPress Login",
            "slug" => 'wp_login',
            "entry" => "wordpress/wordpress_login.php",
            "settings_description" => "Enable Friendly Captcha for the WordPress log in form.",
        ),
        array(
            "name" => "WordPress Reset Password",
            "slug" => 'wp_reset_password',
            "entry" => "wordpress/wordpress_reset_password.php",
            "settings_description" => "Enable Friendly Captcha for the WordPress <i>\"Reset Password\"</i> form.",
        ),
        array(
            "name" => "WordPress Comments<br>(guests)",
            "slug" => 'wp_comments',
            "entry" => "wordpress/wordpress_comments.php",
            "settings_description" => "Enable Friendly Captcha for WordPress Comments for guest visitors.",
        ),
        array(
            "name" => "WordPress Comments<br>(logged in users)",
            "slug" => 'wp_comments_logged_in',
            "entry" => "wordpress/wordpress_comments.php",
            "settings_description" => "Enable Friendly Captcha for WordPress Comments for users that are logged in to Wordpress.",
        ),
        array(
            "name" => "WooCommerce Login",
            "slug" => 'wc_login',
            "entry" => "woocommerce/woocommerce_login.php",
            "plugins" => array("woocommerce/woocommerce.php"),
            "settings_description" => "Enable Friendly Captcha for the WooCommerce log in form.",
        ),
        array(
            "name" => "WooCommerce Register",
            "slug" => 'wc_register',
            "entry" => "woocommerce/woocommerce_register.php",
            "plugins" => array("woocommerce/woocommerce.php"),
            "settings_description" => "Enable Friendly Captcha for the WooCommerce sign up form.",
        ),
        array(
            "name" => "WooCommerce Lost Password",
            "slug" => 'wc_lost_password',
            "entry" => "woocommerce/woocommerce_lost_password.php",
            "plugins" => array("woocommerce/woocommerce.php"),
            "settings_description" => "Enable Friendly Captcha for the WooCommerce lost password form.",
        ),
        array(
            "name" => "WooCommerce Checkout",
            "slug" => 'wc_checkout',
            "entry" => "woocommerce/woocommerce_checkout.php",
            "plugins" => array("woocommerce/woocommerce.php"),
            "settings_description" => "Enable Friendly Captcha for the WooCommerce checkout form.",
        ),
        array(
            "name" => "Ultimate Member Login",
            "slug" => 'um_login',
            "entry" => "ultimate-member/ultimate-member_login.php",
            "plugins" => array("ultimate-member/ultimate-member.php"),
            "settings_description" => "Enable Friendly Captcha for the Ultimate Member login form.",
        ),
        array(
            "name" => "Ultimate Member Register",
            "slug" => 'um_register',
            "entry" => "ultimate-member/ultimate-member_register.php",
            "plugins" => array("ultimate-member/ultimate-member.php"),
            "settings_description" => "Enable Friendly Captcha for the Ultimate Member sign up form.",
        ),
        array(
            "name" => "Ultimate Member Reset Password",
            "slug" => 'um_reset_password',
            "entry" => "ultimate-member/ultimate-member_reset_password.php",
            "plugins" => array("ultimate-member/ultimate-member.php"),
            "settings_description" => "Enable Friendly Captcha for the Ultimate Member reset password form.",
        ),
        array(
            "name" => "WPUM Login",
            "slug" => 'wpum_login',
            "entry" => "wpum/wpum_login.php",
            "plugins" => array("wp-user-manager/wp-user-manager.php"),
            "settings_description" => "Enable Friendly Captcha for the WP User Manager login form.",
        ),
        array(
            "name" => "WPUM Registration",
            "slug" => 'wpum_registration',
            "entry" => "wpum/wpum_registration.php",
            "plugins" => array("wp-user-manager/wp-user-manager.php"),
            "settings_description" => "Enable Friendly Captcha for the WP User Manager registration form.",
        ),
        array(
            "name" => "WPUM Password Recovery",
            "slug" => 'wpum_password_recovery',
            "entry" => "wpum/wpum_password-recovery.php",
            "plugins" => array("wp-user-manager/wp-user-manager.php"),
            "settings_description" => "Enable Friendly Captcha for the WP User Manager password recovery form.",
        ),
        array(
            "name" => "Profile Builder Login",
            "slug" => 'pb_login',
            "entry" => "profile-builder/profile_builder_login.php",
            "plugins" => array("profile-builder/index.php"),
            "settings_description" => "Enable Friendly Captcha for the <a href=\"https://de.wordpress.org/plugins/profile-builder/\" target=\"_blank\">Profile Builder</a> login form.",
        ),
        array(
            "name" => "Profile Builder Register",
            "slug" => 'pb_register',
            "entry" => "profile-builder/profile_builder_register.php",
            "plugins" => array("profile-builder/index.php"),
            "settings_description" => "Enable Friendly Captcha for the <a href=\"https://de.wordpress.org/plugins/profile-builder/\" target=\"_blank\">Profile Builder</a> sign up form.",
        ),
        array(
            "name" => "Profile Builder Reset Password",
            "slug" => 'pb_reset_password',
            "entry" => "profile-builder/profile_builder_reset_password.php",
            "plugins" => array("profile-builder/index.php"),
            "settings_description" => "Enable Friendly Captcha for the <a href=\"https://de.wordpress.org/plugins/profile-builder/\" target=\"_blank\">Profile Builder</a> reset password form.",
        ),
        array(
            "name" => "Divi Theme Contact Form",
            "slug" => 'divi',
            "entry" => "divi/divi.php",
            "settings_description" => "Enable Friendly Captcha and replace ReCaptcha in the <a href=\"https://www.elegantthemes.com/gallery/divi//\" target=\"_blank\">Divi Theme</a> contact form.<br /><strong>Important:</strong> Please choose 'FriendlyCaptcha verification' as spam protection in each individual Divi contact form.",
        ),
        array(
            "name" => "WP Job Openings",
            "slug" => 'wp_job_openings',
            "entry" => "wp-job-openings/wp-job-openings.php",
            "plugins" => array("wp-job-openings/wp-job-openings.php", "pro-pack-for-wp-job-openings/pro-pack.php"),
            "settings_description" => "Enable Friendly Captcha for the <a href=\"https://wordpress.org/plugins/wp-job-openings/\" target=\"_blank\">WP Job Openings</a> application form.",
        ),
    );

    public function init()
    {
        if (defined('FRIENDLY_CAPTCHA_VERSION')) {
            FriendlyCaptcha_Plugin::$version = FRIENDLY_CAPTCHA_VERSION;
            FriendlyCaptcha_Plugin::$friendly_challenge_version = FRIENDLY_CAPTCHA_FRIENDLY_CHALLENGE_VERSION;
            FriendlyCaptcha_Plugin::$friendly_captcha_sdk_version = FRIENDLY_CAPTCHA_FRIENDLY_CAPTCHA_SDK_VERSION;
        } else {
            FriendlyCaptcha_Plugin::$version = '0.0.0';
            FriendlyCaptcha_Plugin::$friendly_challenge_version = '0.0.0';
            FriendlyCaptcha_Plugin::$friendly_captcha_sdk_version = '0.0.0';
        }
        $this->plugin_name = 'friendly-captcha';

        FriendlyCaptcha_Plugin::$instance = $this;
    }

    public static function default_error_user_message()
    {
        /* translators: this is the main error message shown to the user when the captcha failed or wasn't completed. */
        return __("Anti-robot verification failed, please try again.", "frcaptcha");
    }

    /**
     * When both the sitekey and the apikey are set, we can consider the plugin configured.
     */
    public function is_configured()
    {
        return $this->get_sitekey() !== null && $this->get_sitekey() !== "" && $this->get_api_key() !== null && $this->get_api_key() !== "";
    }

    public function get_sitekey()
    {
        return trim(get_option(FriendlyCaptcha_Plugin::$option_sitekey_name));
    }

    public function get_api_key()
    {
        return trim(get_option(FriendlyCaptcha_Plugin::$option_api_key_name));
    }

    public function get_skip_style_injection()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_skip_style_injection_name) == 1;
    }

    public function get_enable_mutation_observer()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_enable_mutation_observer_name) == 1;
    }

    public function get_enable_v2()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_enable_v2_name) == 1;
    }

    public function get_solution_field_name()
    {
        if ($this->get_enable_v2()) {
            return "frc-captcha-response";
        } else {
            return "frc-captcha-solution";
        }
    }

    public function get_integration_option_name($slug)
    {
        return "frcaptcha_" . $slug . "_integration_active";
    }

    public function get_integration_active($slug)
    {
        $option_name = $this->get_integration_option_name($slug);
        return get_option($option_name) == 1;
    }

    /* Widget options */

    public function get_widget_language()
    {
        $lang = get_option(FriendlyCaptcha_Plugin::$option_widget_language_name);
        $lang = empty($lang) ? "automatic" : $lang; // Default to automatic
        if ($lang == "automatic") {
            $lang = substr(get_locale(), 0, 2);
            if (!array_key_exists($lang, FRIENDLY_CAPTCHA_SUPPORTED_LANGUAGES)) {
                $lang = "en"; // Fallback to en
            }
        }
        return $lang;
    }

    public function get_widget_dark_theme_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_widget_dark_theme_active_name) == 1;
    }

    /* Endpoint settings */

    public function get_eu_puzzle_endpoint_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_eu_puzzle_endpoint_active_name) == 1;
    }

    public function get_global_puzzle_endpoint_active()
    {
        $eu_active = $this->get_eu_puzzle_endpoint_active();
        if (!$eu_active) return true; // At least one must be enabled.

        return get_option(FriendlyCaptcha_Plugin::$option_global_puzzle_endpoint_active_name) == 1;
    }

    /* Verification failure alert */

    public function show_verification_failed_alert($response)
    {
        update_option(FriendlyCaptcha_Plugin::$option_verification_failed_alert_name, $response);
    }

    public function get_verification_failed_alert()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_verification_failed_alert_name);
    }

    public function remove_verification_failed_alert()
    {
        delete_option(FriendlyCaptcha_Plugin::$option_verification_failed_alert_name);
    }
}

// This creates the singleton instance
if (!isset(FriendlyCaptcha_Plugin::$instance)) {
    $frcaptcha_plugin_instance = new FriendlyCaptcha_Plugin();
    $frcaptcha_plugin_instance->init();
}

// These only contain pure functions
require plugin_dir_path(__FILE__) . 'helpers.php';
require plugin_dir_path(__FILE__) . 'verification.php';

// Register widget routines
require plugin_dir_path(__FILE__) . '../public/widgets.php';

// Set up the admin pages & settings
require plugin_dir_path(__FILE__) . 'admin.php';
require plugin_dir_path(__FILE__) . 'settings.php';

foreach (FriendlyCaptcha_Plugin::$integrations as $integration) {
    if (FriendlyCaptcha_Plugin::$instance->get_integration_active($integration['slug'])) {
        require_once plugin_dir_path(__FILE__) . '../modules/' . $integration['entry'];
    }
}
