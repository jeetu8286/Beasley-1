<?php

/*
Plugin Name: Greater Media Gigya Auth
Description: Greater Media Gigya Auth abstraction
Version: 1.0
Author: 10up
Author URI: http://10up.com
Text Domain: greatermedia-gigya-auth
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_GIGYA_AUTH_PATH', dirname( __FILE__ ) );
define( 'GREATER_MEDIA_GIGYA_AUTH_URL',  plugin_dir_url( __FILE__ ) );

include trailingslashit( GREATER_MEDIA_GIGYA_AUTH_PATH ) . 'inc/class-greatermedia-gigya-auth.php';
