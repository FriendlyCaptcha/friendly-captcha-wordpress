<?php

/**
 * FriendlyCaptcha integration for SI Schedule+Registration (tws-siwp) plugin.
 * 
 * Hooks into the tws-siwp form display and validation filters.
 */

// Add captcha widget to registration form
add_filter(
    'tws_siwp_captcha_widget', function ($html) {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return $html;
        }

        frcaptcha_enqueue_widget_scripts();

        $widget = frcaptcha_generate_widget_tag_from_plugin($plugin);
        return $html . $widget;
    }
);

// Validate captcha on form submission
// $form_data is passed for potential future use (e.g. logging)
add_filter(
    'tws_siwp_validate_captcha', function ($error, $form_data) {
        unset($form_data); // Currently unused, but available for future extensions
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return $error;
        }

        $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

        if (empty($solution)) {
            /* translators: Error message when captcha was not completed */
            return __("Bitte lösen Sie das Anti-Spam-Rätsel.", "frcaptcha");
        }

        $verification = frcaptcha_verify_captcha_solution(
            $solution, 
            $plugin->get_sitekey(), 
            $plugin->get_api_key(), 
            'tws-siwp'
        );

        if (!$verification["success"]) {
            return FriendlyCaptcha_Plugin::default_error_user_message();
        }

        return $error;
    }, 10, 2
);
