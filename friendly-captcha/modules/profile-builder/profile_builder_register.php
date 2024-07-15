<?php

add_filter('wppb_after_form_fields', 'frcaptcha_pb_register_show_widget', 10, 0);

function frcaptcha_pb_register_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    frcaptcha_enqueue_widget_scripts();

    $widget = frcaptcha_generate_widget_tag_from_plugin($plugin);
    return $widget;
}

add_action('wppb_output_field_errors_filter', 'frcaptcha_pb_register_validate', 10, 4);

function frcaptcha_pb_register_validate($output_field_errors, $form_fields, $global_request, $form_type)
{
    if ($form_type != 'register') {
        return $output_field_errors;
    }
    $plugin = FriendlyCaptcha_Plugin::$instance;
    $error_message = '';
    if (!$plugin->is_configured()) {
        return $output_field_errors;
    }
    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();
    //    We need to use a field id in the array. Because we don't have such id we just use a high number that will never be used by the plugin itself.
    if (empty($solution)) {
        $output_field_errors[100] = '<span class="wppb-form-error">' . FriendlyCaptcha_Plugin::default_error_user_message() .  __(' (captcha missing)', 'frcaptcha') . '</span>';
        return;
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification['success']) {
        $output_field_errors[100] = '<span class="wppb-form-error">' . FriendlyCaptcha_Plugin::default_error_user_message()  . '</span>';
    }
    return $output_field_errors;
}
