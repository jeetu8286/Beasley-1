<?php
/**
 * Plugin Name: Marketron Migration
 * Plugin URI:  10up.com
 * Description: Migration script from Marketron XML to WordPress
 * Version:     0.0.1
 * Author:      10up.com
 */


/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'GMEDIA_VERSION', '0.0.1' );
define( 'GMEDIA_URL',     plugin_dir_url( __FILE__ ) );
define( 'GMEDIA_PATH',    dirname( __FILE__ ) . '/' );

if ( defined( 'WP_CLI' ) and WP_CLI  ) {
	include_once GMEDIA_PATH . 'includes/class-gmediamigration.php';
	include_once GMEDIA_PATH . 'includes/class-MTM_Migration_Utils.php';

	require_once( __DIR__ . '/vendor/autoload.php' );
	\WP_CLI::add_command( 'marketron_migration', '\GreaterMedia\Commands\Migrator' );
	\WP_CLI::add_command( 'libsyn', '\GreaterMedia\Commands\LibSynSideloadCommand' );
	\WP_CLI::add_command( 'oembed', '\GreaterMedia\Commands\OEmbedCommand' );
}

if ( defined( 'DOING_ASYNC' ) && DOING_ASYNC ) {
	if ( ! function_exists( '\wp_generate_attachment_metadata' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
	}

	require_once( __DIR__ . '/vendor/autoload.php' );

	$thumbnail_list_regenerator = new \WordPress\Utils\ThumbnailListRegenerator();
	$thumbnail_list_regenerator->register();

	$gigya_action_generator = new \WordPress\Entities\GigyaUser();
	$gigya_action_generator->register();

	$media_file_sideloader = new \WordPress\Utils\MediaSideLoader();
	$media_file_sideloader->register();
}
