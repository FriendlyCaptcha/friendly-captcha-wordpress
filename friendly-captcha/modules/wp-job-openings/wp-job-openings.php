<?php
/**
 * Friendly Captcha Integration for WP Job Openings
 */

// Display Friendly Captcha widget in the form
add_action('awsm_application_form_field_init', 'frcaptcha_wpjo_show_widget', 20, 1);
function frcaptcha_wpjo_show_widget($form_attrs)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    // Load Friendly Captcha scripts
    frcaptcha_enqueue_widget_scripts();

    // Output widget HTML
    echo frcaptcha_generate_widget_tag_from_plugin($plugin);

    // Optional styling
    echo '<style>.frc-captcha{max-width:100%;margin-bottom:1em;}</style>';
}

// Disable reCAPTCHA when Friendly Captcha is configured
add_filter('awsm_application_form_is_recaptcha_visible', 'frcaptcha_wpjo_disable_recaptcha');
function frcaptcha_wpjo_disable_recaptcha($visible)
{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if ($plugin->is_configured()) {
        return false;
    }
    return $visible;
}

// Validate Friendly Captcha on form submission
add_action('awsm_job_application_submitting', 'frcaptcha_wpjo_validate', 5);
function frcaptcha_wpjo_validate()
{
    global $awsm_response;

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return;
    }

    // Get the solution from POST data
    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        $awsm_response['error'][] = FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
        return;
    }

    // Handle widget states (same as Contact Form 7 integration)
    if ('.UNSTARTED' === $solution) {
        $awsm_response['error'][] = __('FriendlyCaptcha widget was not started yet', 'frcaptcha');
        return;
    } elseif ('.FETCHING' === $solution) {
        $awsm_response['error'][] = __('FriendlyCaptcha widget was still fetching a puzzle', 'frcaptcha');
        return;
    } elseif ('.UNFINISHED' === $solution) {
        $awsm_response['error'][] = __('FriendlyCaptcha widget was not done solving yet', 'frcaptcha');
        return;
    } elseif ('.ERROR' === $solution) {
        $awsm_response['error'][] = __('FriendlyCaptcha widget had an (internal) error', 'frcaptcha');
        return;
    }

    // Verify the solution
    $verification = frcaptcha_verify_captcha_solution(
        $solution,
        $plugin->get_sitekey(),
        $plugin->get_api_key(),
        'wp-job-openings'
    );

    if (!$verification["success"]) {
        $error_message = FriendlyCaptcha_Plugin::default_error_user_message();
        if (!empty($verification["error_codes"])) {
            $error_message .= sprintf(
                __(' (Problem: %s)', 'frcaptcha'),
                reset($verification["error_codes"])
            );
        }
        $awsm_response['error'][] = $error_message;
    }
}