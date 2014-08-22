<?php
/*
Plugin Name: Greater Media Contests
Description: Contest Features
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_CONTESTS_PATH', dirname( __FILE__ ) );

include trailingslashit( __DIR__ ) . 'inc/class-greatermedia-contests.php';
include trailingslashit( __DIR__ ) . 'inc/class-greatermedia-contests-metaboxes.php';

if ( defined('WP_CLI') && WP_CLI ) {
	include trailingslashit( __DIR__ ) . 'inc/class-greatermedia-contests-wp-cli.php';
}
