<?php

add_action( 'bbgi_register_settings', 'ee_register_settings', 1, 2 );

if ( ! function_exists( 'ee_register_settings' ) ) :
	function ee_register_settings( $group, $page ) {
		add_settings_section( 'ee_site_settings', 'Station Settings', '__return_false', $page );

		add_settings_field( 'ee_newsletter_signup_page', 'Newsletter Signup Page', 'wp_dropdown_pages', $page, 'ee_site_settings', array(
			'name'              => 'ee_newsletter_signup_page',
			'selected'          => get_option( 'ee_newsletter_signup_page' ),
			'show_option_none'  => '&#8212;',
			'option_none_value' => '0',
		) );

		register_setting( $group, 'ee_newsletter_signup_page', 'intval' );
	}
endif;
