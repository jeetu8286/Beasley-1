<?php

class GMLP_Player {

	public static function init() {

		add_action( 'wp_footer', array( __CLASS__, 'load_js' ), 50 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 50 );
		add_action( 'gm_player', array( __CLASS__, 'render_player' ) );
		add_action( 'radio_callsign', array( __CLASS__, 'get_radio_callsign' ) );

	}

	public static function get_radio_callsign() {

		$radio_callsign = get_option( 'gmlp_radio_callsign', '' );

		echo sanitize_text_field( $radio_callsign );

	}

	public static function render_player() {

		?>
		<div id="player-actions">
			<div id="playButton" class="gmlp-audio-button play" data-station="<?php do_action( 'radio_callsign' ); ?>"></div>
			<button id="pauseButton" class="gmlp-audio-button" data-station="<?php do_action( 'radio_callsign' ); ?>"><i class="fa fa-pause"></i></button>
			<button id="resumeButton" class="gmlp-audio-button" data-station="<?php do_action( 'radio_callsign' ); ?>"><i class="fa fa-play-circle-o"></i></button>
		</div>

		<div id="now-playing-live">
			<div id="nowPlaying">
				<div id="trackInfo">
				</div>
				<div id="npeInfo"></div>
			</div>
		</div>

		<!-- Player placeholder -->
		<div id="td_container"></div>

	<?php

	}

	/**
	 * Call TD Player js files
	 */
	public static function enqueue_scripts() {

		wp_enqueue_script( 'load-jquery', GMLIVEPLAYER_URL . 'assets/js/src/jquery.load.js', array(), GMLIVEPLAYER_VERSION, true );
		wp_enqueue_script( 'tdplayer', GMLIVEPLAYER_URL . 'assets/js/vendor/td-player/tdplayer.js', array( 'jquery' ), '2.5', true );
		wp_enqueue_script( 'tdplayer-api', GMLIVEPLAYER_URL . 'assets/js/vendor/td-player/tdplayer-api.js', array(), '2.5', true );

	}

	/**
	 * @todo find a better way to load these
	 */
	public static function load_js() {

//		echo '<script src="http://player.listenlive.co/api/2.5/js/jquery-1.7.2.min.js"></script>';

		echo '<script data-dojo-config="onReady:window.tdPlayerApiReady, async: 1, tlmSiblingOfDojo: 0, deps:[\'tdapi/run\']" src="//api.listenlive.co/tdplayerapi/2.5/dojo/dojo.js"></script>';

	}

}

GMLP_Player::init();