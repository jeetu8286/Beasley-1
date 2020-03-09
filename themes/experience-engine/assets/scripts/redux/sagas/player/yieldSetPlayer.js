// Import saga effects
import { call, takeLatest, select, put } from 'redux-saga/effects';

// Import helper method(s)
import { loadNowPlaying, fullStop } from '../../utilities';

// Import action constant(s)
import {
	ACTION_SET_PLAYER,
} from '../../actions/player';

/**
 * @function yieldSetPlayer
 *
 * Generator runs whenever ACTION_SET_PLAYER is dispatched
 */
function* yieldSetPlayer() {

	console.log( 'yieldSetPlayer' );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// before setting a new player, let's ensure to stop any running player.
	// TODO: maybe dispath a PLAYER_STOP action.
	yield call( fullStop, playerStore );

	// Destructure
	const {
		volume,
		player,
	} = playerStore;

	// makes that whenever a new player is set, we recalculate the current volume.
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
 * @function watchSetPlayer
 *
 * Generator used to bind action and callback
 */
export default function* watchSetPlayer() {
	yield takeLatest( [ACTION_SET_PLAYER], yieldSetPlayer );
}
