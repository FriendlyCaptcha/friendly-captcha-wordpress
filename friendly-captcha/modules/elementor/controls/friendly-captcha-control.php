<?php
class Elementor_Friendly_Captcha_Control extends \Elementor\Base_Control {

	public function get_type() {
		return "FriendlyCaptcha";
	}

	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
			<div class="elementor-control-input-wrapper">
				<# if ( data.label ) {#>
					<label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
				<# } #>
				<input id="<?php echo $control_uid; ?>"/>
				<# if ( data.description ) { #>
					<div class="elementor-control-field-description">{{{ data.description }}}</div>
				<# } #>
			</div>
		<?php
	}

}