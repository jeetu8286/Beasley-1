<?php
/**
 * Greater Media Prototype functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package Greater Media Prototype
 * @since 0.1.0
 */

 // Useful global constants
 define( 'GMIPROTO_VERSION', '0.1.0' );

 /*
  * Theme support
  */
 add_theme_support( 'gmr-customizer' );

 /*
  * Required files
  */
 require_once( __DIR__ . '/includes/customizer/loader.php' );

 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function gmiproto_setup() {
	 add_theme_support( 'post-thumbnails' );

	/**
	 * Makes Greater Media Prototype available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on Greater Media Prototype, use a find and replace
	 * to change 'gmiproto' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'gmiproto', get_template_directory() . '/languages' );
 }
 add_action( 'after_setup_theme', 'gmiproto_setup' );

 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function gmiproto_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script(
		'gigya_socialize',
		'http://cdn.gigya.com/JS/gigya.js?apiKey=3_e_T7jWO0Vjsd9y0WJcjnsN6KaFUBv6r3VxMKqbitvw-qKfmaUWysQKa1fra5MTb6',
		array('jquery'),
		'0.1.0',
		true
	);
	 wp_enqueue_script( 'gmiproto', get_template_directory_uri() . "/assets/js/greater_media_prototype{$postfix}.js", array(), GMIPROTO_VERSION, true );
	 wp_enqueue_script( 'respond-js', get_template_directory_uri() . '/assets/js/vendor/respond.min.js', array(), '1.4.2', false );
	 wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/assets/js/vendor/html5shiv.min.js', array(), '3.7.2', false );
	 wp_enqueue_style( 'gmiproto', get_template_directory_uri() . "/assets/css/greater_media_prototype{$postfix}.css", array(), GMIPROTO_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'gmiproto_scripts_styles' );

 /**
  * Add humans.txt to the <head> element.
  */
 function gmiproto_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_template_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'gmiproto_humans', $humans );
 }
 add_action( 'wp_head', 'gmiproto_header_meta' );

 /**
 * Used by hook: 'customize_preview_init'
 *
 * @see add_action('customize_preview_init',$func)
 */
 function gmi_customizer_live_preview()
 {
	wp_enqueue_script(
		'gmi-theme-customizer',
		get_template_directory_uri() . "/assets/js/theme-customize.min.js",
		array( 'jquery','customize-preview' ),
		GMIPROTO_VERSION,
		true
	);
}
add_action( 'customize_preview_init', 'gmi_customizer_live_preview' );
