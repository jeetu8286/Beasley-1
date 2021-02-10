<?php
/**
 * Sets up settings page and shortcode for Second Street
 */

namespace Bbgi\Integration;

class HubspotForm extends \Bbgi\Module {

	/**
	 * Registers this module.
	 *
	 * @access public
	 */
	public function register() {
		// add shortcodes
		add_shortcode( 'hsform', $this( 'render_shortcode' ) );
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
			'portalid' => '',
			'formid' => ''
		), $atts, 'hsform' );

		$embed = sprintf(
			'<div class="hsform" data-portalid="%s" data-formid="%s"></div>',
			esc_attr( $attributes['portalid']),
			esc_attr( $attributes['formid'])
		);

		return apply_filters( 'hubspotform_html', $embed, $attributes );
	}

}
