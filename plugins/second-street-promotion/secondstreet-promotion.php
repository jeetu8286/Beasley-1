<?php
/**
 * Plugin Name: Second Street Promotion
 * Description: Plugin will allow Second Street Affiliates to embed a Second Street Promotion within their WordPress site(s).
 * Version: 1.0
 * Author: Second Street (Heather McCarron)
 * Author URI: http://secondstreet.com
 * License: GPL2
 */

 /*  Copyright 2014  Second Street | Heather McCarron  (email : heather@secondstreet.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/

 	// Blocks direct access to plugin
	defined('ABSPATH') or die("Access Forbidden");

	// [ss-promo] Code
	function ss_promo_func($atts, $content = null) {
		$a = shortcode_atts(array (
			'op_id' => '',
			'op_guid' => '',
			'routing' => ''
		), $atts);

		return '<script src="https://embed-' . $a['op_id'] . '.secondstreetapp.com/Scripts/dist/embed.js" data-ss-embed="promotion" data-opguid="' . $a['op_guid'] . '" data-routing="' . $a['routing'] . '"></script>';

	}
	add_shortcode( 'ss_promo', 'ss_promo_func' );