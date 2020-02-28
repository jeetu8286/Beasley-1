/**
 * @function loadNowPlaying
 * Used to load a configuration to the NowPlaying API
 * TODO: Is this asynchronous??? Need docs
 *
 * @param {String} station Station identifier
 * @param {Object} player Player instance
 */
export default function loadNowPlaying( station, player ) {

	console.log( 'loadNowPlaying', player );

	// Destructure from window
	const {
		omnyplayer,
		mp3player,
	} = window;

	if ( station && player && !omnyplayer && !mp3player ) {
		console.log( 'has station and player, fire player.NowPlayingApi' );
		player.NowPlayingApi.load( { numberToFetch: 10, mount: station } );
	}
}
