<?php

require plugin_dir_path( __FILE__ ) . '../admin/options.php';

if (is_admin()) {
    add_action( 'admin_menu', 'frcaptcha_options_page' );

    function frcaptcha_options_page() {
        add_options_page(
            'Friendly Captcha',
            'Friendly Captcha',
            'manage_options',
            'friendly_captcha_admin',
            'frcaptcha_options_page_html',
            30
        );
    }
}
