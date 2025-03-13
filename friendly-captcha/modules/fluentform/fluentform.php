<?php

// See https://github.com/fluentform/fluentform/blob/5.1.14/app/Services/FormBuilder/FormBuilder.php#L274-L298
add_action('fluentform_render_item_submit_button', 'frcaptcha_fluentform_show_widget', 10, 0);
add_action('fluentform_render_item_step_end', 'frcaptcha_fluentform_show_widget', 10, 0);

function frcaptcha_fluentform_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // it just slightly overflows..
    echo "<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>";

    frcaptcha_enqueue_widget_scripts();
}

add_filter('fluentform_before_insert_submission', 'frcaptcha_fluentform_validate', 20, 3);

function frcaptcha_fluentform_validate($insert_data, $data, $form)
{

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    $fieldName = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();
    $solution = $data[$fieldName];

    if (empty($solution)) {
        $error_message = FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
        wp_send_json(
            [
                'errors' => [
                    'g-recaptcha-response' => [$error_message],
                ],
            ],
            422
        );
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'fluentform');

    if (!$verification["success"]) {
        $error_message = FriendlyCaptcha_Plugin::default_error_user_message();
        wp_send_json(
            [
                'errors' => [
                    'g-recaptcha-response' => [$error_message],
                ],
            ],
            422
        );
    }
}
