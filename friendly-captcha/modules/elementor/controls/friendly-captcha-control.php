<?php
class Elementor_Friendly_Captcha_Control extends \Elementor\Base_Data_Control {

	protected function get_default_settings() {
		return [
			'label_block' => true,
			'separator' => 'after',
			'show_label' => 'false'
		];
	}

	public function get_type() {
		return "frcaptcha";
	}

	public function enqueue()
	{
    $plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_elementor_active()) {
        return;
    }

    frcaptcha_echo_script_tags();
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		$atts = "data-solution-field-name=\"" . $control_uid . "\"";
		$plugin = FriendlyCaptcha_Plugin::$instance;
    if (!$plugin->is_configured() or !$plugin->get_elementor_active()) {
        return;
    }

		echo frcaptcha_generate_widget_tag_from_plugin($plugin, $atts);
	}

}