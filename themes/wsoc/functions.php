<?php
/**
 * WSOC functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package WSOC
 * @since 0.1.0
 */

 // Useful global constants
define( 'WSOC_VERSION', '0.1.2' ); /* Version bump by Steve 03/20/2017 */

 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function wsoc_setup() {
	/**
	 * Makes WSOC available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on WSOC, use a find and replace
	 * to change 'wsoc' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'wsoc', get_stylesheet_directory_uri() . '/languages' );
 }
 add_action( 'after_setup_theme', 'wsoc_setup' );

 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function wsoc_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_dequeue_style( 'greatermedia' );
	wp_deregister_style( 'greatermedia' );
	wp_enqueue_style( 'wsoc', get_stylesheet_directory_uri() . "/assets/css/wsoc{$postfix}.css", array(), WSOC_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'wsoc_scripts_styles', 20 );

 /**
  * Add humans.txt to the <head> element.
  */
 function wsoc_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_stylesheet_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'wsoc_humans', $humans );
 }
 add_action( 'wp_head', 'wsoc_header_meta' );
