<?php
/**
 * Class GMP_Player
 *
 * This class constructs a podcast player to use on the front end of a website
 */
class GMP_Player{

	/**
	 * Hook into the appropriate actions when the class is initiated.
	 */
	public static function init() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

	}

	/**
	 * Enqueue scripts and styles
	 */
	public static function enqueue_scripts() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'gmpodcasts-js', GMPODCASTS_URL . "/assets/js/greater_media_podcasts{$postfix}.js", array( 'jquery' ), GMPODCASTS_VERSION, true );
		wp_enqueue_script( 'mediaelement-js', GMPODCASTS_URL . "/assets/js/vendor/mediaelement-and-player{$postfix}.js", array( 'jquery' ), '2.15.1', true );
		wp_enqueue_style( 'gmpodcasts-css', GMPODCASTS_URL . "/assets/css/greater_media_podcasts{$postfix}.css", array(), GMPODCASTS_VERSION );

	}

}