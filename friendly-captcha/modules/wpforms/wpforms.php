<?php

add_action('wpforms_wp_footer_end', 'frcaptcha_wpforms_friendly_captcha_enqueue_scripts', 10, 0);

function frcaptcha_wpforms_friendly_captcha_enqueue_scripts()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    frcaptcha_echo_script_tags();

    // We add our own script to reset the widget after form submission
    // wp_enqueue_script doesn't work with WPForms for some reason, so we have to echo the script tags manually.
    echo '<script async defer src="' . plugin_dir_url(__FILE__) . 'script.js"></script>';

    // The CSS reset of WPForms is really agressive.. so we add the frcaptcha styles but more `!important`ly now.
    // Really wish this wasn't necessary..
    echo "<style>
    .frc-captcha * { /* Mostly a CSS reset so existing website styles don't clash */
        text-align: initial;
        border-radius: 4px !important;
        font-size: 14px !important;
        line-height: 1.35 !important;
        background-color: initial;
        color: #222 !important;
        font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;
    }
    
    .frc-captcha {
        position: relative !important;
        width: 280px !important;
        border: 1px solid #ddd !important;
        padding-bottom: 12px !important;
        background-color: #fff !important;
        margin-bottom: 1em !important;
    }
    
    .frc-container {
        display: flex !important;
        align-items: center !important;
        min-height: 52px !important;
    }
    
    .frc-icon {
        fill: #222;
        stroke: #222;
        flex-shrink: 0 !important;
        margin: 8px 8px 0 8px !important;
    }
    
    .frc-icon.frc-warning {
        fill: #C00 !important;
    }
    
    .frc-content {
        white-space: nowrap !important;
        display: flex !important;
        flex-direction: column;
        margin: 4px 6px 0 0 !important;
        overflow-x: auto !important;
        flex-grow: 1 !important;
    }
    
    .frc-banner {
        position: absolute !important;
        bottom: 0px;
        right: 6px !important;
        line-height: 1 !important;
    }
    
    .frc-banner * {
        font-size: 10px !important;
        opacity: 0.8 !important;
    }
    
    .frc-banner b {
        font-weight: bold !important;
    }
    
    .frc-progress {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 3px 0 !important;
        height: 4px !important;
        border: none !important;
        background-color: #eee !important;
        color: #222 !important;
        width: 100% !important;
        transition: all 0.5s linear !important;
    }
    
    .frc-progress::-webkit-progress-bar {
        background: #eee !important;
    }
    
    .frc-progress::-webkit-progress-value {
        background: #222 !important;
    }
    
    .frc-progress::-moz-progress-bar {
        background: #222 !important;
    }
    
    .frc-button {
        padding: 2px 6px !important;
        background-color: #f1f1f1 !important;
        border: 1px solid transparent !important;
        font-weight: 600 !important;
        text-align: center !important;
    }
    .frc-button:focus{
        border: 1px solid #333 !important;
    }
    .frc-button:hover{
        background-color: #ddd;
    }
    
    /* Dark theme */
    .dark.frc-captcha{
        color: #fff !important;
        background-color: #222 !important;
    }
    
    .dark.frc-captcha * {
        color: #fff !important;
    }
    
    .dark.frc-captcha button {
        background-color: #444 !important;
    }
    
    .dark .frc-icon {
        fill: #fff !important;
        stroke: #fff !important;
    }
    
    .dark .frc-progress {
        background-color: #444 !important;
    }
    
    .dark .frc-progress::-webkit-progress-bar {
        background: #444 !important;
    }
    
    .dark .frc-progress::-webkit-progress-value {
        background: #ddd !important;
    }
    
    .dark .frc-progress::-moz-progress-bar {
        background: #ddd !important;
    }
    </style>";
}

add_filter(
    'wpforms_display_submit_before',
    'frcaptcha_wpforms_add_widget',
    10,
    1
);

function frcaptcha_wpforms_add_widget($form_data)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);
    return;
}


add_action('wpforms_process', 'frcaptcha_wpforms_process', 10, 3);

function frcaptcha_wpforms_process($fields, $entry, $form_data)
{

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        wpforms()->process->errors[$form_data['id']]['header'] = esc_html__(FriendlyCaptcha_Plugin::default_error_user_message(), 'frcaptcha');
        wpforms_log(
            esc_html__('[Friendly Captcha] Spam Entry') . uniqid(),
            array("Friendly Captcha solution not present", $entry),
            array(
                'type'    => array('spam'),
                'form_id' => $form_data['id'],
            )
        );
        return;
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'wpforms');

    if (!$verification["success"]) {
        wpforms()->process->errors[$form_data['id']]['header'] = esc_html__(FriendlyCaptcha_Plugin::default_error_user_message(), 'frcaptcha');
        $captcha_error_str = $verification["error_codes"] ? $verification["error_codes"] : reset($verification["error_codes"]);
        // Not entirely sure this will work as expected..
        // We could not log to be more safe?
        wpforms_log(
            esc_html__('[Friendly Captcha] Spam Entry') . uniqid(),
            array($captcha_error_str, $solution, $entry),
            array(
                'type'    => array('spam'),
                'form_id' => $form_data['id'],
            )
        );
    }
}
