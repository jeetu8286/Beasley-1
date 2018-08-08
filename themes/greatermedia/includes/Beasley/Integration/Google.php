<?php

namespace Beasley\Integration;

class Google extends \Beasley\Module {

	/**
	 * Registers current module.
	 *
	 * @access public
	 */
	public function register() {
		add_action( 'beasley-register-settings', array( $this, 'register_settings' ), 10, 2 );
	}

	/**
	 * Registers Google Analytics and Tag Manager settings.
	 *
	 * @access public
	 * @param string $group
	 * @param string $page
	 */
	public function register_settings( $group, $page ) {
		$section_id = 'beasley_google_settings';

		$uid_dimension_args = array(
			'name' => 'gmr_google_uid_dimension',
			'desc' => 'Sends the current user\'s ID to this custom Google Analytics dimension. Most sites can use dimension1 unless it is already in use.',
		);

		$author_dimension_args = array(
			'name' => 'gmr_google_author_dimension',
			'desc' => 'Sends the current post\'s author login ID to this custom Google Analytics dimension. Most sites can use dimension2 unless it is already in use.',
		);

		add_settings_section( $section_id, 'Google', '__return_false', $page );

		add_settings_field( 'beasley_google_tag_manager', 'Tag Manager Code', 'beasley_input_field', $page, $section_id, 'name=beasley_google_tag_manager&desc=GTM-xxxxxx' );
		add_settings_field( 'gmr_google_analytics', 'Analytics Code', 'beasley_input_field', $page, $section_id, 'name=gmr_google_analytics&desc=UA-xxxxxx-xx' );
		add_settings_field( 'gmr_google_uid_dimension', 'User ID Dimension #', 'beasley_input_field', $page, $section_id, $uid_dimension_args );
		add_settings_field( 'gmr_google_author_dimension', 'Author Dimension #', 'beasley_input_field', $page, $section_id, $author_dimension_args );

		register_setting( $group, 'beasley_google_tag_manager', 'sanitize_text_field' );
		register_setting( $group, 'gmr_google_analytics', 'sanitize_text_field' );
		register_setting( $group, 'gmr_google_uid_dimension', 'sanitize_text_field' );
		register_setting( $group, 'gmr_google_author_dimension', 'sanitize_text_field' );
	}

}
