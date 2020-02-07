// Import saga effects
import { call, takeLatest, select } from 'redux-saga/effects';

// Import helper method(s)
import { loadNowPlaying } from '../reducers/player';

// Import action constant(s)
import {
	ACTION_INIT_TDPLAYER,
} from '../actions/player';

/**
 * @function yieldInitTdPlayer
 * Generator runs whenever ACTION_INIT_TDPLAYER is dispatched
 *
 * @param {Object} action dispatched action
 * @param {Object} action.player player in payload
 */
function* yieldInitTdPlayer( { player } ) {

	console.log( 'yieldInitTdPlayer' );

	// TODO: See if required globally - Set global instance
	window.tdplayer = player || null;

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure playerStore
	const { volume, station } = playerStore;

	// If player and volume, set volume
	if (
		player &&
		'function' === typeof player.setVolume
	) {
		yield call( [ player, 'setVolume' ], ( volume / 100 ) );
	}

	// If station, execute loadNowPlaying method
	if ( station ) {

		// Call loadNowPlaying and pass station and player
		yield call( loadNowPlaying, station, player );
	}
}

/**
 * @function watchInitTdPlayer
 * Generator used to bind action and callback
 */
export default function* watchInitTdPlayer() {
	yield takeLatest( [ACTION_INIT_TDPLAYER], yieldInitTdPlayer );
}
