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

if ( ! function_exists( 'ee_has_newsletter_page' ) ) :
	function ee_has_newsletter_page() {
		$page_id = get_option( 'ee_newsletter_signup_page' );
		if ( $page_id < 1 ) {
			return false;
		}

		$page = get_post( $page_id );
		if ( ! is_a( $page, '\WP_Post' ) || $page->post_type != 'page' ) {
			return false;
		}

		return true;
	}
endif;

if ( ! function_exists( 'ee_the_newsletter_page_permalink' ) ) :
	function ee_the_newsletter_page_permalink() {
		$page_id = get_option( 'ee_newsletter_signup_page' );
		the_permalink( $page_id );
	}
endif;
