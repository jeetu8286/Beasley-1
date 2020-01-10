import { all, put, takeLatest, select } from 'redux-saga/effects';
import { ACTION_AD_PLAYBACK_STOP } from './actions/player';

/**
 * Runs whenever ACTION_AD_PLAYBACK_STOP is dispatches
 */
function* yieldPlaybackStop( action ) {
	const player = yield select( store => store.player );
	const { actionType } = action.payload;
	console.log( 'saga playback stop', player, actionType );
	const { tdplayer } = window; // Global player

	// Update DOM
	document.body.classList.remove( 'locked' );

	// If there is a tdplayer and player in state
	// then continue this portion
	if( tdplayer && player ) {
		console.log( player );

		if ( player.adPlayback ) {
			tdplayer.skipAd();
		}

		if( player.station ) {
			tdplayer.play( { station: player.station } );
		}

		// finalize dispatch
		yield put( {type: actionType} );
	}
}

/**
 * Watches for playback stop.
 */
function* watchPlaybackStop() {
	yield takeLatest( [ACTION_AD_PLAYBACK_STOP], yieldPlaybackStop );
}

/**
 * Root saga that watches for side effects.
 */
function* rootSaga() {
	yield all( [
		watchPlaybackStop(),
	] );
}

export default rootSaga;
