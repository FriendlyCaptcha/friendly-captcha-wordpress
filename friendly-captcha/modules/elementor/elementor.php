<?php

// https://developers.elementor.com/docs/form-fields/add-new-field/

function frcaptcha_elementor_init()
{
    frcaptcha_enqueue_widget_scripts();

    wp_enqueue_script(
        'frcaptcha_elementor-friendly-captcha',
        plugin_dir_url(__FILE__) . 'script.js',
        array('friendly-captcha-widget-module', 'friendly-captcha-widget-fallback'),
        FriendlyCaptcha_Plugin::$version,
        true
    );
}

function frcaptcha_elementor_add_form_field($form_fields_registrar)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    require_once(__DIR__ . '/field.php');

    $form_fields_registrar->register(new \Elementor_Form_Friendlycaptcha_Field());
}

function frcaptcha_elementor_add_atomic_widget($widgets_manager): void
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    require_once(__DIR__ . "/atomic-widget.php");
    $widgets_manager->register(new \Elementor_Friendlycaptcha_Atomic_Widget());
}


add_action('elementor/init', 'frcaptcha_elementor_init');
add_action('elementor_pro/forms/fields/register', 'frcaptcha_elementor_add_form_field');
add_action('elementor/widgets/register', 'frcaptcha_elementor_add_atomic_widget');

