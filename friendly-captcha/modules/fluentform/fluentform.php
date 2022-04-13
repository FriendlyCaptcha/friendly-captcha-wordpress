<?php

add_action( 'fluentform_render_item_submit_button', 'frcaptcha_fluentform_show_widget', 10, 0 );

function frcaptcha_fluentform_show_widget() {
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_fluentform_active()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // it just slightly overflows..
    echo "<style>.frc-captcha {max-width:100%; margin-bottom: 1em}</style>";

    frcaptcha_enqueue_widget_scripts();
}

add_filter( 'fluentform_before_insert_submission', 'frcaptcha_fluentform_validate', 20, 3 );	

function frcaptcha_fluentform_validate($insert_data, $data, $form) {

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_fluentform_active()) {
        return;
    }

	$solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if ( empty( $solution ) ) {
        $error_message = FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
        wp_send_json(
            [
                'errors' => [
                    'g-recaptcha-response' => [ $error_message ],
                ],
            ],
            422
        );
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        $error_message = FriendlyCaptcha_Plugin::default_error_user_message();
        wp_send_json(
            [
                'errors' => [
                    'g-recaptcha-response' => [ $error_message ],
                ],
            ],
            422
        );
    }
}
