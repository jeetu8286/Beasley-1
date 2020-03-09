/**
 * @function loadNowPlaying
 * Used to load a configuration to the NowPlaying API
 * TODO: Is this asynchronous??? Need docs
 *
 * @param {String} station Station identifier
 * @param {Object} player Player instance
 */
export default function loadNowPlaying( { station = null, player = null, playerType = null } ) {

	console.log( 'loadNowPlaying', player, playerType, station );

	// If not tdplayer type, abandon
	// Only tdplayer contains the NowPlayingApi
	if (
		!playerType ||
		'tdplayer' !== playerType
	) {
		return;
	}

	// If station and player
	// If player.NowPlayingApi
	// If load function exists
	if (
		station &&
		player &&
		player.NowPlayingApi &&
		'function' === typeof player.NowPlayingApi.load
	) {
		console.log( 'has station and player, fire player.NowPlayingApi' );
		player.NowPlayingApi.load( { numberToFetch: 10, mount: station } );
	}
}
