<?php

add_action( 'bbgi_register_settings', 'ee_register_dfp_settings', 10, 2 );

if ( ! function_exists( 'ee_register_dfp_settings' ) ) :
	function ee_register_dfp_settings( $group, $page ) {
		add_settings_section( 'beasley_dfp_global_targeting_settings', 'DFP Global Targeting', '__return_false', $page );

		$settings = array(
			'dfp_targeting_market' => 'Market Targeting Value',
			'dfp_targeting_genre'  => 'Genre Targeting Value',
			'dfp_targeting_ctest'  => 'CTest Targeting Value',
		);

		foreach ( $settings as $key => $label ) {
			register_setting( $group, $key, 'sanitize_text_field' );
			add_settings_field( $key, $label, 'bbgi_input_field', $page, 'beasley_dfp_global_targeting_settings', 'name=' . $key );
		}
	}
endif;
