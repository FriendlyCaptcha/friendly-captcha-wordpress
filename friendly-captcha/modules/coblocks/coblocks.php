<?php

add_action( 'coblocks_register_form_blocks', array( 'frcaptcha_coblocks_load_addon', 'load' ), 5 );

class frcaptcha_coblocks_load_addon {
    public static function load() {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured() or !$plugin->get_coblocks_active()) {
            return;
        }

        $instance = new frcaptcha_coblocks_load_addon();

        register_block_type(
            __DIR__,
            array(
                'render_callback' => array(  $instance, 'render_field_friendly_captcha'),
            )
        );

        wp_enqueue_script('frcaptcha_coblocks_load_addon', plugin_dir_url(__FILE__) . '/script.js',
            array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-i18n' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'script.js' )
        );
    }


    public static function render_field_friendly_captcha($is_preview)
    {
        frcaptcha_enqueue_widget_scripts();
        return frcaptcha_generate_widget_tag_from_plugin(FriendlyCaptcha_Plugin::$instance);
    }
}




