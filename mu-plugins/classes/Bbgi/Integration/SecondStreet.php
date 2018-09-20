<?php

namespace Bbgi\Integration;

class SecondStreet extends \Bbgi\Module {

	/**
	 * Registers this module.
	 *
	 * @access public
	 */
	public function register() {
		// add action hooks
		add_action( 'admin_head', 'gmr_setup_secondstreet_tinymce' );

		// add shortcodes
		add_shortcode( 'ss_promo', $this( 'render_shortcode' ) );
		add_shortcode( 'ss-promo', $this( 'render_shortcode' ) );
	}

	/**
	 * Renders ss-promo shortcode.
	 *
	 * @access public
	 * @param array $atts Array of shortcode arguments.
	 * @return string Shortcode markup.
	 */
	public function render_shortcode( $atts ) {
		$attributes = shortcode_atts( array(
			'op_id'   => get_option( 'secondstreet_op_id', '' ),
			'op_guid' => get_option( 'secondstreet_op_guid', '' ),
			'routing' => ''
		), $atts, 'ss-promo' );

		return sprintf(
			'<div src="https://embed-%s.secondstreetapp.com/Scripts/dist/embed.js" data-ss-embed="promotion" data-opguid="%s" data-routing="%s"></div>',
			esc_attr( $attributes['op_id'] ),
			esc_attr( $attributes['op_guid'] ),
			esc_attr( $attributes['routing'] )
		);
	}

}
