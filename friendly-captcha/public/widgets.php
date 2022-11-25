<?php

function frcaptcha_enqueue_widget_scripts() {
    $plugin = FriendlyCaptcha_Plugin::$instance;

    if ( !$plugin->is_configured() ) {
        return;
    }

    $version = FriendlyCaptcha_Plugin::$friendly_challenge_version;

    /* Modern browsers will load this smaller bundle */
    wp_enqueue_script( 'friendly-captcha-widget-module',
        plugin_dir_url( __FILE__ ) . 'vendor/widget.module.min.js',
        array(),
        $version,
        true
    );

    /* Fallback for (very) old browsers */
    wp_enqueue_script( 'friendly-captcha-widget-fallback',
        plugin_dir_url( __FILE__ ) . 'vendor/widget.polyfilled.min.js',
        array(),
        $version,
        true
    );
}

/**
 * Useful if for some reason wp_enqueue_script doesn't work (as seems to be the case with WPForms?!)
 */
function frcaptcha_echo_script_tags() {
    $plugin = FriendlyCaptcha_Plugin::$instance;

    if ( !$plugin->is_configured() ) {
        return;
    }

    $version = FriendlyCaptcha_Plugin::$friendly_challenge_version;

    echo '<script async defer type="module" src="'. plugin_dir_url( __FILE__ ) . 'vendor/widget.module.min.js?ver=' . $version . '"></script>';
    echo '<script async defer nomodule src="'. plugin_dir_url( __FILE__ ) . 'vendor/widget.polyfilled.min.js?ver=' . $version . '"></script>';
}

add_filter( 'script_loader_tag', 'frcaptcha_transform_friendly_captcha_script_tags', 10, 3 );

function frcaptcha_transform_friendly_captcha_script_tags( $tag, $handle, $src )
{
	if ( 'friendly-captcha-widget-module' == $handle) {
		return str_replace( '<script', '<script async defer type="module"', $tag );
	}
    if ( 'friendly-captcha-widget-fallback' == $handle) {
        return str_replace( '<script', '<script async defer nomodule', $tag );
	}
	
	return $tag;
}

function frcaptcha_generate_widget_tag_from_plugin($plugin) {
	if (!$plugin->is_configured()) {
		return "";
	}

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
        frcaptcha_generate_widget_tag($sitekey, $lang, $extra_attributes, $theme)
    );
}

function frcaptcha_generate_widget_tag($sitekey, $language, $extra_attributes = "", $theme = "") {
	return sprintf(
        '<div class="frc-captcha %s" data-sitekey="%s" data-lang="%s" %s></div>
		<noscript>You need to enable Javascript for the anti-spam check.</noscript>',
	esc_html($theme),
    esc_html($sitekey),
    esc_html($language),
    $extra_attributes);
}

$frcaptcha_skip_style_injection_tag_injected = false;

function frcaptcha_generate_skip_style_injection_tag($plugin) {
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

function frcaptcha_generate_extra_widget_attributes($plugin) {
}
