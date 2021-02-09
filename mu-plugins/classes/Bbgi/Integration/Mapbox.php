<?php
/**
 * Sets up settings page and shortcode for Second Street
 */

namespace Bbgi\Integration;

class Mapbox extends \Bbgi\Module {

	/**
	 * Registers this module.
	 *
	 * @access public
	 */
	public function register() {
		// add shortcodes
		add_shortcode( 'mapbox', $this( 'render_shortcode' ) );
	}

	/**
	 * Renders ss-promo shortcode.
	 *
	 * @access public
	 * @param array $attributes Array of shortcode arguments.
	 * @return string Shortcode markup.
	 */
	public function render_shortcode( $atts ) {
		$attributes = shortcode_atts( array(
			'accesstoken' => '',
			'style' => '',
			'lat' => '0',
			'long' => '0',
			'zoom' => '9'
		), $atts, 'mapbox' );

		$embed = sprintf(
			'<div class="mapbox" data-accesstoken="%s" data-style="%s" data-long="%s" data-lat="%s" data-zoom="%s"></div>',
			esc_attr( $attributes['accesstoken']),
			esc_attr( $attributes['style']),
			esc_attr( $attributes['lat']),
			esc_attr( $attributes['long']),
			esc_attr( $attributes['zoom'])
		);

		return apply_filters( 'mapbox_html', $embed, $attributes );
	}

}
