/**
 * @function fullStop
 * Stop all players (mp3Player, omnyplayer and tdplayer)
 */
export default function fullStop() {

	// Destructure from window
	let {
		tdplayer,
		mp3player,
		omnyplayer,
	} = window;

	// If mp3player
	if ( mp3player ) {
		mp3player.pause();
		mp3player = null;
	}

	// If omnyplayer
	if ( omnyplayer ) {
		omnyplayer.off( 'ready' );
		omnyplayer.off( 'play' );
		omnyplayer.off( 'pause' );
		omnyplayer.off( 'ended' );
		omnyplayer.off( 'timeupdate' );
		omnyplayer.pause();
		omnyplayer.elem.parentNode.removeChild( omnyplayer.elem );
		omnyplayer = null;
	}

	// If tdplayer
	if ( tdplayer ) {
		tdplayer.stop();
		tdplayer.skipAd();
	}
}
