// Import saga effects
import { call, takeLatest, select, put } from 'redux-saga/effects';

// Import helper method(s)
import { loadNowPlaying } from '../utilities/';

// Import action constant(s)
import {
	ACTION_INIT_TDPLAYER,
	ACTION_SET_PLAYER_TYPE,
} from '../actions/player';

/**
 * @function yieldInitTdPlayer
 * Generator runs whenever ACTION_INIT_TDPLAYER is dispatched
 */
function* yieldInitTdPlayer() {

	console.log( 'yieldInitTdPlayer' );

	// Store player type in state
	yield put( { type: ACTION_SET_PLAYER_TYPE, payload: 'tdplayer' } );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure
	const {
		volume,
		player,
	} = playerStore;


	// If player and volume, set volume
	if (
		player &&
		'function' === typeof player.setVolume
	) {
		yield call( [ player, 'setVolume' ], ( volume / 100 ) );
	}

	// Call loadNowPlaying
	yield call( loadNowPlaying, playerStore );
}

/**
 * @function watchInitTdPlayer
 * Generator used to bind action and callback
 */
export default function* watchInitTdPlayer() {
	yield takeLatest( [ACTION_INIT_TDPLAYER], yieldInitTdPlayer );
}
