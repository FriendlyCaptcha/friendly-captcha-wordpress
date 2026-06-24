<?php

require plugin_dir_path(__FILE__) . '../admin/options.php';

if (is_admin()) {
    add_action('admin_menu', 'frcaptcha_options_page');

    // Add link to settings page in the navbar
    function frcaptcha_options_page()
    {
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
    add_filter('plugin_action_links_friendly-captcha/friendly-captcha.php', 'frcaptcha_settings_link');
    function frcaptcha_settings_link($links)
    {
        $url = esc_url(add_query_arg(
            'page',
            'friendly_captcha_admin',
            get_admin_url() . 'options-general.php'
        ));
        $settings_link = "<a href='$url'>" . __('Settings') . '</a>';

        array_push(
            $links,
            $settings_link
        );
        return $links;
    }

    if (!FriendlyCaptcha_Plugin::$instance->is_configured()) {
        function frcaptcha_admin_notice__not_configured()
        {
            $url = esc_url(add_query_arg(
                'page',
                'friendly_captcha_admin',
                get_admin_url() . 'options-general.php'
            ));

?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <b>Friendly Captcha is not configured yet!</b>
                    Visit the <a href="<?php echo $url ?>">Friendly Captcha settings</a> and enter a valid Sitekey and API Key to complete the setup.
                </p>
            </div>
        <?php
        }

        add_action('admin_notices', 'frcaptcha_admin_notice__not_configured');
    }

    // Deferred to admin_init so pluggable functions (current_user_can) are loaded
    // and the handler only runs on real admin requests, not admin-ajax.
    add_action('admin_init', 'frcaptcha_maybe_dismiss_verification_failed_alert');
    function frcaptcha_maybe_dismiss_verification_failed_alert()
    {
        if (
            isset($_GET['frcaptcha-verification-failed-dismissed']) &&
            current_user_can('manage_options') &&
            isset($_GET['_wpnonce']) &&
            wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'frcaptcha-dismiss-verification-failed')
        ) {
            FriendlyCaptcha_Plugin::$instance->remove_verification_failed_alert();
        }
    }

    if (FriendlyCaptcha_Plugin::$instance->get_verification_failed_alert() != false) {
        function frcaptcha_admin_notice__verification_failed()
        {
            $alert = FriendlyCaptcha_Plugin::$instance->get_verification_failed_alert();
            if ($alert == false) {
                return;
            }

            $settings_url = esc_url(add_query_arg(
                'page',
                'friendly_captcha_admin',
                get_admin_url() . 'options-general.php'
            ));

            $dismiss_url = esc_url(wp_nonce_url(
                add_query_arg('frcaptcha-verification-failed-dismissed', '1'),
                'frcaptcha-dismiss-verification-failed'
            ));

        ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <b>Friendly Captcha verification has failed!</b>
                    <br>
                    This is usually because you have entered an incorrect API Key. If you aren't sure, visit the <a href="<?php echo $settings_url ?>">Friendly Captcha settings</a> and enter a valid Sitekey and API Key.
                    <?php if (!empty($alert['time'])) : ?>
                        <br><br>
                        Last failure: <b><?php echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format'), $alert['time'])); ?></b>. This notice clears automatically a week after the last failure.
                    <?php endif; ?>
                    <br><br>
                    <code><?php echo esc_html($alert['response']); ?></code>
                </p>
                <a href="<?php echo $dismiss_url ?>" class="notice-dismiss" style="text-decoration: none;">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </a>
            </div>
<?php
        }

        add_action('admin_notices', 'frcaptcha_admin_notice__verification_failed');
    }
}
