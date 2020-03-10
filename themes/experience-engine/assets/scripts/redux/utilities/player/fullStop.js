/**
 * @function fullStop
 * Stop all players (mp3Player, omnyplayer and tdplayer)
 *
 * @param {object} playerStore
 * @param {player} playerStore.player - Actual player object to interface with
 * @param {string} playerStore.playerType - Type of player (omnyplayer, tdplayer, mp3player)
 */
export default function fullStop( { player, playerType } ) {

	// If no player or type, abandon
	if (
		!player ||
		!playerType
	) {
		return;
	}

	// If mp3player
	if ( 'mp3player' === playerType ) {
		player.pause();
	}

	// If omnyplayer
	if ( 'omnyplayer' === playerType ) {
		player.off( 'ready' );
		player.off( 'play' );
		player.off( 'pause' );
		player.off( 'ended' );
		player.off( 'timeupdate' );
		player.pause();

		// TODO: needs testing here
		player.elem.parentNode.removeChild( player.elem );
	}

	// If tdplayer
	if ( 'tdplayer' === playerType ) {
		player.stop();
		player.skipAd(); // TODO: No null player here though???
	}
}
