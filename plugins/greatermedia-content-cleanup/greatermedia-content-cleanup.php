<?php
/**
 * Plugin Name: Greater Media Content Cleanup
 * Plugin URI:  http://wordpress.org/plugins
 * Description: Content cleanup allows to remove auto-generated content from the site.
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

function gmr_cotnent_cleanup_activation() {
	$timestamp = current_time( 'timestamp', 1 ) + DAY_IN_SECONDS;
	wp_schedule_event( $timestamp, 'daily', 'gmr_do_content_cleanup' );
}

function gmr_content_cleanup_deactivation() {
	$timestamp = wp_next_scheduled( 'gmr_do_content_cleanup' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'gmr_do_content_cleanup' );
	}
}

register_activation_hook( __FILE__, 'gmr_cotnent_cleanup_activation' );
register_deactivation_hook( __FILE__, 'gmr_content_cleanup_deactivation' );

if ( is_admin() ) {
	add_action( 'plugins_loaded', 'gmr_content_cleanup_setup' );
}