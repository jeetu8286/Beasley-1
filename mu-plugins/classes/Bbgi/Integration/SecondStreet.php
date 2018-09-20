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
		add_action( 'beasley-register-settings', $this( 'register_settings' ), 10, 2 );

		// add shortcodes
		add_shortcode( 'ss_promo', $this( 'render_shortcode' ) );
		add_shortcode( 'ss-promo', $this( 'render_shortcode' ) );
	}

	/**
	 * Registers Google Analytics and Tag Manager settings.
	 *
	 * @access public
	 * @action beasley-register-settings
	 * @param string $group
	 * @param string $page
	 */
	public function register_settings( $group, $page ) {
		$section_id = 'beasley_secondstreet_settings';

		add_settings_section( $section_id, 'SecondStreet', '__return_false', $page );
		add_settings_field( 'secondstreet_op_id', '<code>op_id</code> attribute', 'beasley_input_field', $page, $section_id, 'name=secondstreet_op_id' );
		register_setting( $group, 'secondstreet_op_id', 'sanitize_text_field' );
	}

	/**
	 * Renders ss-promo shortcode.
	 *
	 * @access public
	 * @param array $attributes Array of shortcode arguments.
	 * @return string Shortcode markup.
	 */
	public function render_shortcode( $attributes ) {
		$op_id = isset( $attributes['op_id'] ) ? $attributes['op_id'] : '';
		$op_guid = ! empty( $attributes['op_guid'] ) ? $attributes['op_guid'] : '';

		$site_op_id = get_option( 'secondstreet_op_id', '' );
		if ( ! empty( $site_op_id ) ) {
			if ( empty( $op_id ) ) {
				$op_id = $site_op_id;
			}

			if ( ! empty( $attributes[ 'op_' . $site_op_id ] ) ) {
				$op_guid = $attributes[ 'op_' . $site_op_id ];
			}
		}

		return sprintf(
			'<div src="https://embed-%s.secondstreetapp.com/Scripts/dist/embed.js" data-ss-embed="promotion" data-opguid="%s" data-routing="%s"></div>',
			esc_attr( $op_id ),
			esc_attr( $op_guid ),
			! empty( $attributes['routing'] ) ? esc_attr( $attributes['routing'] ) : 'hash'
		);
	}

}
