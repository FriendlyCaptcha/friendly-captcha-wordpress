<?php

/* In this file the settings for FriendlyCaptcha are registered */

if (is_admin()) {
    add_action('admin_init', 'frcaptcha_settings_init');
    add_action('update_option_' . FriendlyCaptcha_Plugin::$option_sitekey_name, 'frcaptcha_settings_validate');
    add_action('update_option_' . FriendlyCaptcha_Plugin::$option_api_key_name, 'frcaptcha_settings_validate');

    function frcaptcha_settings_init()
    {
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_sitekey_name
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_api_key_name
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_skip_style_injection_name
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_enable_mutation_observer_name
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_enable_v2_name
        );

        foreach (FriendlyCaptcha_Plugin::$integrations as $integration) {
            register_setting(
                FriendlyCaptcha_Plugin::$option_group,
                FriendlyCaptcha_Plugin::$instance->get_integration_option_name($integration['slug'])
            );
        }

        /*Widget settings */
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_widget_language_name
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_widget_dark_theme_active_name
        );

        /*Endpoints*/
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_eu_puzzle_endpoint_active_name
        );
        register_setting(
            FriendlyCaptcha_Plugin::$option_group,
            FriendlyCaptcha_Plugin::$option_global_puzzle_endpoint_active_name
        );

        /* General section */

        // Section
        add_settings_section(
            'frcaptcha_general_settings_section',
            'Account Configuration',
            'frcaptcha_general_section_callback',
            'friendly_captcha_admin'
        );

        add_settings_section(
            'frcaptcha_save_settings_section',
            '',
            'frcaptcha_save_section_callback',
            'friendly_captcha_admin'
        );

        add_settings_field(
            'frcaptcha_settings_sitekey_field',
            'Sitekey',
            'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_general_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_sitekey_name,
                "description" => "Paste your application's sitekey value.<br>Don't have an application yet? Create one <a href=\"https://app.friendlycaptcha.com/dashboard/\" target=\"_blank\">here</a>.",
                "type" => "text"
            )
        );

        add_settings_field(
            'frcaptcha_settings_api_key_field',
            'API Key',
            'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_general_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_api_key_name,
                "description" => "Create a new API key in the <a href=\"https://app.friendlycaptcha.com/dashboard/\" target=\"_blank\">account panel</a> and paste the value here. Keep this one secret!",
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

        $show_all_integrations = isset($_GET['frcaptcha-all-integrations']);

        foreach (FriendlyCaptcha_Plugin::$integrations as $integration) {
            // Only show integrations for plugins that are installed unless the user wants to see all integrations
            if (!$show_all_integrations && array_key_exists('plugins', $integration)) {
                $plugins = $integration['plugins'];

                $active = false;
                foreach ($plugins as $plugin) {
                    if (is_plugin_active($plugin)) {
                        $active = true;
                        break;
                    }
                }

                if (!$active) {
                    continue;
                }
            }

            add_settings_field(
                'frcaptcha_settings_' . $integration['slug'] . '_integration_field',
                $integration['name'],
                'frcaptcha_settings_field_callback',
                'friendly_captcha_admin',
                'frcaptcha_integrations_settings_section',
                array(
                    "option_name" => FriendlyCaptcha_Plugin::$instance->get_integration_option_name($integration['slug']),
                    "description" => $integration['settings_description'],
                    "type" => "checkbox"
                )
            );
        }

        /* Widget settings section */

        // Section
        add_settings_section(
            'frcaptcha_widget_settings_section',
            'Widget Settings',
            'frcaptcha_widget_section_callback',
            'friendly_captcha_admin'
        );

        if (!FriendlyCaptcha_Plugin::$instance->get_enable_v2()) {
            add_settings_field(
                'frcaptcha_settings_widget_language_field',
                'Widget Language',
                'frcaptcha_widget_language_field_callback',
                'friendly_captcha_admin',
                'frcaptcha_widget_settings_section',
                array(
                    "option_name" => FriendlyCaptcha_Plugin::$option_widget_language_name,
                    "description" => "Set the language for the widget. Need another language? <a href=\"https://docs.friendlycaptcha.com/#/widget_api?id=data-lang-attribute\">Help us translate</a>.",
                )
            );
        }

        add_settings_field(
            'frcaptcha_settings_widget_theme_field',
            'Dark theme',
            'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_widget_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_widget_dark_theme_active_name,
                "description" => "Enable a dark theme for Friendly Captcha widgets.",
                "type" => "checkbox"
            )
        );

        if (!FriendlyCaptcha_Plugin::$instance->get_enable_v2()) {
            add_settings_field(
                'frcaptcha_settings_skip_style_injection_field',
                'Disable Style Injection',
                'frcaptcha_settings_field_callback',
                'friendly_captcha_admin',
                'frcaptcha_widget_settings_section',
                array(
                    "option_name" => FriendlyCaptcha_Plugin::$option_skip_style_injection_name,
                    "description" => "Don't load the CSS-Styles for the widget. Use this if you want to style the widget yourself.",
                    "type" => "checkbox"
                )
            );
        }

        add_settings_field(
            'frcaptcha_settings_mutation_observer',
            'Dynamically Initialize',
            'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_widget_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_enable_mutation_observer_name,
                "description" => "Make Friendly Captcha look for new widgets that are dynamically added to the page.<br>Enable this when you are using Friendly Captcha in a popup or a multi-step form.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_enable_v2',
            'Use Friendly Captcha v2',
            'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_widget_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_enable_v2_name,
                "description" => " Friendly Captcha v2 is in preview and is <b>not yet intended for production use</b>. You need to enable v2 in the Friendly Captcha dashboard.",
                "type" => "checkbox"
            )
        );

        add_settings_field(
            'frcaptcha_settings_enable_v2',
            'Use Friendly Captcha v2',
            'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_widget_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_enable_v2_name,
                "description" => " Friendly Captcha v2 is in its alpha stage and is <b>not yet intended for production use</b>.",
                "type" => "checkbox"
            )
        );


        /* Endpoint section */

        // Section
        add_settings_section(
            'frcaptcha_endpoint_settings_section',
            'Endpoint Settings (optional)',
            'frcaptcha_endpoint_section_callback',
            'friendly_captcha_admin'
        );

        add_settings_field(
            'frcaptcha_settings_global_endpoint_field',
            '🌍 Global Endpoint',
            'frcaptcha_settings_field_callback',
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
            '🇪🇺 Dedicated EU Endpoint',
            'frcaptcha_settings_field_callback',
            'friendly_captcha_admin',
            'frcaptcha_endpoint_settings_section',
            array(
                "option_name" => FriendlyCaptcha_Plugin::$option_eu_puzzle_endpoint_active_name,
                "description" => "Enable the dedicated EU endpoint service, see the <a href=\"https://docs.friendlycaptcha.com/#/eu_endpoint\">documentation</a> for more details.",
                "type" => "checkbox"
            )
        );
    }

    $settings_validated = false;
    function frcaptcha_settings_validate()
    {
        // Deduplicate validation when multiple fields are changed
        global $settings_validated;
        if ($settings_validated) {
            $settings_validated = true;
            return;
        }

        $sitekey = get_option(FriendlyCaptcha_Plugin::$option_sitekey_name);
        $api_key = get_option(FriendlyCaptcha_Plugin::$option_api_key_name);

        $verification = frcaptcha_verify_auth_info($sitekey, $api_key);

        if (!$verification['success']) {
            add_settings_error(FriendlyCaptcha_Plugin::$option_api_key_name, 'config_invalid', $verification['message'], 'error');
        }
    }
}
