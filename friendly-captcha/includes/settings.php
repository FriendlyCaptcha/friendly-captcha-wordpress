<?php

/* In this file the settings for FriendlyCaptcha are registered */

if (is_admin()) {
    add_action('admin_init', 'frcaptcha_settings_init');

    function frcaptcha_settings_init() {
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_sitekey_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_api_key_name,
        );

        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_contact_form_7_integration_active_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_wpforms_integration_active_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_wp_register_integration_active_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_wp_login_integration_active_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_wp_reset_password_integration_active_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_wp_comments_integration_active_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_wp_comments_logged_in_integration_active_name,
        );

        /*Widget settings */
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_widget_language_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_widget_dark_theme_active_name,
        );

        /*Endpoints*/
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_eu_puzzle_endpoint_active_name,
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_global_puzzle_endpoint_active_name,
        );

        /* General section */

        // Section
        add_settings_section(
            'frcaptcha_general_settings_section',
            'Account Configuration',
            'frcaptcha_general_section_callback',
            'friendly_captcha_admin'
        );

        add_settings_field(
            'frcaptcha_settings_sitekey_field',
            'Sitekey', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_general_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_sitekey_name,
                "description" => "Paste your application's sitekey value.<br>Don't have an application yet? Create one <a href=\"https://friendlycaptcha.com/account\" target=\"_blank\">here</a>.",
                "type" => "text"
            )
        );

        add_settings_field(
            'frcaptcha_settings_api_key_field',
            'API Key', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_general_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_api_key_name,
                "description" => "Create a new API key in the <a href=\"https://app.friendlycaptcha.com/account\" target=\"_blank\">account panel</a> and paste the value here. Keep this one secret!",
                "type" => "password"
            )
        );

        /* Integrations section */

        // Section
        add_settings_section(
            'frcaptcha_integrations_settings_section',
            'Integrations',
            'frcaptcha_integrations_section_callback',
            'friendly_captcha_admin'
        );

        add_settings_field(
            'frcaptcha_settings_wpforms_integration_field',
            'WPForms', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wpforms_integration_active_name,
                "description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/wpforms/\" target=\"_blank\">WPForms</a> and <a href=\"https://wordpress.org/plugins/wpforms-lite/\"  target=\"_blank\">WPForms lite</a> forms.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wpcf7_integration_field',
            'Contact Form 7', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_contact_form_7_integration_active_name,
                "description" => "Enable Friendly Captcha for <a href=\"https://wordpress.org/plugins/contact-form-7/\" target=\"_blank\">Contact Form 7</a> forms.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_register_integration_field',
            'WordPress Register', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_register_integration_active_name,
                "description" => "Enable Friendly Captcha for the WordPress sign up form.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_login_integration_field',
            'WordPress Login', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_login_integration_active_name,
                "description" => "Enable Friendly Captcha for the WordPress log in form.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_forgot_password_integration_field',
            'WordPress Forgot Password', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_reset_password_integration_active_name,
                "description" => "Enable Friendly Captcha for the WordPress <i>\"Reset Password\"</i> form.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_comments_integration_field',
            'WordPress Comments<br>(guests)', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_comments_integration_active_name,
                "description" => "Enable Friendly Captcha for WordPress Comments for guest visitors.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_comments_logged_in_integration_field',
            'WordPress Comments<br>(logged in users)', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_comments_logged_in_integration_active_name,
                "description" => "Enable Friendly Captcha for WordPress Comments for users that are logged in to Wordpress.",
                "type" => "checkbox"
            )
        );

        /* Widget settings section */

        // Section
        add_settings_section(
            'frcaptcha_widget_settings_section',
            'Widget Settings',
            'frcaptcha_widget_section_callback',
            'friendly_captcha_admin',
        );

        add_settings_field(
            'frcaptcha_settings_widget_language_field',
            'Widget Language',
            'frcaptcha_widget_language_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_widget_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_widget_language_name,
                "description" => "Set the language for the widget. Need another language? <a href=\"http://docs.friendlycaptcha.com/#/widget_api?id=data-lang-attribute\">Help us translate</a>.",
            )
        );

        add_settings_field(
            'frcaptcha_settings_widget_theme_field',
            'Dark theme', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_widget_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_widget_dark_theme_active_name,
                "description" => "Enable a dark theme for Friendly Captcha widgets.",
                "type" => "checkbox"
            )
        );

        /* Endpoint section */

        // Section
        add_settings_section(
            'frcaptcha_endpoint_settings_section',
            'Endpoint Settings (advanced, requires Business or Enterprise account)',
            'frcaptcha_endpoint_section_callback',
            'friendly_captcha_admin',
        );

        add_settings_field(
            'frcaptcha_settings_global_endpoint_field',
            '🌍 Global Endpoint', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_endpoint_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_global_puzzle_endpoint_active_name,
                "description" => "Enable the default global puzzle service.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_eu_endpoint_field',
            '🇪🇺 Dedicated EU Endpoint', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_endpoint_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_eu_puzzle_endpoint_active_name,
                "description" => "Enable the dedicated EU endpoint service, see the <a href=\"http://docs.friendlycaptcha.com/#/eu_endpoint\">documentation</a> for more details.",
                "type" => "checkbox"
            )
        );
    }
}
