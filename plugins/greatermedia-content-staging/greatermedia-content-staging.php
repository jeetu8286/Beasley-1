<?php
/*
Plugin Name: Greater Media Content Staging
Description: Configure the "Content Staging" site for Greater Media
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_CONTENT_STAGING_PATH', dirname( __FILE__ ) );

include trailingslashit( __DIR__ ) . 'inc/class-greatermedia-content-staging.php';