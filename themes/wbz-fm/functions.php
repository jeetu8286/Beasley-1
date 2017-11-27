<?php
/**
 * WBZ-FM functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package WBZ-FM
 * @since 0.1.0
 */
 
 // Useful global constants
define( 'WBZ_FM_VERSION', '0.1.0' );
 
 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function wbz_fm_setup() {
	/**
	 * Makes WBZ-FM available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on WBZ-FM, use a find and replace
	 * to change 'wbz_fm' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'wbz_fm', get_stylesheet_directory_uri() . '/languages' );
 }
 add_action( 'after_setup_theme', 'wbz_fm_setup' );
 
 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function wbz_fm_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_dequeue_style( 'greatermedia' );
	wp_deregister_style( 'greatermedia' );	
	wp_enqueue_style( 'wbz_fm', get_stylesheet_directory_uri() . "/assets/css/wbz_fm{$postfix}.css", array(), WBZ_FM_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'wbz_fm_scripts_styles', 20 );
 
 /**
  * Add humans.txt to the <head> element.
  */
 function wbz_fm_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_stylesheet_directory_uri() . '/humans.txt" />';
	
	echo apply_filters( 'wbz_fm_humans', $humans );
 }
 add_action( 'wp_head', 'wbz_fm_header_meta' );