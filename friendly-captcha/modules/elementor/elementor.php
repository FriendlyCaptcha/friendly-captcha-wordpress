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

define('FRCAPTCHA_ELEMENTOR_ATOMIC_WIDGET_TYPE', 'frc-captcha');

function frcaptcha_elementor_add_atomic_widget($widgets_manager)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    // Atomic widgets only exist in recent Elementor versions
    if (
        !class_exists('\Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base') ||
        !trait_exists('\Elementor\Modules\AtomicWidgets\Elements\Base\Has_Template')
    ) {
        return;
    }

    require_once(__DIR__ . '/atomic-widget.php');
    $widgets_manager->register(new \Elementor_Friendlycaptcha_Atomic_Widget());
}

function frcaptcha_elementor_atomic_form_has_captcha($elements)
{
    foreach ($elements as $element) {
        if (($element['widgetType'] ?? '') === FRCAPTCHA_ELEMENTOR_ATOMIC_WIDGET_TYPE) {
            return true;
        }

        // Same filter Elementor uses to resolve nested elements (e.g. components)
        $inner_elements = apply_filters('elementor/utils/find_element_recursive/inner_elements', $element['elements'] ?? [], $element);
        if (!empty($inner_elements) && frcaptcha_elementor_atomic_form_has_captcha($inner_elements)) {
            return true;
        }
    }

    return false;
}

/**
 * @todo revisit when Elementor atomic forms support captcha providers natively
 */
function frcaptcha_elementor_atomic_form_spam_check($is_spam, $form_fields, $widget_settings, $post_id = 0)
{
    if ($is_spam) {
        return true;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return false;
    }

    // Elementor already validated that this form exists before running the spam check
    $form_id = \Elementor\Utils::get_super_global_value($_POST, 'form_id');
    $document = \Elementor\Plugin::$instance->documents->get($post_id);
    if (!$form_id || !$document) {
        return false;
    }

    $form_element = \Elementor\Utils::find_element_recursive($document->get_elements_data(), $form_id);
    if (empty($form_element) || !frcaptcha_elementor_atomic_form_has_captcha($form_element['elements'] ?? [])) {
        return false;
    }

    $solution = '';
    $field_name = $plugin->get_solution_field_name();
    foreach ($form_fields as $field) {
        if (is_array($field) && ($field['name'] ?? '') === $field_name && is_string($field['value'] ?? null)) {
            $solution = trim(sanitize_text_field($field['value']));
            break;
        }
    }

    if (empty($solution)) {
        return true;
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'elementor-atomic');

    return !$verification['success'];
}

add_action('elementor/init', 'frcaptcha_elementor_init');
add_action('elementor_pro/forms/fields/register', 'frcaptcha_elementor_add_form_field');
add_action('elementor/widgets/register', 'frcaptcha_elementor_add_atomic_widget');
add_filter('elementor_pro/atomic_forms/spam_check', 'frcaptcha_elementor_atomic_form_spam_check', 10, 4);

