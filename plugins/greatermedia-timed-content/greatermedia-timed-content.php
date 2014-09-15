<?php

/*
Plugin Name: Greater Media Timed Content
Description: Timed Content Plugin
Version: 1.0
Author: 10up
Author URI: http://10up.com
Text Domain: greatermedia-timed-content
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_TIMED_CONTENT_PATH', dirname( __FILE__ ) );
define( 'GREATER_MEDIA_TIMED_CONTENT_URL', plugin_dir_url( __FILE__ ) );

include trailingslashit( GREATER_MEDIA_TIMED_CONTENT_PATH ) . 'inc/class-greatermedia-timed-content.php';
include trailingslashit( GREATER_MEDIA_TIMED_CONTENT_PATH ) . 'inc/class-greatermedia-timed-content-shortcode.php';
