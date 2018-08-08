<?php

namespace Beasley\Integration;

class Google extends \Beasley\Module {

	const OPTION_GTM       = 'beasley_google_tag_manager';
	const OPTION_UA        = 'gmr_google_analytics';
	const OPTION_UA_UID    = 'gmr_google_uid_dimension';
	const OPTION_UA_AUTHOR = 'gmr_google_author_dimension';

	/**
	 * Registers current module.
	 *
	 * @access public
	 */
	public function register() {
		add_action( 'wp_head', array( $this, 'render_gtm_head' ) );
		add_action( 'beasley_after_body', array( $this, 'render_gtm_body' ) );
		add_action( 'beasley-register-settings', array( $this, 'register_settings' ), 10, 2 );
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

		add_settings_field( self::OPTION_GTM, 'Tag Manager Code', 'beasley_input_field', $page, $section_id, 'name=beasley_google_tag_manager&desc=GTM-xxxxxx' );
		add_settings_field( self::OPTION_UA, 'Analytics Code', 'beasley_input_field', $page, $section_id, 'name=gmr_google_analytics&desc=UA-xxxxxx-xx' );
		add_settings_field( self::OPTION_UA_UID, 'User ID Dimension #', 'beasley_input_field', $page, $section_id, $uid_dimension_args );
		add_settings_field( self::OPTION_UA_AUTHOR, 'Author Dimension #', 'beasley_input_field', $page, $section_id, $author_dimension_args );

		register_setting( $group, self::OPTION_GTM, 'sanitize_text_field' );
		register_setting( $group, self::OPTION_UA, 'sanitize_text_field' );
		register_setting( $group, self::OPTION_UA_UID, 'sanitize_text_field' );
		register_setting( $group, self::OPTION_UA_AUTHOR, 'sanitize_text_field' );
	}

	/**
	 * Renders GTM script in the header if GTM Code has been provided.
	 *
	 * @access public
	 * @action wp_head
	 */
	public function render_gtm_head() {
		$gtm = trim( get_option( self::OPTION_GTM ) );
		if ( empty( $gtm ) ) {
			return;
		}

		$gtm = esc_js( $gtm );

		echo <<< EOL
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$gtm}');</script>
<!-- End Google Tag Manager -->
EOL;
	}

	/**
	 * Renders GTM script in the body if GTM Code has been provided.
	 *
	 * @access public
	 * @action beasley_after_body
	 */
	public function render_gtm_body() {
		$gtm = trim( get_option( self::OPTION_GTM ) );
		if ( empty( $gtm ) ) {
			return;
		}

		$gtm = esc_attr( urlencode( $gtm ) );

		echo <<<EOL
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={$gtm}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
EOL;
	}

}
