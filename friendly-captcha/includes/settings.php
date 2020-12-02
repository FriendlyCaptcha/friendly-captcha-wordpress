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

        /* General section */

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
                "description" => "Paste your application's sitekey value.<br>Don't have an application yet? Create one <a href=\"https://friendlycaptcha.com/account\">here</a>.",
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
                "description" => "Create a new API key in the <a href=\"https://friendlycaptcha.com/account\">account panel</a> and paste the value here. Keep this one secret!",
                "type" => "password"
            )
        );

        /* Integrations section */

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
                "description" => "Enable Friendly Captcha for <a href=\"https://en-gb.wordpress.org/plugins/wpforms/\">WPForms</a> and <a href=\"https://en-gb.wordpress.org/plugins/wpforms-lite/\">WPForms lite</a> forms.",
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
                "description" => "Enable Friendly Captcha for <a href=\"https://en-gb.wordpress.org/plugins/contact-form-7/\">Contact Form 7</a> forms.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_register_integration_field',
            'Wordpress Register', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_register_integration_active_name,
                "description" => "Enable Friendly Captcha for the Wordpress sign up form.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_login_integration_field',
            'Wordpress Login', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_login_integration_active_name,
                "description" => "Enable Friendly Captcha for the Wordpress log in form.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_wp_forgot_password_integration_field',
            'Wordpress Forgot Password', 'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_integrations_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_wp_reset_password_integration_active_name,
                "description" => "Enable Friendly Captcha for the Wordpress <i>\"Reset Password\"</i> form.",
                "type" => "checkbox"
            )
        );


    }

}