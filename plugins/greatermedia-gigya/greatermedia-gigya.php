<?php
/*
Plugin Name: Greater Media Gigya
Description: Greater Media Gigya
Author: 10up
*/

define( 'GMR_GIGYA_URL', plugin_dir_url( __FILE__ ) );
define( 'GMR_GIGYA_PATH', dirname( __FILE__ ) . '/' );
define( 'GMR_GIGYA_VERSION', '0.1.0' );
define( 'GMR_GIGYA_API_KEY', '3_e_T7jWO0Vjsd9y0WJcjnsN6KaFUBv6r3VxMKqbitvw-qKfmaUWysQKa1fra5MTb6' );
define( 'GMR_GIGYA_SECRET_KEY', 'trS0ufXWUXZ0JBcpr/6umiRfgUiwT7YhJMQSDpUz/p8=' );
define( 'GMR_MAILCHIMP_API_KEY', 'd288a2356ce46a76c0afbc67b9f537ad-us9' );

function gmr_gigya_main() {
	$plugin = new \GreaterMedia\Gigya\Plugin( __FILE__ );
	$plugin->enable();
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	gmr_gigya_main();
} else {
	error_log( 'Error: Composer packages not found, Please run $ composer install.' );
}
