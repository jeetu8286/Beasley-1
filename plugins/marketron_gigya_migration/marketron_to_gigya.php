<?php
/*
Plugin Name: Marketron to Gigya
Description:
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) die( "Please don't try to access this file directly." );
define( 'MARKETRON_TO_GIGYA_PATH', dirname( __FILE__ ) );

if ( defined('WP_CLI') && WP_CLI ) {
	include "vendor/autoload.php";
	include 'tidyjson.php';
	include trailingslashit(__DIR__) . 'inc/class-marketron-to-gigya.php';
}
