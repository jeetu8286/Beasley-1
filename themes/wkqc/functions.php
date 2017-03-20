<?php
/**
 * WKQC functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package WKQC
 * @since 0.1.0
 */

 // Useful global constants
define( 'WKQC_VERSION', '0.1.2' ); /* Version bump by Steve 03/17/2017 */

 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function wkqc_setup() {
	/**
	 * Makes WKQC available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on WKQC, use a find and replace
	 * to change 'wkqc' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'wkqc', get_stylesheet_directory_uri() . '/languages' );
 }
 add_action( 'after_setup_theme', 'wkqc_setup' );

 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function wkqc_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_dequeue_style( 'greatermedia' );
	wp_deregister_style( 'greatermedia' );
	wp_enqueue_style( 'wkqc', get_stylesheet_directory_uri() . "/assets/css/wkqc{$postfix}.css", array(), WKQC_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'wkqc_scripts_styles', 20 );

 /**
  * Add humans.txt to the <head> element.
  */
 function wkqc_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_stylesheet_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'wkqc_humans', $humans );
 }
 add_action( 'wp_head', 'wkqc_header_meta' );
