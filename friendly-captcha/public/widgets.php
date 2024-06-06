<?php

function frcaptcha_enqueue_widget_scripts($forceMutationObserver = false)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;

    if (!$plugin->is_configured()) {
        return;
    }

    if (FriendlyCaptcha_Plugin::$instance->get_enable_v2()) {
        frcaptcha_v2_enqueue_widget_scripts();
    } else {
        frcaptcha_v1_enqueue_widget_scripts();
    }

    frcaptcha_mutation_observer_scripts($plugin, $forceMutationObserver);
}

function frcaptcha_v1_enqueue_widget_scripts()
{
    $version = FriendlyCaptcha_Plugin::$friendly_challenge_version;

    /* Modern browsers will load this smaller bundle */
    wp_enqueue_script(
        'friendly-captcha-widget-module',
        plugin_dir_url(__FILE__) . 'vendor/v1/widget.module.min.js',
        array(),
        $version,
        true
    );

    /* Fallback for (very) old browsers */
    wp_enqueue_script(
        'friendly-captcha-widget-fallback',
        plugin_dir_url(__FILE__) . 'vendor/v1/widget.min.js',
        array(),
        $version,
        true
    );
}

function frcaptcha_v2_enqueue_widget_scripts()
{
    $version = FriendlyCaptcha_Plugin::$friendly_captcha_sdk_version;

    /* Modern browsers will load this smaller bundle */
    wp_enqueue_script(
        'friendly-captcha-widget-module',
        plugin_dir_url(__FILE__) . 'vendor/v2/site.min.js',
        array(),
        $version,
        true
    );

    /* Fallback for (very) old browsers */
    wp_enqueue_script(
        'friendly-captcha-widget-fallback',
        plugin_dir_url(__FILE__) . 'vendor/v2/site.compat.min.js',
        array(),
        $version,
        true
    );
}

function frcaptcha_mutation_observer_scripts($plugin, $forceMutationObserver)
{
    $version = FriendlyCaptcha_Plugin::$version;

    if ($forceMutationObserver || $plugin->get_enable_mutation_observer()) {
        wp_enqueue_script(
            'friendly-captcha-mutation-observer',
            plugin_dir_url(__FILE__) . 'mutation-observer.js',
            array(),
            $version,
            true
        );
    }
}

/**
 * Useful if for some reason wp_enqueue_script doesn't work (as seems to be the case with WPForms?!)
 */
function frcaptcha_echo_script_tags()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;

    if (!$plugin->is_configured()) {
        return;
    }

    if ($plugin->get_enable_v2()) {
        return frcaptcha_v2_echo_script_tags();
    } else {
        return frcaptcha_v1_echo_script_tags();
    }
}

function frcaptcha_v1_echo_script_tags()
{
    $version = FriendlyCaptcha_Plugin::$friendly_challenge_version;

    echo '<script async defer type="module" src="' . plugin_dir_url(__FILE__) . 'vendor/v1/widget.module.min.js?ver=' . $version . '"></script>';
    echo '<script async defer nomodule src="' . plugin_dir_url(__FILE__) . 'vendor/v1/widget.min.js?ver=' . $version . '"></script>';
}


function frcaptcha_v2_echo_script_tags()
{
    $version = FriendlyCaptcha_Plugin::$friendly_captcha_sdk_version;

    echo '<script async defer type="module" src="' . plugin_dir_url(__FILE__) . 'vendor/v2/site.min.js?ver=' . $version . '"></script>';
    echo '<script async defer nomodule src="' . plugin_dir_url(__FILE__) . 'vendor/v2/site.compat.min.js?ver=' . $version . '"></script>';
}

add_filter('script_loader_tag', 'frcaptcha_transform_friendly_captcha_script_tags', 10, 3);

function frcaptcha_transform_friendly_captcha_script_tags($tag, $handle, $src)
{
    if ('friendly-captcha-widget-module' == $handle) {
        return str_replace('<script', '<script async defer type="module"', $tag);
    }
    if ('friendly-captcha-widget-fallback' == $handle) {
        return str_replace('<script', '<script async defer nomodule', $tag);
    }
    if ('friendly-captcha-mutation-observer' == $handle) {
        return str_replace('<script', '<script async defer', $tag);
    }

    return $tag;
}

function frcaptcha_generate_widget_tag_from_plugin($plugin)
{
    if (!$plugin->is_configured()) {
        return "";
    }

    if ($plugin->get_enable_v2()) {
        return frcaptcha_v2_generate_widget_tag_from_plugin($plugin);
    } else {
        return frcaptcha_v1_generate_widget_tag_from_plugin($plugin);
    }
}

function frcaptcha_v1_generate_widget_tag_from_plugin($plugin)
{
    $sitekey = $plugin->get_sitekey();
    $lang = $plugin->get_widget_language();

    $extra_attributes = "";
    $global = $plugin->get_global_puzzle_endpoint_active();
    $eu = $plugin->get_eu_puzzle_endpoint_active();

    if ($global && $eu) {
        $extra_attributes = "data-puzzle-endpoint=\"https://eu-api.friendlycaptcha.eu/api/v1/puzzle,https://api.friendlycaptcha.com/api/v1/puzzle\"";
    } else if ($eu) {
        $extra_attributes = "data-puzzle-endpoint=\"https://eu-api.friendlycaptcha.eu/api/v1/puzzle\"";
    }

    $theme = $plugin->get_widget_dark_theme_active() ? "dark" : "";

    return sprintf(
        '%s%s',
        frcaptcha_generate_skip_style_injection_tag($plugin),
        frcaptcha_v1_generate_widget_tag($sitekey, $lang, $extra_attributes, $theme)
    );
}

function frcaptcha_v2_generate_widget_tag_from_plugin($plugin)
{
    $sitekey = $plugin->get_sitekey();

    $extra_attributes = "";
    $eu = $plugin->get_eu_puzzle_endpoint_active();

    if ($eu) {
        $extra_attributes = "data-api-endpoint=\"eu\"";
    }

    $theme = $plugin->get_widget_dark_theme_active() ? "dark" : "light";

    return sprintf(
        '%s%s',
        frcaptcha_generate_skip_style_injection_tag($plugin),
        frcaptcha_v2_generate_widget_tag($sitekey, $extra_attributes, $theme)
    );
}

function frcaptcha_v1_generate_widget_tag($sitekey, $language, $extra_attributes = "", $theme = "")
{
    return sprintf(
        '<div class="frc-captcha %s" data-sitekey="%s" data-lang="%s" %s></div>
		<noscript>You need to enable Javascript for the anti-spam check.</noscript>',
        esc_html($theme),
        esc_html($sitekey),
        esc_html($language),
        $extra_attributes
    );
}

function frcaptcha_v2_generate_widget_tag($sitekey, $extra_attributes = "", $theme = "light")
{
    return sprintf(
        '<div class="frc-captcha" data-sitekey="%s" data-theme="%s" %s></div>
		<noscript>You need to enable Javascript for the anti-spam check.</noscript>',
        esc_html($sitekey),
        esc_html($theme),
        $extra_attributes
    );
}

$frcaptcha_skip_style_injection_tag_injected = false;

function frcaptcha_generate_skip_style_injection_tag($plugin)
{
    global $frcaptcha_skip_style_injection_tag_injected;

    if (!$plugin->get_skip_style_injection()) {
        return '';
    }

    if ($frcaptcha_skip_style_injection_tag_injected) {
        // we only want to inject the element once
        return '';
    }

    $frcaptcha_skip_style_injection_tag_injected = true;
    return '<div id="frc-style"></div>';
}
