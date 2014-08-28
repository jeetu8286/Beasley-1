<?php
/*
Plugin Name: Greater Media UGC
Description: Listener Submissions (User-Generated Content)
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_UGC_PATH', dirname( __FILE__ ) );
define( 'GREATER_MEDIA_UGC_URL', plugin_dir_url( __FILE__ ) );

include trailingslashit( GREATER_MEDIA_UGC_PATH ) . 'inc/class-greatermedia-ugc.php';
include trailingslashit( GREATER_MEDIA_UGC_PATH ) . 'inc/class-greatermedia-uggallery.php';
include trailingslashit( GREATER_MEDIA_UGC_PATH ) . 'inc/class-greatermedia-ugimage.php';
include trailingslashit( GREATER_MEDIA_UGC_PATH ) . 'inc/class-ugc-moderation-table.php';
