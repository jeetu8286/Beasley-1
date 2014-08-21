<?php
/*
Plugin Name: Greater Media Taxonomies
Description: Taxonomies for Greater Media station sites
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_TAXONOMIES_PATH', dirname( __FILE__ ) );

include trailingslashit( __DIR__ ) . 'inc/class-greatermedia-taxonomies.php';