<?php
/*
Plugin Name: LiveFyre Media Walls
Description: Media Wall integration
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_LIVEFYRE_WALLS_PATH', dirname( __FILE__ ) );
define( 'GREATER_MEDIA_LIVEFYRE_WALLS_URL', plugins_url( trailingslashit( basename( dirname( __FILE__ ) ) ) ) );

include trailingslashit( GREATER_MEDIA_LIVEFYRE_WALLS_PATH ) . 'inc/class-greatermedia-livefyre-media-wall.php';
include trailingslashit( GREATER_MEDIA_LIVEFYRE_WALLS_PATH ) . 'inc/class-greatermedia-livefyre-media-wall-admin.php';
