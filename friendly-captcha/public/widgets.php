<?php

function frcaptcha_enqueue_widget_scripts() {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if ( !$plugin->is_configured() ) {
        return;
    }

    /* Modern browsers will load this smaller bundle */
    wp_enqueue_script( 'friendly-captcha-widget-module',
        plugin_dir_url( __FILE__ ) . '/vendor/widget.module.min.js',
        array(),
        '0.6.1',
        true
    );

    /* Fallback for (very) old browsers */
    wp_enqueue_script( 'friendly-captcha-widget-fallback',
        plugin_dir_url( __FILE__ ) . '/vendor/widget.polyfilled.min.js',
        array(),
        '0.6.1',
        true
    );
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

function frcaptcha_generate_widget_tag($sitekey, $theme = "") {
	return sprintf(
        '<div class="frc-captcha %s" data-sitekey="%s"></div>
		<noscript>You need to enable Javascript for the anti-spam check.</noscript>',
	$theme,
    $sitekey);
}

