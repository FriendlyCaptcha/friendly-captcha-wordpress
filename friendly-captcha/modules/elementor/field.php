<?php

if (!class_exists('\ElementorPro\Modules\Forms\Fields\Field_Base')) {
	die();
}

// https://developers.elementor.com/docs/form-fields/advanced-example/

class Elementor_Form_Friendlycaptcha_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base
{
	/**
	 * Get field type.
	 *
	 * @return string Field type.
	 */
	public function get_type()
	{
		return 'frcaptcha';
	}

	/**
	 * Get field name.
	 *
	 * @return string Field name.
	 */
	public function get_name()
	{
		return esc_html__('FriendlyCaptcha', 'elementor-form-frcaptcha-field');
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
	public function render($item, $item_index, $form)
	{
		$plugin = FriendlyCaptcha_Plugin::$instance;
		if (!$plugin->is_configured()) {
			return;
		}

		echo frcaptcha_generate_widget_tag_from_plugin($plugin);

		echo "<style>.frc-captcha {max-width: 100%; width:100%}</style>";

		// We render a hidden field so Elementor knows where to display errors
		$form->add_render_attribute(
			'input' . $item_index,
			[
				'type'        => 'text',
				'style'       => 'display: none',
			]
		);
		echo '<input ' . $form->get_render_attribute_string('input' . $item_index) . '>';
	}

	/**
	 * Field validation.
	 *
	 * @param \ElementorPro\Modules\Forms\Classes\Field_Base   $field
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 * @return void
	 */
	public function validation($field, $record, $ajax_handler)
	{
		$plugin = FriendlyCaptcha_Plugin::$instance;
		if (!$plugin->is_configured()) {
			return;
		}

		$solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();

		if (empty($solution)) {
			$error_message = FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
			$ajax_handler->add_error(
				$field['id'],
				esc_html__($error_message, 'elementor-form-frcaptcha-field')
			);
			return;
		}

		$verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'elementor');

		if (!$verification["success"]) {
			$error_message = FriendlyCaptcha_Plugin::default_error_user_message();
			$ajax_handler->add_error(
				$field['id'],
				esc_html__($error_message, 'elementor-form-frcaptcha-field')
			);
			return;
		}
	}

	public function update_controls($widget)
	{
		$elementor = \ElementorPro\Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

		if (is_wp_error($control_data)) {
			return;
		}

		$control_data = $this->remove_control_form_field_type('required', $control_data); // The captcha is always required

		$widget->update_control('form_fields', $control_data);
	}

	private function remove_control_form_field_type($control_name, $control_data)
	{
		foreach ($control_data['fields'] as $index => $field) {
			if ($control_name !== $field['name']) {
				continue;
			}
			foreach ($field['conditions']['terms'] as $condition_index => $terms) {
				if (!isset($terms['name']) || 'field_type' !== $terms['name'] || !isset($terms['operator']) || '!in' !== $terms['operator']) {
					continue;
				}
				$control_data['fields'][$index]['conditions']['terms'][$condition_index]['value'][] = $this->get_type();
				break;
			}
			break;
		}
		return $control_data;
	}

	public function __construct()
	{
		parent::__construct();
		add_action('elementor/preview/init', [$this, 'editor_preview_footer']);
	}

	public function editor_preview_footer()
	{
		add_action('wp_footer', [$this, 'content_template_script']);
	}

	public function content_template_script()
	{
?>
		<script>
			jQuery(document).ready(() => {

				elementor.hooks.addFilter(
					'elementor_pro/forms/content_template/field/<?php echo $this->get_type(); ?>',
					function(inputField, item, i) {
						const fieldId = `form_field_${i}`;

						// We render a placeholder instead of the real widget here
						// The real widget messed with the Elementor editor
						return `<div id="${fieldId}" style="
                        position: relative;
	                    width: 100%;
                        text-align: center;
	                    border: 1px solid #f4f4f4;
	                    padding-bottom: 20px;
                        padding-top: 20px;
	                    background-color: #fff;">Anti-Robot Verification</div>`;
					}, 10, 3
				);

			});
		</script>
<?php
	}
}
