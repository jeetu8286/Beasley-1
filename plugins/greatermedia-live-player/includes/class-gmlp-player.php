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
		<input type="button" data-action="play-live" data-station="<?php do_action( 'radio_callsign' ); ?>" value="player.play( { station:'WRORFM' } )"/>
		<input class="btn btn-primary" type="button" id="stopButton" value="player.stop()">
		<div class="well">
			<p>
				<b>NowPlayingApi</b>
			</p>
			<div class="btn-group">
				<div class="input-append">
					<input class="span2" placeholder="Enter a Callsign" id="songHistoryCallsignUser" type="text">
					<select id="songHistoryConnectionTypeSelect" name="songHistoryConnectionTypeSelect">
						<option value="normalConnection">normalConnection</option>
						<option value="hdConnection">hdConnection (AAC)</option>
					</select>
					<button class="btn" type="button" id="fetchSongHistoryByUserCallsignButton">Get Song History</button>
				</div>
			</div>
		</div>

		<div id="nowPlaying" style="float:left; width:330px; height:250px;">
			<div id="trackInfo">
				<p><span class="label label-info">Now Playing</span></p>
			</div>
			<div id="npeInfo"></div>
		</div>

		<!-- Player placeholder -->
		<div id="td_container" style="border:1px dashed black; float:left; width:300px; height:250px;">Player</div>


	<?php

	}

	public static function load_js() {

		?>

		<script src="http://player.listenlive.co/api/2.5/js/jquery-1.7.2.min.js"></script>

		<script src="http://player.listenlive.co/api/2.5/js/tdplayer.js"></script>
		<script>
			var tdApiBaseUrl = 'http://api.listenlive.co/tdplayerapi/2.5/';
		</script>
		<script type="text/javascript" charset="utf-8" src="http://api.listenlive.co/tdplayerapi/2.5/tdapi/modules/MediaPlayer.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://api.listenlive.co/tdplayerapi/2.5/tdapi/modules/Npe.js"></script>
		<script type="text/javascript" charset="utf-8" src="http://api.listenlive.co/tdplayerapi/2.5/tdapi/modules/mediaplayer/Html5.js"></script>
		<script data-dojo-config="onReady:window.tdPlayerApiReady, async: 1, tlmSiblingOfDojo: 0, deps:['tdapi/run']" src="//api.listenlive.co/tdplayerapi/2.5/dojo/dojo.js"></script>


	<?php

	}

}

GMLP_Player::init();