<?php
/*
Plugin Name: Beasley Admin Fixes
Description: Hotfixes for Beasley admin plugins. New post defaults, etc. until plugin maintainers catch up.
Version: 1.0
Author: 10up
Author URI: http://10up.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}


/**
 * Sets checkbox of "Needs Photo" to false by default, see bug created here: https://github.com/Automattic/Edit-Flow/issues/397
 */
function fix_new_post_checkbox_defaults() {
	wp_enqueue_script( 'new-post-checkbox-js', plugins_url( 'beasley-admin-fixes/js/checkbox.js' ), 'jquery', EDIT_FLOW_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'fix_new_post_checkbox_defaults', 100 );