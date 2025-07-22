<?php

/* The main options page */

function frcaptcha_options_page_html()
{
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <hr>
        <form action="options.php" method="post">
            <?php
            settings_fields(FriendlyCaptcha_Plugin::$option_group);
            do_settings_sections('friendly_captcha_admin');
            submit_button();
            ?>
        </form>
        <p style="opacity:0.7">Friendly Captcha for WordPress. Version <?php echo FriendlyCaptcha_Plugin::$version ?>.</p>
    </div>
<?php
}



function frcaptcha_general_section_callback()
{
    echo '<p>If you don\'t have a Friendly Captcha account yet, you can sign up at <a href="https://friendlycaptcha.com" target="_blank">FriendlyCaptcha.com</a>.';
}

function frcaptcha_save_section_callback()
{
    echo '<p><button class="button button-primary" type="submit">Save Changes</button></p>';
}

function frcaptcha_integrations_section_callback()
{
    $show_all_integrations = isset($_GET['frcaptcha-all-integrations']);

    $toggle_url = esc_url(add_query_arg('frcaptcha-all-integrations', $show_all_integrations ? false : '1'));
    $toggle_text = $show_all_integrations ? 'Show installed integrations' : 'Show all integrations';

    echo '<p>Friendly Captcha can be integrated into a number of different form plugins. You can enable Friendly Captcha for each of them separately.</p>
    <p>This list only shows integrations for the plugins you have installed. <br/><b>
    <a href="' . $toggle_url . '">' . $toggle_text . '</a></b></p>';
}

function frcaptcha_widget_section_callback()
{
    echo '<p>Settings for the Friendly Captcha widget. This is the widget the users of your website will see.</p>';
}

function frcaptcha_endpoint_section_callback()
{
    echo '<p><b>⚠️ If you are not on a Friendly Captcha Advanced or Enterprise plan, this section is not relevant for you.</b></p>
    <p>Endpoint for the widget to talk to. If no boxes are selected the global endpoint is used.</p>
    <p>To use the dedicated EU endpoint must enable it for your app in the <a href="https://app.friendlycaptcha.com/dashboard/">dashboard</a>. If you are seeing 403 erorrs, please ensure this endpoint is enabled in the Friendly Captcha dashboard for your sitekey.</p>';
}

// field content cb
function frcaptcha_settings_field_callback(array $args)
{
    $type   = $args['type'];
    $option_name   = $args['option_name'];
    $description = $args['description'];

    // Value of the option
    $setting = get_option($option_name);

    $value = isset($setting) ? esc_attr($setting) : '';
    $checked = "";

    if ($type == "checkbox") {
        $value = 1;
        $checked = checked(1, $setting, false);
    }

    if ($type == "password") {
        $value = str_repeat("*", strlen($value));
    }
?>
    <input autcomplete="none" type="<?php echo $type; ?>" name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>" value="<?php echo $value ?>" <?php echo $checked ?>>
    <label class="description" for="<?php echo $option_name; ?>"><?php echo $description ?></label>
<?php
}

// a specific callback for the language dropdown as it's hard to generalize.
function frcaptcha_widget_language_field_callback(array $args)
{
    $option_name   = $args['option_name'];
    $description = $args['description'];

    // Value of the option
    $setting = get_option($option_name);
    $value = isset($setting) ? esc_attr($setting) : 'automatic';
?>
    <select autcomplete="none" type="select" name="<?php echo $option_name; ?>" id="<?php echo $option_name; ?>">
        <option value="automatic" <?php if ($value == "automatic") {
                                        echo "selected ";
                                    } ?>>Automatic</option>
        <?php
        foreach (FRIENDLY_CAPTCHA_SUPPORTED_LANGUAGES as $code => $name) {
            $selected = $code == $value ? 'selected' : '';
            echo "<option value=\"{$code}\" {$selected}>{$name}</option>";
        }
        ?>
    </select>
    <p class="description"><?php echo $description ?></p>
<?php
}
?>