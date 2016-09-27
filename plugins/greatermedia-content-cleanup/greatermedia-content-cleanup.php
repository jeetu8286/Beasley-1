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

define( 'GMR_CLEANUP_CRON', 'gmr_do_content_cleanup' );
define( 'GMR_CLEANUP_ASYNC_TASK', 'gmr_do_content_cleanup_async' );
define( 'GMR_CLEANUP_STATUS_OPTION', 'gmr-cleanup-status' );
define( 'GMR_CLEANUP_AUTHORS_OPTION', 'gmr-cleanup-authors' );
define( 'GMR_CLEANUP_AGE_OPTION', 'gmr-cleanup-age' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/includes/class-content-cleanup.php';
	WP_CLI::add_command( 'gmr-content', 'GMR_Content_Cleanup' );
}

function gmr_content_cleanup_setup() {
	require_once __DIR__ . '/includes/class-cleanup-cron.php';
	$cron = new GMR_Cleanup_Cron();
	$cron->setup();

	if ( is_admin() ) {
		require_once __DIR__ . '/includes/class-content-settings.php';
		$settings = new GMR_Content_Settings();
		$settings->setup();
	}
}

function gmr_cotnent_cleanup_activation() {
	$timestamp = wp_next_scheduled( GMR_CLEANUP_CRON );
	if ( ! $timestamp ) {
		$timestamp = current_time( 'timestamp', 1 ) + DAY_IN_SECONDS;
		wp_schedule_event( $timestamp, 'daily', GMR_CLEANUP_CRON );
	}
}

function gmr_content_cleanup_deactivation() {
	$timestamp = wp_next_scheduled( GMR_CLEANUP_CRON );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, GMR_CLEANUP_CRON );
	}
}

register_activation_hook( __FILE__, 'gmr_cotnent_cleanup_activation' );
register_deactivation_hook( __FILE__, 'gmr_content_cleanup_deactivation' );

gmr_content_cleanup_setup();