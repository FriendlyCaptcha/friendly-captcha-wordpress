<?php

add_action('comment_form_after_fields', 'frcaptcha_wp_comments_show_widget', 10, 0);

function frcaptcha_wp_comments_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // For some reason there is no spacing by default otherwise
    echo "<style>.frc-captcha {margin-bottom: 1em}</style>";
    frcaptcha_enqueue_widget_scripts();
}

add_action('comment_form_logged_in_after', 'frcaptcha_wp_comments_logged_in_show_widget', 10, 0);

function frcaptcha_wp_comments_logged_in_show_widget()
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_integration_active("wp_comments_logged_in")) {
        return;
    }

    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // For some reason there is no spacing by default otherwise
    echo "<style>.frc-captcha {margin-bottom: 1em}</style>";
    frcaptcha_enqueue_widget_scripts();
}

add_filter('preprocess_comment', 'frcaptcha_wp_comments_validate', 10, 1);

function frcaptcha_wp_comments_validate($comment)
{

    // Skip captcha for trackback or pingback
    if ($comment['comment_type'] != '' && $comment['comment_type'] != 'comment') {
        return $comment;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or (!$plugin->get_integration_active("wp_comments") && !$plugin->get_integration_active("wp_comments_logged_in"))) {
        return $comment;
    }

    $errorPrefix = '<strong>' . __('Error', 'wp-captcha') . '</strong> : ';
    if (empty($_POST)) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (empty body)", "frcaptcha"));
    }

    // Guest user
    if ($comment["user_id"] == 0 && !$plugin->get_integration_active("wp_comments")) {
        return $comment;
    }
    // Non-Guest user
    else if ($comment["user_id"] != 0 && !$plugin->get_integration_active("wp_comments_logged_in")) {
        return $comment;
    }

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        wp_die($errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha"));
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());
    if (!$verification["success"]) {
        wp_die($errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message());
    }

    return $comment;
}
