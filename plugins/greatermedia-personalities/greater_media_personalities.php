<?php
/**
 * Plugin Name: Greater Media Personalities
 * Plugin URI:  http://
 * Description: Adds support for biographical information about a personality.
 * Version:     0.1.0
 * Author:      Michael Phillips (10up)
 * Author URI:
 * License:     GPLv2+
 * Text Domain: gmi_personality
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2014 Michael Phillips (10up) (email : michael.phillips@10up.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

// Useful global constants
define( 'GMI_PERSONALITY_VERSION', '0.1.0' );
define( 'GMI_PERSONALITY_URL',     plugin_dir_url( __FILE__ ) );
define( 'GMI_PERSONALITY_PATH',    dirname( __FILE__ ) . '/' );

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

include __DIR__ . '/includes/class-gmi-personality.php';
include __DIR__ . '/includes/gmi-personality-live-links.php';

/**
 * Default initialization for the plugin:
 * - Registers the default textdomain.
 */
function gmi_personality_init() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'gmi_personality' );
	load_textdomain( 'gmi_personality', WP_LANG_DIR . '/gmi_personality/gmi_personality-' . $locale . '.mo' );
	load_plugin_textdomain( 'gmi_personality', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * Activate the plugin
 */
function gmi_personality_activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	gmi_personality_init();

	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'gmi_personality_activate' );

/**
 * Deactivate the plugin
 * Uninstall routines should be in uninstall.php
 */
function gmi_personality_deactivate() {

}
register_deactivation_hook( __FILE__, 'gmi_personality_deactivate' );

// Wireup actions
add_action( 'init', 'gmi_personality_init' );

// Wireup filters

// Wireup shortcodes
