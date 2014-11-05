<?php
/*
Plugin Name: Greater Media Gigya
Description: Greater Media Gigya
Author: 10up
*/

define( 'GMR_GIGYA_URL', plugin_dir_url( __FILE__ ) );
define( 'GMR_GIGYA_PATH', dirname( __FILE__ ) . '/' );
define( 'GMR_GIGYA_PLUGIN_FILE', __FILE__ );
define( 'GMR_GIGYA_VERSION', '0.1.0' );
define( 'GMR_MAILCHIMP_API_KEY', 'd288a2356ce46a76c0afbc67b9f537ad-us9' );

function gmr_gigya_main() {
	$plugin = new \GreaterMedia\Gigya\Plugin( __FILE__ );
	$plugin->enable();
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
	gmr_gigya_main();

	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		$gigya_wp_cli_loader = new \GreaterMedia\Gigya\Commands\Loader();
		$gigya_wp_cli_loader->load();
	}
} else {
	error_log(
		'Error: Composer packages not found, Please run $ composer install.'
	);
}
