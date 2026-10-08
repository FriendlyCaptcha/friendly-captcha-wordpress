<?php

use Elementor\Modules\AtomicWidgets\Elements\Base\Atomic_Widget_Base;
use Elementor\Modules\AtomicWidgets\Elements\Base\Has_Template;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Verification happens in frcaptcha_elementor_atomic_form_spam_check()
class Elementor_Friendlycaptcha_Atomic_Widget extends Atomic_Widget_Base {
    use Has_Template;

    public static $widget_description = 'Protect the form from spam with Friendly Captcha.';

    public static function get_element_type(): string {
        return FRCAPTCHA_ELEMENTOR_ATOMIC_WIDGET_TYPE;
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
     * @todo revisit this and the spam check when Elementor Atomic Form supports reCAPTCHA
     */
    protected function render(): void
    {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured()) {
            return;
        }

        // Elementor Atomic Forms only submit inputs matching `input[data-interaction-id]`,
        // so we add it to the solution input once the widget creates it
        $interaction_id = $this->get_interaction_id();
        $solution = $plugin->get_solution_field_name();
        ?>
        <div data-frc-captcha-interaction-id="<?php echo esc_attr($interaction_id); ?>">
            <?php echo frcaptcha_generate_widget_tag_from_plugin($plugin); ?>
            <script>
                (function() {
                    const interactionId = '<?php echo esc_js($interaction_id); ?>';
                    const solutionSelector = '[name="<?php echo esc_js($solution); ?>"]';

                    const init = function() {
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
                    };

                    // The widget may be rendered after page load, e.g. in popups
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', init);
                    } else {
                        init();
                    }
                })();
            </script>
        </div>
        <?php
    }

    protected function get_templates(): array {
        return [
            FRCAPTCHA_ELEMENTOR_ATOMIC_WIDGET_TYPE => __DIR__ . '/atomic-widget.html.twig',
        ];
    }
}
