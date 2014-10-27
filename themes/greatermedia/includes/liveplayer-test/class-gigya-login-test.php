<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaGigyaTest {

	function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'gm_live_player_test_ui', array( $this, 'test_ui' ) );

	}

	public function wp_enqueue_scripts() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		if ( defined( 'GREATER_MEDIA_GIGYA_TEST_UI' ) && GREATER_MEDIA_GIGYA_TEST_UI ) {
			wp_enqueue_script( 'liveplayer-testing', get_template_directory_uri() . '/assets/js/liveplayer-test.js', array(), false, false );
		}

		wp_enqueue_script(
			'gigya_socialize',
			'http://cdn.gigya.com/JS/gigya.js?apiKey=3_e_T7jWO0Vjsd9y0WJcjnsN6KaFUBv6r3VxMKqbitvw-qKfmaUWysQKa1fra5MTb6',
			array( 'jquery' ),
			'0.1.0',
			true
		);

		wp_enqueue_script( 'gigya-login', get_template_directory_uri() . "/assets/js/gigya_login{$postfix}.js", array(), GREATERMEDIA_VERSION, true );
		wp_enqueue_script( 'liveplayer-login', get_template_directory_uri() . "/assets/js/liveplayer_login{$postfix}.js", array(), GREATERMEDIA_VERSION, true );

	}

	public static function test_ui() {

		if ( defined( 'GREATER_MEDIA_GIGYA_TEST_UI' ) && GREATER_MEDIA_GIGYA_TEST_UI ) {
			require_once( __DIR__ . '/greatermedia-gigya-auth-testing.html' );
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

$GreaterMediaGigyaTest = new GreaterMediaGigyaTest();