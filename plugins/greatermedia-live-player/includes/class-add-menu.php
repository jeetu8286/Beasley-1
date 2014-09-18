<?php
/**
 * Class GMLP_Menu
 *
 * This class generates an off-canvas nav button and container
 */
class GMLP_Menu {

	public static function init() {

		add_action( 'wp_footer', array( __CLASS__, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

	}

	/**
	 * Render the menu
	 */
	public static function render() {

		?>

		<nav class="gmlp-nav">

			<button class="gmlp-nav-toggle"><i class="fa fa-bars"></i></button>

			<div class="gmlp-menu">

				This is a test

			</div>

		</nav>

		<?php

	}

	/**
	 * Enqueue scripts and styles for the menu
	 */
	public static function enqueue_scripts() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script( 'gmlp-js', GMLIVEPLAYER_URL . "assets/js/greater_media_live_player{$postfix}.js", array( 'jquery' ), GMLIVEPLAYER_VERSION, false );
		wp_enqueue_script( 'pjax', GMLIVEPLAYER_URL . 'assets/js/vendor/pjax.js', array(), '0.1.3', true );
		wp_enqueue_style( 'gmlp-styles', GMLIVEPLAYER_URL . "assets/css/greater_media_live_player{$postfix}.css", array(), GMLIVEPLAYER_VERSION );

	}

}

GMLP_Menu::init();