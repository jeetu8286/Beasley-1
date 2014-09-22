<?php
/**
 * Class GMLP_Menu
 *
 * This class generates an off-canvas nav button and container
 */
class GMLP_Menu {

	public static function init() {

		add_action( 'wp_footer', array( __CLASS__, 'render_player_menu' ) );
		//add_action( 'wp_footer', array( __CLASS__, 'render_pjax_time' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

	}

	/**
	 * Render the menu
	 */
	public static function render_player_menu() {

		?>

		<nav class="gmlp-nav">

			<button class="gmlp-nav-toggle"><i class="fa fa-volume-up"></i></button>

			<div class="gmlp-menu">

				<h2>Live Player</h2>

				<?php do_action( 'gm_player' ); ?>

			</div>

		</nav>

		<?php

	}

	public static function render_pjax_time() {

		?>

		<div class="gmlp-time">
			<?php
			$dt = new DateTime();
			echo $dt->format('Y-m-d H:i:s');
			?>
			<label><input type="checkbox" name="pjax" />pjax</label>
		</div>

		<?php

	}

	/**
	 * Enqueue scripts and styles for the menu
	 */
	public static function enqueue_scripts() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_script( 'pjax', GMLIVEPLAYER_URL . 'assets/js/vendor/pjax-standalone.min.js', array(), '0.1.3', false );
		wp_enqueue_script( 'pjax-load', GMLIVEPLAYER_URL . 'assets/js/src/pjax-load.js', array( 'pjax' ), GMLIVEPLAYER_VERSION, false );
		wp_enqueue_script( 'gmlp-js', GMLIVEPLAYER_URL . "assets/js/greater_media_live_player.js", array( 'jquery', 'pjax' ), GMLIVEPLAYER_VERSION, false );
		wp_enqueue_script( 'jquery-cookie', GMLIVEPLAYER_URL . 'assets/js/src/jquery.cookie.js', array(), GMLIVEPLAYER_VERSION, false );
		wp_enqueue_style( 'gmlp-styles', GMLIVEPLAYER_URL . "assets/css/greater_media_live_player{$postfix}.css", array(), GMLIVEPLAYER_VERSION );

		wp_localize_script( 'gmlp-js', 'player', array(
			'location' => get_option( 'gmlp_player_location', '' )
		) );

	}

}

GMLP_Menu::init();