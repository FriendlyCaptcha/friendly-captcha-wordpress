<?php

if ( ! class_exists( '\ElementorPro\Modules\Forms\Fields\Field_Base' ) ) {
	die();
}

// https://developers.elementor.com/docs/form-fields/advanced-example/

class Elementor_Form_Friendlycaptcha_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base {
	/**
	 * Get field type.
	 *
	 * @return string Field type.
	 */
	public function get_type() {
		return 'frcaptcha';
	}

	/**
	 * Get field name.
	 *
	 * @return string Field name.
	 */
	public function get_name() {
		return esc_html__( 'FriendlyCaptcha', 'elementor-form-frcaptcha-field' );
	}

	/**
	 * Render field output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @param mixed $item
	 * @param mixed $item_index
	 * @param mixed $form
	 * @return void
	 */
	public function render( $item, $item_index, $form ) {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured() or !$plugin->get_elementor_active()) {
            return;
        }
        
		echo frcaptcha_generate_widget_tag_from_plugin($plugin);
        frcaptcha_enqueue_widget_scripts();
	}

	/**
	 * Field validation.
	 *
	 * @param \ElementorPro\Modules\Forms\Classes\Field_Base   $field
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 * @return void
	 */
	public function validation( $field, $record, $ajax_handler ) {
        $plugin = FriendlyCaptcha_Plugin::$instance;
        if (!$plugin->is_configured() or !$plugin->get_elementor_active()) {
            return;
        }

        $solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

		if ( empty ( $solution ) ) {
            $error_message = FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
            $ajax_handler->add_error(
				$field['id'],
				esc_html__( $error_message, 'elementor-form-frcaptcha-field' )
			);
			return;
		}

        $verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key());

        if (!$verification["success"]) {
            $error_message = FriendlyCaptcha_Plugin::default_error_user_message();
            $ajax_handler->add_error(
				$field['id'],
				esc_html__( $error_message, 'elementor-form-frcaptcha-field' )
			);
            return;
        }
	}

    public function update_controls( $widget ) {
		$elementor = \ElementorPro\Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack( $widget->get_unique_name(), 'form_fields' );

		if ( is_wp_error( $control_data ) ) {
			return;
		}

        // I haven't figured out how to remove the label yet
        $control_data = $this->remove_control_form_field_type( 'width', $control_data );

		$widget->update_control( 'form_fields', $control_data );
	}
    
    private function remove_control_form_field_type( $control_name, $control_data ) {
        foreach ( $control_data['fields'] as $index => $field ) {
            if ( $control_name !== $field['name'] ) {
                continue;
            }
            foreach ( $field['conditions']['terms'] as $condition_index => $terms ) {
                if ( ! isset( $terms['name'] ) || 'field_type' !== $terms['name'] || ! isset( $terms['operator'] ) || '!in' !== $terms['operator'] ) {
                    continue;
                }
                $control_data['fields'][ $index ]['conditions']['terms'][ $condition_index ]['value'][] = $this->get_type();
                break;
            }
            break;
        }
        return $control_data;
    }
}
