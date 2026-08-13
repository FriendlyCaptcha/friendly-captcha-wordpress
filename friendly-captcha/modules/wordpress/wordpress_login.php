<?php

add_action('login_form', 'frcaptcha_wp_login_show_widget', 10, 0);

function frcaptcha_wp_login_show_widget()
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

// How long a verified solution may be replayed by a second authentication request for the same user.
const FRCAPTCHA_LOGIN_REPLAY_TTL = 60;

add_filter('wp_authenticate_user', 'frcaptcha_wp_login_validate', 20, 2);

function frcaptcha_wp_login_validate($user, $password)
{
    if ($user instanceof WP_Error) {
        return $user;
    }

    // When 2FA is configured using Wordfence before 9.0.0, the filter is run twice. We want to skip the second run.
    // Wordfence 9.0.0 dropped this constant without a replacement, see frcaptcha_claim_replayed_login_solution.
    // See https://github.com/wordpress-premium/wordfence/blob/master/modules/login-security/classes/controller/wordfencels.php#L568
    $is2FA = (defined('WORDFENCE_LS_AUTHENTICATION_CHECK') && WORDFENCE_LS_AUTHENTICATION_CHECK);
    if ($is2FA) {
        return $user;
    }

    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured()) {
        return $user;
    }

    $errorPrefix = '<strong>' . __('Error', 'wp-captcha') . '</strong> : ';
    if (empty($_POST)) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (empty body)", "frcaptcha"));
    }

    $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

    if (empty($solution)) {
        return new WP_Error("frcaptcha-empty-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha"));
    }

    if (frcaptcha_claim_replayed_login_solution($solution, $user)) {
        return $user;
    }

    $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

    if (!$verification["success"]) {
        return new WP_Error("frcaptcha-solution-error", $errorPrefix . FriendlyCaptcha_Plugin::default_error_user_message());
    }

    frcaptcha_remember_login_solution($solution, $user);

    return $user;
}

/**
 * Wordfence 9.0.0 and later authenticate twice for a single login: an admin-ajax preflight
 * (action=wordfence_ls_authenticate) followed by the real wp-login.php POST. Both carry the same
 * solution, and the siteverify API rejects the second as response_duplicate.
 *
 * To stay compatible we allow a verified solution to be replayed once, by the same user, within a
 * short window. Only a hash of the solution is stored so that a SQL injection elsewhere on the site
 * cannot yield a replayable solution.
 */
function frcaptcha_login_replay_key($solution)
{
    return 'frcaptcha_login_' . hash('sha256', $solution);
}

function frcaptcha_remember_login_solution($solution, $user)
{
    if (!($user instanceof WP_User)) {
        return;
    }

    set_transient(frcaptcha_login_replay_key($solution), (int) $user->ID, FRCAPTCHA_LOGIN_REPLAY_TTL);
}

function frcaptcha_claim_replayed_login_solution($solution, $user)
{
    if (!($user instanceof WP_User)) {
        return false;
    }

    $key = frcaptcha_login_replay_key($solution);
    $rememberedUserId = get_transient($key);

    if ($rememberedUserId === false || (int) $rememberedUserId !== (int) $user->ID) {
        return false;
    }

    // A solution is replayable exactly once.
    delete_transient($key);

    return true;
}


/* Remove the captcha hook, when Ultimate Member is used, so it does not get called twice */
add_filter('um_submit_form_errors_hook_login', 'remove_filter_authenticate');

function remove_filter_authenticate($credentials)
{
    remove_filter('wp_authenticate_user', 'frcaptcha_wp_login_validate', 20);

    return $credentials;
}
