<?php
/**
 * Contains methods for customizing the theme customization screen.
 */
class GMR_Customizer {
    public static function init() {
        add_action( 'gmr_get_homepage_layout', array( __CLASS__, 'get_homepage_layout' ) );
    }

    public static function register ( $wp_customize ) {
        // Remove default sections
        $wp_customize->remove_section( 'colors' );
        $wp_customize->remove_section( 'title_tagline' );
        $wp_customize->remove_section( 'static_front_page' );
        $wp_customize->remove_section( 'header_image' );
        $wp_customize->remove_section( 'background_image' );
        $wp_customize->remove_section( 'nav' );

        // Layout setting
        $wp_customize->add_section( 'layout_section',
           array(
              'title' => __( 'Layout', 'gmiproto' ),
              'priority' => 20,
              'capability' => 'edit_theme_options',
              'description' => __('Customize various layout features.', 'gmiproto'),
           )
        );

        $wp_customize->add_setting( 'homepage_layout_setting',
            array(
              'default' => '1-column',
              'type' => 'theme_mod',
              'capability' => 'edit_theme_options',
              'transport' => 'refresh',
              'sanitize_callback' => array( __CLASS__, 'sanitize_homepage_layout_setting' ),
            )
        );

        $wp_customize->add_control( 'homepage_layout_control',
            array(
                'type' => 'select',
                'label' => __( 'Homepage Layout', 'gmiproto' ),
                'section' => 'layout_section',
                'settings' => 'homepage_layout_setting',
                'active_callback' => 'is_front_page',
                'description' => __('Above the fold layout for the home page', 'gmiproto'),
                'choices' => array(
                    'one-column' => 'One Column',
                    'two-column' => 'Two Columns',
                    'three-column' => 'Three Columns',
                )
            )
        );

        // Look and feel settings
        $wp_customize->add_section( 'appearance_section',
           array(
              'title' => __( 'Look & Feel', 'gmiproto' ),
              'priority' => 30,
              'capability' => 'edit_theme_options',
              'description' => __('Customize the look and feel of the site.', 'gmiproto'),
           )
        );

        $wp_customize->add_setting( 'look_and_feel_setting',
            array(
              'default' => '',
              'type' => 'theme_mod',
              'capability' => 'edit_theme_options',
              'transport' => 'refresh',
              'sanitize_callback' => array( __CLASS__, 'sanitize_look_and_feel_setting' ),
            )
        );

        $wp_customize->add_control( 'look_and_feel_control',
            array(
                'type' => 'select',
                //'label' => __( 'Homepage Layout', 'gmiproto' ),
                'section' => 'appearance_section',
                'settings' => 'look_and_feel_setting',
                //'description' => __('Above the fold layout for the home page', 'gmiproto'),
                'choices' => array(
                    '' => 'Normal',
                    'bold' => 'Bold',
                    'shrimp' => 'Shrimp',
                )
            )
        );
    }

    public static function get_homepage_layout() {
        if ( is_front_page() ) {
            $mod_name = get_theme_mod( 'homepage_layout_setting' );

            if ( ! empty( $mod_name ) ) {
                locate_template( 'partials/' . $mod_name . '-homepage-layout.php', true );
            }
        }
    }

    public static function enqueue_custom_look_scripts() {
        $mod_name = get_theme_mod( 'look_and_feel_setting' );

        if ( ! empty( $mod_name ) ) {
            wp_enqueue_style( 'gmi-custom-look-' . $mod_name , get_stylesheet_directory_uri() . "/assets/css/customizer_" . $mod_name . ".min.css", array('gmiproto'), GMIPROTO_VERSION );
        }
    }

    /**
     * Sanitize the homepage layout setting.
     */
    public static function sanitize_homepage_layout_setting( $value ) {
        if ( ! in_array( $value, array( 'one-column', 'two-column', 'three-column' ) ) ) {
            $value = 'one-column';
        }

        return $value;
    }

    /**
     * Sanitize the look and feel setting.
     */
    public static function sanitize_look_and_feel_setting( $value ) {
        if ( ! in_array( $value, array( '', 'bold', 'shrimp' ) ) ) {
            $value = '';
        }

        return $value;
    }
}

GMR_Customizer::init();

add_action( 'customize_register' , array( 'GMR_Customizer' , 'register' ) );

add_action( 'wp_enqueue_scripts', array( 'GMR_Customizer' , 'enqueue_custom_look_scripts' ) );
