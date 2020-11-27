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
        <p style="opacity:0.7">Friendly Captcha for Wordpress version <?php echo FriendlyCaptcha_Plugin::$version?></p>
    </div>
    <?php
}



function frcaptcha_general_section_callback() {
    echo '<p>If you don\'t have a FriendlyCaptcha account yet, you will need to first sign up at <a href="https://friendlycaptcha.com">FriendlyCaptcha.com</a>.';
}
function frcaptcha_integrations_section_callback() {
    echo '<p>Friendly Captcha can be enabled individually for different parts of your website. </p>';
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
?>