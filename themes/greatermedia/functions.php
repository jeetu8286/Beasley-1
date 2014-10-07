<?php
/**
 * Greater Media functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package Greater Media
 * @since 0.1.0
 */
 
 // Useful global constants
define( 'GREATERMEDIA_VERSION', '0.1.0' );
 
 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function greatermedia_setup() {
	/**
	 * Makes Greater Media available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on Greater Media, use a find and replace
	 * to change 'greatermedia' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'greatermedia', get_template_directory() . '/languages' );
 }
 add_action( 'after_setup_theme', 'greatermedia_setup' );
 
 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function greatermedia_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'greatermedia', get_template_directory_uri() . "/assets/js/greater_media{$postfix}.js", array(), GREATERMEDIA_VERSION, true );
		
	wp_enqueue_style( 'greatermedia', get_template_directory_uri() . "/assets/css/greater_media{$postfix}.css", array(), GREATERMEDIA_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'greatermedia_scripts_styles' );
 
 /**
  * Add humans.txt to the <head> element.
  */
 function greatermedia_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_template_directory_uri() . '/humans.txt" />';
	
	echo apply_filters( 'greatermedia_humans', $humans );
 }
 add_action( 'wp_head', 'greatermedia_header_meta' );