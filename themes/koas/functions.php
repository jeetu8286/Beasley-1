<?php
/**
 * KOAS functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package KOAS
 * @since 0.1.0
 */

 // Useful global constants
define( 'KOAS_VERSION', '0.1.1' ); /* Version bump by Steve 03/21/2017 */

 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function koas_setup() {
	/**
	 * Makes KOAS available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on KOAS, use a find and replace
	 * to change 'koas' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'koas', get_stylesheet_directory_uri() . '/languages' );
 }
 add_action( 'after_setup_theme', 'koas_setup' );

 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function koas_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_dequeue_style( 'greatermedia' );
	wp_deregister_style( 'greatermedia' );
	wp_enqueue_style( 'koas', get_stylesheet_directory_uri() . "/assets/css/koas{$postfix}.css", array(), KOAS_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'koas_scripts_styles', 20 );

 /**
  * Add humans.txt to the <head> element.
  */
 function koas_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_stylesheet_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'koas_humans', $humans );
 }
 add_action( 'wp_head', 'koas_header_meta' );
