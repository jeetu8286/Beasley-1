
import { call, takeLatest, put, select } from 'redux-saga/effects';
import { fullStop } from '../../utilities';
import {
	ACTION_PLAY_OMNY,
	ACTION_SET_PLAYER_TYPE,
	setPlayer,
} from '../../actions/player';

/**
 * @function yieldPlayOmny
 * Generator runs whenever ACTION_PLAY_OMNY is dispatched
 * NOTE: Omny doesn't support sound provider, thus we can't change/control volume :(
 *
 * @param {Object} action dispatched action
 * @param {String} action.player player from action
 */
function* yieldPlayOmny( { player } ) {

	console.log( 'yieldPlayOmny' );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Call fullStop
	yield call( fullStop, playerStore );

	// Update state player
	yield put( setPlayer( player, 'omnyplayer' ) );

	// Trigger play
	if(
		player &&
		'function' === typeof player.play
	) {
		yield call( [ player, 'play' ] );
	}

};

/**
 * @function watchPlayOmny
 * Generator used to bind action and callback
 */
export default function* watchPlayOmny() {
	yield takeLatest( [ACTION_PLAY_OMNY], yieldPlayOmny );
}
