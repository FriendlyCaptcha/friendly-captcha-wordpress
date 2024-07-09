<?php

add_action('coblocks_register_form_blocks', array('frcaptcha_coblocks_load_addon', 'load'), 5);
add_action('coblocks_before_form_submit', array('frcaptcha_coblocks_load_addon', 'before_form_submit'), 5, 2);


class frcaptcha_coblocks_load_addon
{
    public static function load()
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return;
        }

        $instance = new frcaptcha_coblocks_load_addon();

        register_block_type(
            __DIR__,
            array(
                'render_callback' => array($instance, 'render_field_friendly_captcha'),
            )
        );

        add_action('enqueue_block_editor_assets', array($instance, 'frcaptcha_coblocks_enqueue_block_editor_assets'));
    }

    public function render_field_friendly_captcha($is_preview)
    {
        frcaptcha_enqueue_widget_scripts();
        return frcaptcha_generate_widget_tag_from_plugin(FriendlyCaptcha_Plugin::$instance);
    }

    public function frcaptcha_coblocks_enqueue_block_editor_assets()
    {
        wp_enqueue_script(
            'frcaptcha_coblocks_load_addon',
            plugin_dir_url(__FILE__) . '/script.js',
            array('wp-blocks', 'wp-editor', 'wp-element', 'wp-i18n'),
            filemtime(plugin_dir_path(__FILE__) . 'script.js')
        );
        wp_localize_script('frcaptcha_coblocks_load_addon', 'frcaptcha_coblocks_settings', ['preview' => plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/preview.png']);
    }

    public static function before_form_submit($postData, $atts)
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;

        if (!$plugin->is_configured()) {
            return;
        }

        // if the current page has any friendly captcha widget, the check will be enforced
        // that means if there are two forms, they both need to use the widget
        // checking the existing widget for the current form seems not practical
        // most pages are expected to have only one form though
        if (!has_block('frcaptcha/field-friendly-captcha')) {
            return;
        }

        $errorPrefix = '<strong>' . __('Error', 'wp-captcha') . '</strong> : ';
        $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

        if (empty($solution)) {
            wp_die($errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha"));
        }

        $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());
        if (!$verification["success"]) {
            wp_die($errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message());
        }

        $fieldName = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();
        unset($_POST[$fieldName]); // suppress the solution in email message
    }
}
