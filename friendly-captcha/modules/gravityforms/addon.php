<?php
GFForms::include_addon_framework();
 
class GFFormsFriendlyCaptchaAddOn extends GFAddOn {
 
    protected $_version = FRIENDLY_CAPTCHA_VERSION;
    protected $_min_gravityforms_version = '2.5';
    protected $_slug = 'frcaptcha';
    protected $_full_path = __FILE__;
    protected $_short_title = 'Friendly Captcha';
 
    private static $_instance = null;
 
    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFFormsFriendlyCaptchaAddOn();
        }
 
        return self::$_instance;
    }

    // Icon for settings page tab
    public function get_menu_icon() {
        return 'dashicons-shield-alt';
    }

    // Uncomment to make it show up in the settings screen.
    // // Global plugin settings
    // public function plugin_settings_fields() {
    //     return array(
    //         array(
    //             'title'  => esc_html__( "Friendly Captcha Settings", 'frcaptcha' ),
    //             'description' => esc_html__("There are no settings here, use the ", "frcaptcha") . " <a href=\"/wp-admin/admin.php?page=friendly_captcha_admin\">" . esc_html("Friendly Captcha Plugin Settings", "frcaptcha") . "</a> " . esc_html("to customize the widget.", "frcaptcha"),
    //             'fields' => 
    //                 array(
    //                 // array(
    //                 //     'name'                  => 'frcaptcha-sitekey',
    //                 //     'tooltip'               => '<h6>' . esc_html__( 'Friendly Captcha sitekey', 'frcaptcha' ) . '</h6>',
    //                 //     'label'                 => '<label for="frcaptcha-sitekey"><strong>' . esc_html__( 'Friendly Captcha sitekey', 'frcaptcha' ) . '</strong></label>',
    //                 //     'type'                  => 'text',
    //                 //     'style'                 => 'width:350px;',
    //                 //     'required'              => true,
    //                 //     'autocomplete'          => 'off'
    //                 // )
    //             )
    //         )
    //     );
    // }

    public function pre_init() {
        parent::pre_init();
     
        if ( $this->is_gravityforms_supported() && class_exists( 'GF_Field' )) {
            require_once( 'field.php' );
        }
    }

    public function init_admin() {
        parent::init_admin();
    }
}