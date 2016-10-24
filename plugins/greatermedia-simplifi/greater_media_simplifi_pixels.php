<?php
/**
 * Plugin Name: Greater Media Simplifi Pixels
 * Plugin URI:  http://wordpress.org/plugins
 * Description: A solution to add Simplifi targeting and conversion pixels to our web sites.
 * Version:     0.1.0
 * Author:      Steve Meyers
 * Author URI:  http://greatermedia.com
 */

// Useful global constants
define( 'GMSIMPLIFI_PIXELS_VERSION', '0.1.0' );
define( 'GMSIMPLIFI_PIXELS_URL',     plugin_dir_url( __FILE__ ) );
define( 'GMSIMPLIFI_PIXELS_PATH',    dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

/**
 * Add required files
 */
require_once( __DIR__ . '/includes/class-gmp-simplifi-cpt.php' );
require_once( __DIR__ . '/includes/class-gmp-simplifi-metabox.php' );

register_activation_hook( __FILE__, 'gmr_simplifi_pixels_activated' );
register_deactivation_hook( __FILE__, 'gmr_simplifi_pixels_deactivated' );

function gmr_simplifi_pixels_activated() {
	\GMP_SIMPLIFI_CPT::simplifi_pixels_cpt();

	load_capabilities( \GMP_SIMPLIFI_CPT::SIMPLIFI_PIXEL_POST_TYPE );
}

function gmr_simplifi_pixels_deactivated() {
	unload_capabilities( \GMP_SIMPLIFI_CPT::SIMPLIFI_PIXEL_POST_TYPE );
}
