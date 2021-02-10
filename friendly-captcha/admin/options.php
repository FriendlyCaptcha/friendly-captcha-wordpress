<?php

/* The main options page */

function frcaptcha_options_page_html() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <hr>
        <form action="options.php" method="post">
            <?php
            settings_errors();
            settings_fields( FriendlyCaptcha_Plugin::$option_group );
            do_settings_sections( 'friendly_captcha_admin' );
            submit_button();
            ?>
        </form>
        <p style="opacity:0.7">Friendly Captcha for WordPress. Version <?php echo FriendlyCaptcha_Plugin::$version?>.</p>
    </div>
    <?php
}



function frcaptcha_general_section_callback() {
    echo '<p>If you don\'t have a Friendly Captcha account yet, you can sign up at <a href="https://friendlycaptcha.com" target="_blank">FriendlyCaptcha.com</a>.';
}
function frcaptcha_integrations_section_callback() {
    echo '<p>Friendly Captcha can be enabled individually for different parts of your website.</p>';
}
function frcaptcha_widget_section_callback() {
    echo '<p>Settings for the Friendly Captcha widget. This is the widget the users of your website will see.</p>';
}

// field content cb
function frcaptcha_settings_field_callback(array $args) {
    $type   = $args['type'];
    $option_name   = $args['option_name'];
    $description = $args['description'];

    // Value of the option
    $setting = get_option($option_name);

    $value = isset( $setting ) ? esc_attr( $setting ) : '';
    $checked = "";

    if ($type == "checkbox") {
        $value = 1;
        $checked = checked(1, $setting, false);
    } 
    ?>
    <input autcomplete="none" type="<?php echo $type; ?>" name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" value="<?php echo $value ?>" <?php echo $checked ?>>
    <p class="description"><?php echo $description ?></p>
    <?php
}

// a specific callback for the language dropdown as it's hard to generalize.
function frcaptcha_widget_language_field_callback(array $args) {
    $option_name   = $args['option_name'];
    $description = $args['description'];

    // Value of the option
    $setting = get_option($option_name);
    $value = isset( $setting ) ? esc_attr( $setting ) : 'en';
    ?>
    <select autcomplete="none" type="select" name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>">
        <option value="en" <?php if($value=="en"){echo "selected ";}?>>English</option>
        <option value="de" <?php if($value=="de"){echo "selected ";}?>>German</option>
        <option value="fr" <?php if($value=="fr"){echo "selected ";}?>>French</option>
        <option value="nl" <?php if($value=="nl"){echo "selected ";}?>>Dutch</option>
    </select>
    <p class="description"><?php echo $description ?></p>
    <?php
}
?>
