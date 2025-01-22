<?php

// A global variable is used to track the captcha verification
// error state so it can be used in multiple plugin hooks.
$frcaptcha_pb_register_general_top_error_message_val = null;

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
    if (!$plugin->is_configured()) {
        return $output_field_errors;
    }

    // Reset the global error message state before each verification.
    global $frcaptcha_pb_register_general_top_error_message_val;
    $frcaptcha_pb_register_general_top_error_message_val = null;

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();
    if (empty($solution)) {
        $frcaptcha_pb_register_general_top_error_message_val = FriendlyCaptcha_Plugin::default_error_user_message() .  __(' (captcha missing)', 'frcaptcha');
        $output_field_errors = (array) $output_field_errors;
        $output_field_errors[] = $frcaptcha_pb_register_general_top_error_message_val;
        return $output_field_errors;
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());
    if (!$verification['success']) {
        $frcaptcha_pb_register_general_top_error_message_val = FriendlyCaptcha_Plugin::default_error_user_message();
        $output_field_errors = (array) $output_field_errors;
        $output_field_errors[] = $frcaptcha_pb_register_general_top_error_message_val;
        return $output_field_errors;
    }

    return $output_field_errors;
}

add_filter('wppb_general_top_error_message', 'frcaptcha_pb_register_general_top_error_message', 10, 1);

function frcaptcha_pb_register_general_top_error_message($top_error_message)
{
    global $frcaptcha_pb_register_general_top_error_message_val;
    if (!$frcaptcha_pb_register_general_top_error_message_val) {
        return $top_error_message;
    }

    return $top_error_message . '<p class="wppb-error">' . $frcaptcha_pb_register_general_top_error_message_val . '</p>';
}
