<?php
/**
 * Plugin Name: GreaterMedia Shows
 * Plugin URI:  http://wordpress.org/plugins
 * Description: GreaterMedia Shows Plugin
 * Version:     0.0.1
 * Author:      10up
 * Author URI:  http://10up.com
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

// Useful global constants
define( 'GMEDIA_SHOWS_VERSION', '0.0.1' );
define( 'GMEDIA_SHOWS_URL',     plugin_dir_url( __FILE__ ) );
define( 'GMEDIA_SHOWS_PATH',    dirname( __FILE__ ) . '/' );

if( is_readable( GMEDIA_SHOWS_PATH . 'includes/class-ShowsCPT.php' ) ) {
	include_once GMEDIA_SHOWS_PATH. 'includes/class-ShowsCPT.php';
}

if( is_readable( GMEDIA_SHOWS_PATH . 'includes/class-Metaboxes.php' ) ) {
	include_once GMEDIA_SHOWS_PATH. 'includes/class-Metaboxes.php';
}

