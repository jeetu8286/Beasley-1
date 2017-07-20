<?php
/**
 * KDWN functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package KDWN
 * @since 0.1.0
 */

 // Useful global constants
define( 'KDWN_VERSION', '0.1.4' );

 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function kdwn_setup() {
	/**
	 * Makes KDWN available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on KDWN, use a find and replace
	 * to change 'kdwn' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'kdwn', get_stylesheet_directory_uri() . '/languages' );
 }
 add_action( 'after_setup_theme', 'kdwn_setup' );

 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function kdwn_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_dequeue_style( 'greatermedia' );
	wp_deregister_style( 'greatermedia' );
	wp_enqueue_style( 'kdwn', get_stylesheet_directory_uri() . "/assets/css/kdwn{$postfix}.css", array(), KDWN_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'kdwn_scripts_styles', 20 );

 /**
  * Add humans.txt to the <head> element.
  */
 function kdwn_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_stylesheet_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'kdwn_humans', $humans );
 }
 add_action( 'wp_head', 'kdwn_header_meta' );

 // Only show local posts on the home page
 function local_news_home_category( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'category_name', 'local-news' );
    }
}
add_action( 'pre_get_posts', 'local_news_home_category' );
