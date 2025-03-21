<?php
if (!class_exists('GFForms')) {
	die();
}

class GFForms_Friendlycaptcha_Field extends GF_Field
{

	/**
	 * @var string $type The field type.
	 */

	public $type = 'frcaptcha';

	/**
	 * Return the field title, for use in the form editor.
	 *
	 * @return string
	 */
	public function get_form_editor_field_title()
	{
		// Note: I removed the space as it barely fits otherwise..
		return esc_attr__('FriendlyCaptcha', 'frcaptcha');
	}

	/**
	 * Assign the field button to the Advanced Fields group.
	 *
	 * @return array
	 */

	public function get_form_editor_button()
	{
		return array(
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		);
	}

	/**
	 * The settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */

	function get_form_editor_field_settings()
	{
		return array(
			'label_setting',
			// 'description_setting',
			// 'css_class_setting',
		);
	}

	/**
	 * Sets icon for field editor
	 */
	public function get_form_editor_field_icon()
	{
		return "dashicons-shield-alt";
	}

	/**
	 * Enable this field for use with conditional logic.
	 *
	 * @return bool
	 */

	public function is_conditional_logic_supported()
	{
		return true;
	}

	/**
	 * Define the fields inner markup.
	 *
	 * @param array $form The Form Object currently being processed.
	 * @param string|array $value The field value. From default/dynamic population, $_POST, or a resumed incomplete submission.
	 * @param null|array $entry Null or the Entry Object currently being edited.
	 *
	 * @return string
	 */

	public function get_field_input($form, $value = '', $entry = null)
	{
		$plugin = FriendlyCaptcha_Plugin::$instance;
		if (!$plugin->is_configured()) {
			return;
		}

		$is_form_editor  = $this->is_form_editor();

		frcaptcha_enqueue_widget_scripts(true);

		// Replace all inline scripts to footer
		add_filter('gform_init_scripts_footer', '__return_true');

		$widget_html = frcaptcha_generate_widget_tag_from_plugin($plugin);

		if ($is_form_editor) {
			$widget_html = '<div style="border: 1px solid gray;width: 320px;padding: 1em;line-height: 1.3;border-radius:5px;"><span class="dashicons-before dashicons-shield-alt"></span>' . __("A Friendly Captcha widget will be displayed here.", "frcaptcha") . '</div>';
		}

		return sprintf("<div class='ginput_container ginput_container_%s'>%s</div>", $this->type, $widget_html);
	}

	/**
	 * Returns true if this captcha field is on the last page of the given form.
	 *
	 * @param array $form The form data.
	 * @return bool
	 */
	private function is_on_last_page($form)
	{
		$pages = GFAPI::get_fields_by_type($form, array('page'));
		return count($pages) + 1 === (int) $this->pageNumber;
	}

	public function validate($value, $form)
	{
		if (GFFormDisplay::is_last_page($form) && !$this->is_on_last_page($form)) {
			return;
		}
		$this->validate_frcaptcha($form);
	}

	// Validate the Friendly Captcha solution
	public function validate_frcaptcha($form)
	{
		$plugin = FriendlyCaptcha_Plugin::$instance;
		if (!$plugin->is_configured()) {
			return;
		}

		$solution = frcaptcha_get_sanitized_frcaptcha_solution_from_post();
		if (empty($solution)) {
			$this->failed_validation  = true;
			$this->validation_message = FriendlyCaptcha_Plugin::default_error_user_message() . __(" (captcha missing)", "frcaptcha");
			return;
		}

		$verification = frcaptcha_verify_captcha_solution($solution, $plugin->get_sitekey(), $plugin->get_api_key(), 'gravityforms');
		if (!$verification["success"]) {
			$this->failed_validation  = true;
			$this->validation_message = FriendlyCaptcha_Plugin::default_error_user_message();
			GFCommon::log_debug(__METHOD__ . '(): Validating the Friendly Captcha response has failed due to the following: ' . reset($verification["error_codes"]));
			return;
		}
	}
}

GF_Fields::register(new GFForms_Friendlycaptcha_Field());
