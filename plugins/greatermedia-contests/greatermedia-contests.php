<?php
/*
Plugin Name: Greater Media Contests
Description: Contest Features
Version: 1.2.1
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

define( 'GREATER_MEDIA_CONTESTS_URL', plugin_dir_url( __FILE__ ) );
define( 'GREATER_MEDIA_CONTESTS_PATH', dirname( __FILE__ ) );
define( 'GREATER_MEDIA_CONTESTS_VERSION', '1.5.0' );

define( 'GMR_CONTEST_CPT', 'contest' );

include 'inc/contests.php';
if ( is_admin() || ( defined( 'DOING_ASYNC' ) && DOING_ASYNC ) ) {
	include 'inc/metaboxes.php';
}

register_activation_hook( __FILE__, 'gmr_contests_activated' );
register_deactivation_hook( __FILE__, 'gmr_contests_deactivated' );

function gmr_contests_activated() {
	gmr_contests_register_post_type();
	load_capabilities( GMR_CONTEST_CPT );
	flush_rewrite_rules();
}

function gmr_contests_deactivated() {
	unload_capabilities( GMR_CONTEST_CPT );
	flush_rewrite_rules();
}
