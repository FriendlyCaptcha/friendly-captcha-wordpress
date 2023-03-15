<?php

require plugin_dir_path( __FILE__ ) . '../admin/options.php';

if (is_admin()) {
    add_action( 'admin_menu', 'frcaptcha_options_page' );

    // Add link to settings page in the navbar
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

    // Add link to settings in the plugin list
    add_filter( 'plugin_action_links_friendly-captcha/friendly-captcha.php', 'frcaptcha_settings_link' );
    function frcaptcha_settings_link( $links ) {
        $url = esc_url( add_query_arg(
            'page',
            'friendly_captcha_admin',
            get_admin_url() . 'options-general.php'
        ) );
        $settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
        
        array_push(
            $links,
            $settings_link
        );
        return $links;
    }

    if (!FriendlyCaptcha_Plugin::$instance->is_configured()) {
        function frcaptcha_admin_notice__not_configured() {
            $url = esc_url( add_query_arg(
                'page',
                'friendly_captcha_admin',
                get_admin_url() . 'options-general.php'
            ) );

            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <b>Friendly Captcha is not configured properly!</b> 
                    Visit the <a href="<?php echo $url ?>">Friendly Captcha settings</a> and enter a valid Sitekey and API Key to complete the setup.</p>
            </div>
            <?php
        }

        add_action( 'admin_notices', 'frcaptcha_admin_notice__not_configured' );
    }
}