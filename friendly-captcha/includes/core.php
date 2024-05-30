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

    // Integrations
    public static $option_contact_form_7_integration_active_name = "frcaptcha_contact_form_7_integration_active";
    public static $option_f12_cf7_doubleoptin_integration_active_name = "frcaptcha_f12_cf7_doubleoptin_integration_active";
    public static $option_wpforms_integration_active_name = "frcaptcha_wpforms_integration_active";
    public static $option_gravity_forms_integration_active_name = "frcaptcha_gravity_forms_integration_active";
    public static $option_coblocks_integration_active_name = "frcaptcha_coblocks_integration_active";
    public static $option_fluentform_integration_active_name = "frcaptcha_fluentform_integration_active";
    public static $option_elementor_forms_integration_active_name = "frcaptcha_elementor_integration_active";
    public static $option_html_forms_integration_active_name = "frcaptcha_html_forms_integration_active";
    public static $option_forminator_integration_active_name = "frcaptcha_forminator_integration_active";
    public static $option_formidable_integration_active_name = "frcaptcha_formidable_integration_active";
    public static $option_avada_forms_integration_active_name = "frcaptcha_avada_forms_integration_active";

    public static $option_wp_register_integration_active_name = "frcaptcha_wp_register_integration_active";
    public static $option_wp_login_integration_active_name = "frcaptcha_wp_login_integration_active";
    public static $option_wp_reset_password_integration_active_name = "frcaptcha_wp_reset_password_integration_active";
    public static $option_wp_comments_integration_active_name = "frcaptcha_wp_comments_integration_active";
    public static $option_wp_comments_logged_in_integration_active_name = "frcaptcha_wp_comments_logged_in_integration_active";

    public static $option_wc_register_integration_active_name = "frcaptcha_wc_register_integration_active";
    public static $option_wc_login_integration_active_name = "frcaptcha_wc_login_integration_active";
    public static $option_wc_lost_password_integration_active_name = "frcaptcha_wc_lost_password_integration_active";
    public static $option_wc_checkout_integration_active_name = "frcaptcha_wc_checkout_integration_active";

    public static $option_um_login_integration_active_name = "frcaptcha_um_login_integration_active";
    public static $option_um_register_integration_active_name = "frcaptcha_um_register_integration_active";
    public static $option_um_reset_password_integration_active_name = "frcaptcha_um_reset_password_integration_active";

    public static $option_wpum_registration_integration_active_name = "frcaptcha_wpum_registration_integration_active";
    public static $option_wpum_login_integration_active_name = "frcaptcha_wpum_login_integration_active";
    public static $option_wpum_password_recovery_integration_active_name = "frcaptcha_wpum_password_recovery_integration_active";

    public static $option_pb_login_integration_active_name = "frcaptcha_pb_login_integration_active";
    public static $option_pb_register_integration_active_name = "frcaptcha_pb_register_integration_active";
    public static $option_pb_reset_password_integration_active_name = "frcaptcha_pb_reset_password_integration_active";
    public static $option_divi_integration_active_name = "frcaptcha_divi_integration_active";

    public static $option_widget_language_name = "frcaptcha_widget_language";
    public static $option_widget_dark_theme_active_name = "frcaptcha_widget_dark_theme_active";

    public static $option_global_puzzle_endpoint_active_name = "frcaptcha_global_endpoint_active";
    public static $option_eu_puzzle_endpoint_active_name = "frcaptcha_eu_endpoint_active";

    public static $option_verification_failed_alert_name = "frcaptcha_verification_failed_alert";

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

    public function get_contact_form_7_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_contact_form_7_integration_active_name) == 1;
    }

    public function get_f12_cf7_doubleoptin_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_f12_cf7_doubleoptin_integration_active_name) == 1;
    }

    public function get_wpforms_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wpforms_integration_active_name) == 1;
    }

    public function get_gravity_forms_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_gravity_forms_integration_active_name) == 1;
    }

    public function get_coblocks_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_coblocks_integration_active_name) == 1;
    }

    public function get_fluentform_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_fluentform_integration_active_name) == 1;
    }

    public function get_elementor_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_elementor_forms_integration_active_name) == 1;
    }

    public function get_html_forms_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_html_forms_integration_active_name) == 1;
    }

    public function get_forminator_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_forminator_integration_active_name) == 1;
    }

    public function get_formidable_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_formidable_integration_active_name) == 1;
    }

    public function get_avada_forms_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_avada_forms_integration_active_name) == 1;
    }

    public function get_wp_register_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wp_register_integration_active_name) == 1;
    }

    public function get_wp_login_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wp_login_integration_active_name) == 1;
    }

    public function get_wp_reset_password_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wp_reset_password_integration_active_name) == 1;
    }

    public function get_wp_comments_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wp_comments_integration_active_name) == 1;
    }

    public function get_wp_comments_logged_in_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wp_comments_logged_in_integration_active_name) == 1;
    }

    public function get_wc_login_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wc_login_integration_active_name) == 1;
    }

    public function get_wc_register_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wc_register_integration_active_name) == 1;
    }

    public function get_wc_lost_password_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wc_lost_password_integration_active_name) == 1;
    }

    public function get_wc_checkout_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wc_checkout_integration_active_name) == 1;
    }

    public function get_um_login_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_um_login_integration_active_name) == 1;
    }

    public function get_um_register_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_um_register_integration_active_name) == 1;
    }

    public function get_um_reset_password_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_um_reset_password_integration_active_name) == 1;
    }

    public function get_wpum_login_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wpum_login_integration_active_name) == 1;
    }

    public function get_wpum_registration_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wpum_registration_integration_active_name) == 1;
    }

    public function get_wpum_password_recovery_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_wpum_password_recovery_integration_active_name) == 1;
    }

    public function get_pb_login_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_pb_login_integration_active_name) == 1;
    }

    public function get_pb_register_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_pb_register_integration_active_name) == 1;
    }

    public function get_pb_reset_password_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_pb_reset_password_integration_active_name) == 1;
    }


    public function get_divi_active()
    {
        return get_option(FriendlyCaptcha_Plugin::$option_divi_integration_active_name) == 1;
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

    public function show_verification_failed_alert() {
        update_option(FriendlyCaptcha_Plugin::$option_verification_failed_alert_name, 1);
    }

    public function get_verification_failed_alert() {
        return get_option(FriendlyCaptcha_Plugin::$option_verification_failed_alert_name) == 1;
    }

    public function remove_verification_failed_alert() {
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

if (FriendlyCaptcha_Plugin::$instance->get_contact_form_7_active()) {
    require plugin_dir_path(__FILE__) . '../modules/contact-form-7/contact-form-7.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wpforms_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wpforms/wpforms.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_gravity_forms_active()) {
    require plugin_dir_path(__FILE__) . '../modules/gravityforms/gravityforms.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_coblocks_active()) {
    require plugin_dir_path(__FILE__) . '../modules/coblocks/coblocks.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_fluentform_active()) {
    require plugin_dir_path(__FILE__) . '../modules/fluentform/fluentform.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_elementor_active()) {
    require plugin_dir_path(__FILE__) . '../modules/elementor/elementor.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_html_forms_active()) {
    require plugin_dir_path(__FILE__) . '../modules/html-forms/html-forms.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_forminator_active()) {
    require plugin_dir_path(__FILE__) . '../modules/forminator/forminator.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_formidable_active()) {
    require plugin_dir_path(__FILE__) . '../modules/formidable/formidable.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_avada_forms_active()) {
    require plugin_dir_path(__FILE__) . '../modules/avada-forms/avada-forms.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wp_register_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wordpress/wordpress_register.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wp_login_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wordpress/wordpress_login.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wp_reset_password_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wordpress/wordpress_reset_password.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wp_comments_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wordpress/wordpress_comments.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wc_login_active()) {
    require plugin_dir_path(__FILE__) . '../modules/woocommerce/woocommerce_login.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wc_register_active()) {
    require plugin_dir_path(__FILE__) . '../modules/woocommerce/woocommerce_register.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wc_lost_password_active()) {
    require plugin_dir_path(__FILE__) . '../modules/woocommerce/woocommerce_lost_password.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wc_checkout_active()) {
    require plugin_dir_path(__FILE__) . '../modules/woocommerce/woocommerce_checkout.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_um_login_active()) {
    require plugin_dir_path(__FILE__) . '../modules/ultimate-member/ultimate-member_login.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_um_register_active()) {
    require plugin_dir_path(__FILE__) . '../modules/ultimate-member/ultimate-member_register.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_um_reset_password_active()) {
    require plugin_dir_path(__FILE__) . '../modules/ultimate-member/ultimate-member_reset_password.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wpum_login_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wpum/wpum_login.php';
    require_once plugin_dir_path(__FILE__) . '../modules/wpum/wpum_validate.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wpum_registration_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wpum/wpum_registration.php';
    require_once plugin_dir_path(__FILE__) . '../modules/wpum/wpum_validate.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_wpum_password_recovery_active()) {
    require plugin_dir_path(__FILE__) . '../modules/wpum/wpum_password-recovery.php';
    require_once plugin_dir_path(__FILE__) . '../modules/wpum/wpum_validate.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_pb_login_active()) {
    require plugin_dir_path(__FILE__) . '../modules/profile-builder/profile_builder_login.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_pb_register_active()) {
    require plugin_dir_path(__FILE__) . '../modules/profile-builder/profile_builder_register.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_pb_reset_password_active()) {
    require plugin_dir_path(__FILE__) . '../modules/profile-builder/profile_builder_reset_password.php';
}

if (FriendlyCaptcha_Plugin::$instance->get_divi_active()) {
    require plugin_dir_path(__FILE__) . '../modules/divi/divi.php';
}
