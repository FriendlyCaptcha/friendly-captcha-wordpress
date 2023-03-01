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

if (!FriendlyCaptcha_Plugin::$instance->is_configured()) {
    function frcaptcha_admin_notice__not_configured() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><b>Friendly Captcha is not configured properly!</b> Go to the settings to set the Sitekey and API key.</p>
        </div>
        <?php
    }

    add_action( 'admin_notices', 'frcaptcha_admin_notice__not_configured' );
}