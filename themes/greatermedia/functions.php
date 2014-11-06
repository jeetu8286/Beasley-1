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

require_once( __DIR__ . '/includes/liveplayer-test/class-gigya-login-test.php' );
require_once( __DIR__ . '/includes/liveplayer/loader.php' );

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

	// Update this as appropriate content types are created and we want this functionality
	add_post_type_support( 'post', 'timed-content' );
	add_post_type_support( 'post', 'login-restricted-content' );

	/**
	 * Add theme support for post-formats
	 */
	$formats = array( 'gallery', 'link', 'image', 'video', 'audio' );
	add_theme_support( 'post-formats', $formats );

}

add_action( 'after_setup_theme', 'greatermedia_setup' );

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since 0.1.0
 */
function greatermedia_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_register_script(
		'headroom',
		get_template_directory_uri() . "/assets/js/vendor/headroom.min.js",
		array(),
		'0.7.0',
		true
	);
	wp_register_style(
		'open-sans',
		'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700',
		array(),
		GREATERMEDIA_VERSION
	);
	wp_register_style(
		'droid-sans',
		'http://fonts.googleapis.com/css?family=Droid+Sans:400,700',
		array(),
		GREATERMEDIA_VERSION
	);
	wp_register_style(
		'font-awesome',
		'//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css',
		array(),
		'4.2'
	);

	if ( ! defined( 'GREATER_MEDIA_GIGYA_TEST_UI' ) || ! GREATER_MEDIA_GIGYA_TEST_UI ) {
		wp_enqueue_script(
			'gigya_socialize',
			'http://cdn.gigya.com/JS/gigya.js?apiKey=3_e_T7jWO0Vjsd9y0WJcjnsN6KaFUBv6r3VxMKqbitvw-qKfmaUWysQKa1fra5MTb6',
			array( 'jquery' ),
			'0.1.0',
			true
		);
		wp_enqueue_script(
			'gigya-login',
			get_template_directory_uri() . "/assets/js/gigya_login{$postfix}.js",
			array(),
			GREATERMEDIA_VERSION,
			true
		);
		wp_enqueue_script(
			'liveplayer-login',
			get_template_directory_uri() . "/assets/js/liveplayer_login{$postfix}.js",
			array(),
			GREATERMEDIA_VERSION,
			true
		);
	}

	if ( is_page( 'style-guide' ) ) {
		wp_enqueue_script(
			'gm-styleguide',
			get_template_directory_uri() . "/assets/js/gm_styleguide{$postfix}.js",
			array(
				'jquery'
			),
			GREATERMEDIA_VERSION,
			true
		);
		wp_enqueue_script(
			'google-code-pretify',
			get_template_directory_uri() .
			'/assets/js/styleguide/prettify.js',
			array(
				'jquery'
			),
			GREATERMEDIA_VERSION,
			true
		);
		wp_enqueue_style(
			'gm-styleguide',
			get_template_directory_uri() . "/assets/css/gm_styleguide{$postfix}.css",
			array(
				'open-sans',
				'droid-sans',
				'font-awesome'
			),
			GREATERMEDIA_VERSION
		);
	} else {
		wp_enqueue_script(
			'greatermedia',
			get_template_directory_uri() . "/assets/js/greater_media{$postfix}.js",
			array(
				'headroom'
			),
			GREATERMEDIA_VERSION,
			true
		);
		wp_enqueue_script(
			'respond.js',
			get_template_directory_uri() . '/assets/js/vendor/respond.min.js',
			array(),
			'1.4.2',
			false
		);
		wp_enqueue_script(
			'html5shiv',
			get_template_directory_uri() . '/assets/js/vendor/html5shiv-printshiv.js',
			array(),
			'3.7.2',
			false
		);
		wp_enqueue_style(
			'greatermedia',
			get_template_directory_uri() . "/assets/css/greater_media{$postfix}.css",
			array(
				'dashicons',
				'open-sans',
				'droid-sans',
				'font-awesome'
			),
			GREATERMEDIA_VERSION
		);
	};

}

add_action( 'wp_enqueue_scripts', 'greatermedia_scripts_styles');

/**
 * Add humans.txt to the <head> element.
 */
function greatermedia_header_meta() {
	$humans = '<link type="text/plain" rel="author" href="' . get_template_directory_uri() . '/humans.txt" />';

	echo apply_filters( 'greatermedia_humans', $humans );
}

add_action( 'wp_head', 'greatermedia_header_meta' );

/**
 * Register Navigation Menus
 */
function greatermedia_nav_menus() {
	$locations = array(
		'main-nav' => __( 'Main Navigation', 'greatermedia' ),
		'secondary-nav' => __( 'Seconadary Navigation', 'greatermedia' ),
		'footer-nav' => __( 'Footer Navigation', 'greatermedia' )
	);
	register_nav_menus( $locations );
}

add_action( 'init', 'greatermedia_nav_menus' );

/**
 * Add Post Formats
 */
function greatermedia_post_formats() {

	global $post;
	$post_id = $post->ID;

	if ( has_post_format( 'gallery', $post_id ) ) {
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
 * Add Widget Areas
 */
function greatermedia_widgets_init() {

	register_sidebar( array(
		'name'          => 'Live Player Sidebar',
		'id'            => 'liveplayer_sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
	) );

}

add_action( 'widgets_init', 'greatermedia_widgets_init' );
