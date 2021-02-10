<?php

/* Main entry point */ 

// TODO: is this necessary? It breaks intellisense..
// if ( !class_exists( 'FriendlyCaptcha_Plugin' ) ) {    
    class FriendlyCaptcha_Plugin {

        /**
         * Singleton global instance
         */
        public static $instance;
        public $plugin_name;

        public static $version;
        public static $friendly_challenge_version;

        // Global constants
        public static $option_group = "frcaptcha_options";
        public static $option_sitekey_name = "frcaptcha_sitekey";
        public static $option_api_key_name = "frcaptcha_api_key";

        public static $option_contact_form_7_integration_active_name = "frcaptcha_contact_form_7_integration_active";
        public static $option_wpforms_integration_active_name = "frcaptcha_wpforms_integration_active";
        public static $option_wp_register_integration_active_name = "frcaptcha_wp_register_integration_active";
        public static $option_wp_login_integration_active_name = "frcaptcha_wp_login_integration_active";
        public static $option_wp_reset_password_integration_active_name = "frcaptcha_wp_reset_password_integration_active";

        public static $option_widget_language_name = "frcaptcha_widget_language";

        public static $default_error_user_message = "Anti-robot verification failed, please try again.";

        public function init() {
            if ( defined( 'FRIENDLY_CAPTCHA_VERSION' ) ) {
                FriendlyCaptcha_Plugin::$version = FRIENDLY_CAPTCHA_VERSION;
                FriendlyCaptcha_Plugin::$friendly_challenge_version = FRIENDLY_CAPTCHA_FRIENDLY_CHALLENGE_VERSION;
            } else {
                FriendlyCaptcha_Plugin::$version = '0.0.0';
                FriendlyCaptcha_Plugin::$friendly_challenge_version = '0.0.0';
            }
            $this->plugin_name = 'friendly-captcha';

            FriendlyCaptcha_Plugin::$instance = $this;
        }

        /**
         * When both the sitekey and the apikey are set, we can consider the plugin configured.
         */
        public function is_configured() {
            return null !== $this->get_sitekey() && null !== $this->get_api_key();
        }

        public function get_sitekey() {
            return trim(get_option(FriendlyCaptcha_Plugin::$option_sitekey_name));
        }

        public function get_api_key() {
            return trim(get_option(FriendlyCaptcha_Plugin::$option_api_key_name));
        }

        public function get_contact_form_7_active() {
            return get_option(FriendlyCaptcha_Plugin::$option_contact_form_7_integration_active_name) == 1;
        }

        public function get_wpforms_active() {
            return get_option(FriendlyCaptcha_Plugin::$option_wpforms_integration_active_name) == 1;
        }

        public function get_wp_register_active() {
            return get_option(FriendlyCaptcha_Plugin::$option_wp_register_integration_active_name) == 1;
        }

        public function get_wp_login_active() {
            return get_option(FriendlyCaptcha_Plugin::$option_wp_login_integration_active_name) == 1;
        }

        public function get_wp_reset_password_active() {
            return get_option(FriendlyCaptcha_Plugin::$option_wp_reset_password_integration_active_name) == 1;
        }

        public function get_widget_language() {
            $lang = get_option(FriendlyCaptcha_Plugin::$option_widget_language_name);
            return empty($lang) ? "en" : $lang; // Default to en
        }
    }

    // This creates the singleton instance
    if ( !defined(FriendlyCaptcha_Plugin::$instance)) {
        $frcaptcha_plugin_instance = new FriendlyCaptcha_Plugin();
        $frcaptcha_plugin_instance->init();
    }

    // These only contain pure functions
    require plugin_dir_path( __FILE__ ) . 'helpers.php';
    require plugin_dir_path( __FILE__ ) . 'verification.php';
    
    // Register widget routines
    require plugin_dir_path( __FILE__ ) . '../public/widgets.php';

    // Set up the admin pages & settings
    require plugin_dir_path( __FILE__ ) . 'admin.php';
    require plugin_dir_path( __FILE__ ) . 'settings.php';

    if (FriendlyCaptcha_Plugin::$instance->get_contact_form_7_active()) {
        require plugin_dir_path( __FILE__ ) . '../modules/contact-form-7/contact-form-7.php';
    }

    if (FriendlyCaptcha_Plugin::$instance->get_wpforms_active()) {
        require plugin_dir_path( __FILE__ ) . '../modules/wpforms/wpforms.php';
    }

    if (FriendlyCaptcha_Plugin::$instance->get_wp_register_active()) {
        require plugin_dir_path( __FILE__ ) . '../modules/wordpress/wordpress_register.php';
    }

    if (FriendlyCaptcha_Plugin::$instance->get_wp_login_active()) {
        require plugin_dir_path( __FILE__ ) . '../modules/wordpress/wordpress_login.php';
    }

    if (FriendlyCaptcha_Plugin::$instance->get_wp_reset_password_active()) {
        require plugin_dir_path( __FILE__ ) . '../modules/wordpress/wordpress_reset_password.php';
    }
// }
