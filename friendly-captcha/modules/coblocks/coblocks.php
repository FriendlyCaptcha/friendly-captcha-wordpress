<?php

// Implementation inspired by https://github.com/hCaptcha/hcaptcha-wordpress-plugin/blob/master/src/php/CoBlocks/Form.php

add_filter('render_block', array('Frcaptcha_Coblocks', 'render_block'), 10, 3);
add_filter('render_block_data', array('Frcaptcha_Coblocks', 'render_block_data'), 10, 3);

class Frcaptcha_Coblocks
{
    private const FRIENDLY_CAPTCHA_DUMMY_TOKEN = 'friendlycaptcha_token';

    /**
     * Add Friendly Captcha to CoBlocks form.
     *
     * @param string|mixed $block_content The block content.
     * @param array        $block         The full block, including name and attributes.
     * @param WP_Block     $instance      The block instance.
     *
     * @return string
     * @noinspection PhpUnusedParameterInspection
     */
    public static function render_block($block_content, array $block, WP_Block $instance): string
    {
        $block_content = (string) $block_content;
        if ('coblocks/form' !== $block['blockName']) {
            return $block_content;
        }

        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return $block_content;
        }


        frcaptcha_enqueue_widget_scripts();

        $elements = frcaptcha_generate_widget_tag_from_plugin($plugin);
        return str_replace('<button type="submit"', $elements . '<button type="submit"', $block_content);
    }

    /**
     * Render block context filter.
     * CoBlocks has no filters in form processing. So, we need to do some tricks.
     *
     * @since WP 5.1.0
     *
     * @param array|mixed $parsed_block The block being rendered.
     * @param array       $source_block An unmodified copy of $parsed_block, as it appeared in the source content.
     *
     * @return array
     * @noinspection PhpUnusedParameterInspection
     */
    public static function render_block_data($parsed_block, array $source_block): array
    {
        static $filters_added;
        if ($filters_added) {
            return $parsed_block;
        }

        $parsed_block = (array) $parsed_block;
        $block_name = $parsed_block['blockName'] ?? '';
        if ('coblocks/form' !== $block_name) {
            return $parsed_block;
        }

        $form_submission = isset($_POST['action']) ? sanitize_text_field(wp_unslash($_POST['action'])) : '';
        if ('coblocks-form-submit' !== $form_submission) {
            return $parsed_block;
        }

        // We cannot add filters right here.
        // In this case, the calculation of form hash in the coblocks_render_coblocks_form_block() will fail.
        add_action('coblocks_before_form_submit', ['Frcaptcha_Coblocks', 'before_form_submit'], 10, 2);

        $filters_added = true;

        return $parsed_block;
    }

    public static function before_form_submit(array $post, array $atts): void
    {
        add_filter('pre_option_coblocks_google_recaptcha_site_key', '__return_true');
        add_filter('pre_option_coblocks_google_recaptcha_secret_key', '__return_true');

        $_POST['g-recaptcha-token'] = self::FRIENDLY_CAPTCHA_DUMMY_TOKEN;

        add_filter('pre_http_request', ['Frcaptcha_Coblocks', 'verify'], 10, 3);
    }

    public static function verify($response, array $parsed_args, string $url)
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return;
        }

        if (
            CoBlocks_Form::GCAPTCHA_VERIFY_URL !== $url ||
            self::FRIENDLY_CAPTCHA_DUMMY_TOKEN !== $parsed_args['body']['response']
        ) {
            return $response;
        }

        remove_filter('pre_http_request', ['Frcaptcha_Coblocks', 'verify']);

        $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();
        if (empty($solution)) {
            return [
                'body'     => '{"success":false}',
                'response' =>
                [
                    'code'    => 200,
                    'message' => 'OK',
                ],
            ];
        }

        $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());
        if (!$verification["success"]) {
            return [
                'body'     => '{"success":false}',
                'response' =>
                [
                    'code'    => 200,
                    'message' => 'OK',
                ],
            ];
        }

        $fieldName = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();
        unset($_POST[$fieldName]); // suppress the solution in email message

        return [
            'body'     => '{"success":true}',
            'response' =>
            [
                'code'    => 200,
                'message' => 'OK',
            ],
        ];
    }
}
