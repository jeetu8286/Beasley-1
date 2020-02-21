// Import saga effects
import { put, takeLatest, select, call } from 'redux-saga/effects';

// Import action constant(s)
import {
	ACTION_AD_PLAYBACK_STOP,
} from '../actions/player';

/**
 * @function yieldAdPlaybackStop
 * Runs whenever ACTION_AD_PLAYBACK_STOP is dispatches
 *
 * @param {Object} action dispatched action
 * @param {Object} action.payload payload from dispatch
 */
function* yieldAdPlaybackStop( { payload } ) {

	console.log( 'yieldAdPlaybackStop' );

	// Destructure from payload
	const { actionType } = payload;

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure from playerStore
	const { adPlayback, station } = playerStore;

	// Destructure tdplayer from window
	const { tdplayer } = window; // Global player

	// Update DOM
	yield call( [ document.body.classList, 'remove' ], 'locked' );

	// If global tdplayer exists
	// TODO: Can't we just reference the state player?
	if( tdplayer ) {

		// If adPlayback and player.skipAd
		if(
			adPlayback &&
			'function' === typeof tdplayer.skipAd
		) {
			yield call( [ tdplayer, 'skipAd' ] );
		}

		// If station and player.skipAd
		if(
			station &&
			'function' === typeof tdplayer.play
		) {
			yield call( [ tdplayer, 'play' ], { station } );
		}

	}

	// finalize dispatch
	yield put( { type: actionType } );
}

/**
 * @function watchAdPlaybackStop
 * Watches for playback stop.
 */
export default function* watchAdPlaybackStop() {
	yield takeLatest( [ACTION_AD_PLAYBACK_STOP], yieldAdPlaybackStop );
}
