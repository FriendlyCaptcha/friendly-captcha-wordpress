<?php

add_action('fusion_element_button_content', 'add_friendly_captcha', 10, 2);
add_filter('fusion_form_demo_mode', 'verify_friendly_captcha');

function add_friendly_captcha($html, $args)
{
    if (false === strpos($html, '<button type="submit"')) {
        return $html;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;

    if (!$plugin->is_configured()) {
        return $html;
    }

    frcaptcha_enqueue_widget_scripts();
    $widget = frcaptcha_generate_widget_tag_from_plugin($plugin);

    return $widget . $html;
}

function verify_friendly_captcha($demo_mode)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;

    if (!$plugin->is_configured()) {
        return $demo_mode;
    }

    // This is a modified version of frcaptcha_get_sanitized_frcaptcha_solution_from_post which looks for the solution in the formData key
    $form_data = $_POST['formData'];
    $form_data = wp_parse_args(str_replace('&amp;', '&', $form_data));

    $field_name = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();
    $post_value = $form_data[$field_name];
    $solution = isset($post_value) ? trim(sanitize_text_field($post_value)) : '';

    if (empty($solution)) {
        $message = FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
        die(wp_json_encode(['status' => 'error', 'info' => ['friendly_captcha' => $message]]));
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        $message = FriendlyCaptcha_Plugin::default_error_user_message();
        die(wp_json_encode(['status' => 'error', 'info' => ['friendly_captcha' => $message]]));
    }

    return $demo_mode;
}
