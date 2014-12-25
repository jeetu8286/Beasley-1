<?php
/*
Plugin Name: Greater Media Contests
Description: Contest Features
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_CONTESTS_URL', plugin_dir_url( __FILE__ ) );
define( 'GREATER_MEDIA_CONTESTS_PATH', dirname( __FILE__ ) );
define( 'GREATER_MEDIA_CONTESTS_VERSION', '1.0.0' );

define( 'GMR_CONTEST_CPT', 'contest' );
define( 'EP_GMR_CONTEST', EP_PAGES << 1 );

include 'inc/contests.php';
include 'inc/class-greatermedia-contests.php';
include 'inc/class-greatermedia-contests-rewrites.php';
include 'inc/class-greatermedia-contest-entry.php';
include 'inc/class-greatermedia-contest-entry-embedded-form.php';
include 'inc/class-greatermedia-formbuilder-render.php';

if ( is_admin() ) {
	include 'inc/class-greatermedia-contests-metaboxes.php';
	include 'inc/class-greatermedia-contests-template-actions.php';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	include 'inc/class-greatermedia-contests-wp-cli.php';
}

register_activation_hook( __FILE__, 'flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );