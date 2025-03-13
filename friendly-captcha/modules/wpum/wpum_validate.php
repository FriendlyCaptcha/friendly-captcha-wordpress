<?php

// based on code from WPUM Recaptcha Pro Addon (includes/class-wpum-recaptcha-actions.php)
// Copyright (c) 2018, Alessandro Tesoro; GNU General Public License, version 2

add_filter('submit_wpum_form_validate_fields', 'frcaptcha_wpum_validate', 10, 5);

/**
 * Hook into the forms validation system for the login and registration form
 * and then validate the recaptcha field.
 *
 * @param bool       $pass
 * @param array      $fields
 * @param array      $values
 * @param string     $form_name
 * @param WPUM_Form  $form
 *
 * @return bool|WP_Error
 */
function frcaptcha_wpum_validate($pass, $fields, $values, $form_name, $form)
{
    $process = false;

    $plugin = FriendlyCaptcha_Plugin::$instance;

    if ($plugin->is_configured()) {
        switch ($form_name) {
            case 'registration':
            case 'registration-multi':
                $process = $plugin->get_integration_active("wpum_registration");
                break;
            case 'login':
                $process = $plugin->get_integration_active("wpum_login");
                break;
            case 'password-recovery':
                $process = $plugin->get_integration_active("wpum_password_recovery");
                break;
        }
    }

    if (!$process) {
        return $pass;
    }

    $errorPrefix = '<strong>' . __('Error', 'frcaptcha') . '</strong>: ';
    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        $error_message = $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(' (captcha missing)', 'frcaptcha');
        return new WP_Error("frcaptcha-empty-error", $error_message);
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'wpum');

    if (!$verification['success']) {
        $error_message = $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message();
        return new WP_Error("frcaptcha-solution-error", $error_message);
    }

    return $pass;
}
