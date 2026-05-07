<?php
/*
 * FriendlyCaptcha Helper to integrate better with custom code.
 */

/** Add captcha to forms
 * @param  string $html  html to append the captcha widget to
 * @return string        html with captcha widget appended
 */
add_filter("frc_captcha_append_widget", function ($html) {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $html;
    }

    frcaptcha_enqueue_widget_scripts();

    $widget = frcaptcha_generate_widget_tag_from_plugin($plugin);
    return $html . $widget;
});

/** Validate captcha on form submission
 * @param  string $solution value of $_POST['frc-captcha-solution']
 * @param  bool $lax_on_failure how to decide on network failure: returning false here means a broken network failure or integration in settings is deactivated is treated as a bot.
 * @return bool           true = human, false = bot / missing solution
 */
add_filter(
    "frc_captcha_validation",
    function ($solution, $lax_on_failure) {
        $plugin = FriendlyCaptcha_Plugin::$instance;

        if (!$plugin->is_configured()) {
            return $lax_on_failure;
        }

        if (empty($solution)) {
            return false;
        }

        $verification = frcaptcha_verify_captcha_solution(
            $solution,
            $plugin->get_sitekey(),
            $plugin->get_api_key(),
            "generic_integration",
        );

        return $verification["success"];
    },
    10,
    2,
);
