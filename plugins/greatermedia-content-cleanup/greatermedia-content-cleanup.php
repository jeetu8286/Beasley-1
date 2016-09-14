<?php
/**
 * Plugin Name: Greater Media Content Cleanup
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Content cleanup registers WP_CLI command to remove useless data from the site.
 * Version:     0.1.0
 * Author:      10up
 * Author URI:  http://10up.com
 * License:     GPLv2+
 */

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/includes/class-content-cleanup.php';
	WP_CLI::add_command( 'gmr-content', 'GMR_Content_Cleanup' );
}

function gmr_content_cleanup_setup() {
	require_once __DIR__ . '/includes/class-content-settings.php';

	$settings = new GMR_Content_Settings();
	$settings->setup();
}

if ( is_admin() ) {
	add_action( 'plugins_loaded', 'gmr_content_cleanup_setup' );
}