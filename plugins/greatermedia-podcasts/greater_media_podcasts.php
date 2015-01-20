<?php
/**
 * Plugin Name: Greater Media Podcasts
 * Plugin URI:  http://wordpress.org/plugins
 * Description: A podcasting solution for Greater Media
 * Version:     0.1.0
 * Author:      10up
 * Author URI:  http://10up.com
 */

// Useful global constants
define( 'GMPODCASTS_VERSION', '0.1.0' );
define( 'GMPODCASTS_URL',     plugin_dir_url( __FILE__ ) );
define( 'GMPODCASTS_PATH',    dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

/**
 * Add required files
 */
require_once( __DIR__ . '/includes/class-gmp-cpt.php' );
require_once( __DIR__ . '/includes/class-gmp-metabox.php' );
require_once( __DIR__ . '/includes/class-gmp-player.php' );
require_once( __DIR__ . '/includes/class-gmp-options.php' );
require_once( __DIR__ . '/includes/class-gmp-feed.php' );

register_activation_hook( __FILE__, 'flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );