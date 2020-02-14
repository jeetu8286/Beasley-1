/**
 * @function loadNowPlaying
 * Used to load a configuration to the NowPlaying API
 * TODO: Is this asynchronous??? Need docs
 *
 * @param {Object} player Player instance
 * @param {String} station Station identifier
 */
export default function loadNowPlaying( station, player ) {

	// Destructure from window
	const {
		omnyplayer,
		mp3player,
	} = window;

	if ( station && player && !omnyplayer && !mp3player ) {
		player.NowPlayingApi.load( { numberToFetch: 10, mount: station } );
	}
}
