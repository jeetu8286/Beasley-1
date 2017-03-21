<?php
/**
 * 105.1 The Bounce functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package 105.1 The Bounce
 * @since 0.1.0
 */

 // Useful global constants
define( 'WMGC_VERSION', '2.0.8' ); /* Version bump by Steve 03/20/2017 */

 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function wmgc_setup() {
	/**
	 * Makes 105.1 The Bounce available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on 105.1 The Bounce, use a find and replace
	 * to change 'wmgc' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'wmgc', get_stylesheet_directory_uri() . '/languages' );
 }
 add_action( 'after_setup_theme', 'wmgc_setup' );

 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function wmgc_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

  wp_register_style('google-fonts-wmgc','//fonts.googleapis.com/css?family=Oswald:400,300,700',array(),null);
  wp_dequeue_style( 'greatermedia' );
	wp_deregister_style( 'greatermedia' );
	wp_enqueue_style( 'wmgc', get_stylesheet_directory_uri() . "/assets/css/the_bounce{$postfix}.css", array('google-fonts-wmgc'), WMGC_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'wmgc_scripts_styles', 20 );

 /**
  * Add Google tag manager to wp-head for P-T-P
  *
  */

 function wmgc_add_custom_gtm(){
 ?>
 <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KRWQX8" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

 <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
     new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
     j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
     '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-KRWQX8');</script>
 <?php
 }
 add_action('wp_head', 'wmgc_add_custom_gtm');



 /**
  * Add humans.txt to the <head> element.
  */
 function wmgc_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_stylesheet_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'wmgc_humans', $humans );
 }
 add_action( 'wp_head', 'wmgc_header_meta' );
