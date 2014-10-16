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
 * @since   0.1.0
 */

// Useful global constants
define( 'GREATERMEDIA_VERSION', '0.1.0' );

/**
 * Required files
 */
require_once( __DIR__ . '/includes/class-post-styles.php' );
require_once( __DIR__ . '/includes/gm-tinymce/loader.php');

/**
 * Set up theme defaults and register supported WordPress features.
 *
 * @uses  load_theme_textdomain() For translation/localization support.
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

	/**
	 * Add theme support for post thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'gm-article-thumbnail', 1580, 9999, false ); // thumbnails used for articles
}

add_action( 'after_setup_theme', 'greatermedia_setup' );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since 0.1.0
 */
function greatermedia_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
	if ( is_page( 'style-guide' ) ) {
		wp_enqueue_script( 'gm-styleguide', get_template_directory_uri() . "/assets/js/gm_styleguide{$postfix}.js", array( 'jquery' ), GREATERMEDIA_VERSION, true );
		wp_enqueue_script( 'google-code-pretify', get_template_directory_uri() . "/assets/js/styleguide/prettify.js", array( 'jquery' ), GREATERMEDIA_VERSION, true );
		wp_enqueue_style( 'gm-styleguide', get_template_directory_uri() . "/assets/css/gm_styleguide{$postfix}.css", array(), GREATERMEDIA_VERSION );
	} else {
		wp_enqueue_script( 'greatermedia', get_template_directory_uri() . "/assets/js/greater_media{$postfix}.js", array( 'jquery' ), GREATERMEDIA_VERSION, true );
		wp_enqueue_style( 'greatermedia', get_template_directory_uri() . "/assets/css/greater_media{$postfix}.css", array( 'dashicons' ), GREATERMEDIA_VERSION );
	}
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

/**
 * Add theme support for post-formats
 */
add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'video', 'audio' ) );

function greatermedia_post_formats() {

	global $post;
	$post_id = $post->ID;

	if ( has_post_format( 'aside', $post_id ) ) {
		$format = 'aside';
	} elseif ( has_post_format( 'gallery', $post_id ) ) {
		$format = 'gallery';
	} elseif ( has_post_format( 'link', $post_id ) ) {
		$format = 'link';
	} elseif ( has_post_format( 'image', $post_id ) ) {
		$format = 'image';
	} elseif ( has_post_format( 'video', $post_id ) ) {
		$format = 'video';
	} elseif ( has_post_format( 'audio', $post_id ) ) {
		$format = 'audio';
	} else {
		$format = 'standard';
	}

	echo $format;

}

/**
 * add a 'read more' link to the bottom of the excerpt
 *
 * @param $more
 *
 * @return string
 */
function new_excerpt_more( $more ) {
	return '<div class="read-more"><a href="' . get_permalink( get_the_ID() ) . '" class="read-more--btn">' . __( 'Read More', 'your-text-domain' ) . '</a></div>';
}

add_filter( 'excerpt_more', 'new_excerpt_more' );