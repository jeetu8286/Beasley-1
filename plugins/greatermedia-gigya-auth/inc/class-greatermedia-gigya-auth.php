<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaGigyaAuth {

	function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );

	}

	public function wp_enqueue_scripts() {

		global $GreaterMediaGigyaAuthSettings;

		if ( $GreaterMediaGigyaAuthSettings['USE_TEST_UI'] ) {
			wp_enqueue_style( 'greatermedia-gigya-auth-testing', trailingslashit( GREATER_MEDIA_GIGYA_AUTH_URL ) . 'css/greatermedia-gigya-auth-testing.css', array(), false, 'all' );
			wp_enqueue_script( 'greatermedia-gigya-auth-testing', trailingslashit( GREATER_MEDIA_GIGYA_AUTH_URL ) . 'js/greatermedia-gigya-auth-testing.js', array(), false, false );
		}

		wp_enqueue_script( 'greatermedia-gigya-auth', trailingslashit( GREATER_MEDIA_GIGYA_AUTH_URL ) . 'js/greatermedia-gigya-auth.js', array(), false, false );

	}

	public static function wp_footer() {

		global $GreaterMediaGigyaAuthSettings;

		if ( $GreaterMediaGigyaAuthSettings['USE_TEST_UI'] ) {
			include trailingslashit( GREATER_MEDIA_GIGYA_AUTH_PATH ) . 'tpl/greatermedia-gigya-auth-testing.html';
		}

	}

	/**
	 * Check if the current listener is logged in
	 * @return bool
	 */
	public static function is_gigya_user_logged_in() {

		return isset( $_COOKIE[ gm_gigya_user ] );

	}

	/**
	 * Get the current listener's Gigya user id
	 * @return null
	 */
	public static function gigya_user_id() {

		if ( ! self::is_gigya_user_logged_in() ) {
			return null;
		}

		$cookie_data = json_decode( $_COOKIE[ gm_gigya_user ] );
		if ( ! $cookie_data ) {
			return null;
		}

		if ( isset( $cookie_data->UID ) ) {
			return $cookie_data->UID;
		}

		return null;

	}

}

$GreaterMediaGigyaAuth = new GreaterMediaGigyaAuth();