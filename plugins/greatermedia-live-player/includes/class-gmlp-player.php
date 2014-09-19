<?php

class GMLP_Player {

	public static function init() {

		add_action( 'wp_footer', array( __CLASS__, 'load_js' ) );
		add_action( 'gm_player', array( __CLASS__, 'render_player' ) );
		add_action( 'radio_callsign', array( __CLASS__, 'get_radio_callsign' ) );

	}

	public static function get_radio_callsign() {

		$radio_callsign = get_option( 'gmlp_radio_callsign', '' );

		echo sanitize_text_field( $radio_callsign );

	}

	public static function render_player() {

		?>
		<div id="radio-callsign"><?php do_action( 'radio_callsign' ); ?></div>
		<input class="gmlp-audio-button" type="button" data-action="play-live" data-station="<?php do_action( 'radio_callsign' ); ?>" value="Play" />
		<input class="gmlp-audio-button" type="button" id="stopButton" value="Stop" />

		<div id="nowPlaying">
			<div id="trackInfo">
				<p><span class="label label-info">Now Playing</span></p>
			</div>
			<div id="npeInfo"></div>
		</div>

		<!-- Player placeholder -->
		<div id="td_container">Player</div>

	<?php

	}

	public static function load_js() {

		echo '<script src="http://player.listenlive.co/api/2.5/js/tdplayer.js"></script>';
		echo '<script>';
		echo 'var tdApiBaseUrl = \'http://api.listenlive.co/tdplayerapi/2.5/\'';
		echo '</script>';
		echo '<script type="text/javascript" charset="utf-8" src="http://api.listenlive.co/tdplayerapi/2.5/tdapi/modules/MediaPlayer.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="http://api.listenlive.co/tdplayerapi/2.5/tdapi/modules/Npe.js"></script>';
		echo '<script type="text/javascript" charset="utf-8" src="http://api.listenlive.co/tdplayerapi/2.5/tdapi/modules/mediaplayer/Html5.js"></script>';
		echo '<script data-dojo-config="onReady:window.tdPlayerApiReady, async: 1, tlmSiblingOfDojo: 0, deps:[\'tdapi/run\']" src="//api.listenlive.co/tdplayerapi/2.5/dojo/dojo.js"></script>';

	}

}

GMLP_Player::init();