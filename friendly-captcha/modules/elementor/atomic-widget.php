<?php

if (!class_exists('\Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base')) {
    die();
}

use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Template;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Elementor_Friendlycaptcha_Atomic_Widget extends Atomic_Widget_Base {
    use Has_Template;

    public static $widget_description = 'Display a text input with customizable type, placeholder, default value, required, readonly, and attributes.';

    /**
     * @throws Exception
     */
    public function __construct( $data = [], $args = null ) {
        add_filter( 'elementor_pro/atomic_forms/spam_check', [ $this, 'is_spam' ], 10, 4 );
        parent::__construct( $data, $args );
    }

    public static function get_element_type(): string {
        return 'frc-captcha';
    }

    public function get_title(): string {
        return esc_html__('FriendlyCaptcha', 'elementor-form-frcaptcha-field');
    }

    public function get_icon(): string {
        return 'eicon-atomic-input'; // TODO: change to a more appropriate icon for FriendlyCaptcha
    }

    public function get_categories(): array {
        return [ 'atomic-form' ];
    }

    public function get_keywords(): array {
        return [ 'atomic', 'form', 'spam', 'friendly', 'captcha' ];
    }

    protected static function define_props_schema(): array {
        return [];
    }

    protected function define_atomic_controls(): array {
        return [];
    }

    /**
     * @todo revisit this and `is_spam()` when Elementor Atomic Form supports reCAPTCHA
     * @throws Exception
     */
    protected function render(): void
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return;
        }

        try {
            // Adds data-interaction-id the solution input after initialization
            // Elementor Atomic Forms sends only input fields by selector `input[data-interaction-id]`
            $interaction_id = $this->get_interaction_id();

            $solution = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();
            if(empty($solution)) throw new \Exception("FriendlyCaptcha solution is missing");

            ob_start();
            ?>
            <div data-frc-captcha-interaction-id="<?php echo esc_attr($interaction_id); ?>">
                <?php echo frcaptcha_generate_widget_tag_from_plugin($plugin); ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const interactionId = '<?php echo esc_js($interaction_id); ?>';
                        const solutionSelector = '[name="<?php echo esc_js($solution); ?>"]';
                        const container = document.querySelector(`[data-frc-captcha-interaction-id="${interactionId}"]`);

                        if (!container) {
                            return;
                        }

                        const applyInteractionId = (node) => {
                            if (!(node instanceof Element)) return;

                            if (node.matches(solutionSelector)) {
                                node.dataset.interactionId = interactionId;
                            } else {
                                node.querySelectorAll(solutionSelector).forEach((input) => {
                                    input.dataset.interactionId = interactionId;
                                });
                            }
                        };

                        applyInteractionId(container);

                        const observer = new MutationObserver((mutations) => {
                            mutations.forEach((mutation) => {
                                mutation.addedNodes.forEach((node) => {
                                    applyInteractionId(node);
                                });
                            });
                        });

                        observer.observe(container, {
                            childList: true,
                            subtree: true,
                        });
                    });
                </script>
            </div>
            <?php
            echo ob_get_clean();
        } catch ( \Exception $e ) {
            if ( Utils::is_elementor_debug() ) {
                throw $e;
            }
        }
    }

    protected function get_templates(): array {
        return [
            'frc-captcha' => __DIR__ . '/atomic-widget.html.twig',
        ];
    }

    public function is_spam( bool $is_spam, array $form_fields, array $widget_settings, int $post_id = 0 ): bool {
        if ( $is_spam ) {
            return true;
        }

        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return false;
        }

        $solution = null;
        $fieldName = FriendlyCaptcha_Plugin::$instance->get_solution_field_name();
        foreach ($form_fields as $key => $field) {
            if ($field['name'] === $fieldName) {
                $solution = $field['value'];
                break;
            }
        }
        if (empty($solution)) return true;

        $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'elementor');

        return !$verification["success"];
    }
}
