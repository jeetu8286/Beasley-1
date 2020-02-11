import { call, takeLatest, select } from 'redux-saga/effects';
import { fullStop } from '../reducers/player';
import {
	ACTION_PLAY_AUDIO,
} from '../actions/player';

/**
 * @function yieldPlayAudio
 * Generator runs whenever ACTION_PLAY_AUDIO is dispatched
 *
 * @param {Object} action dispatched action
 * @param {Object} action.player player from action payload
 */
function* yieldPlayAudio( { player } ) {

	console.log( 'yieldPlayAudio' );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure playerStore
	const { volume = null } = playerStore;

	// Call fullStop method
	yield call( fullStop );

	// If player exists from dispatch
	if(
		player &&
		volume
	) {

		// Set global reference
		// TODO: Is global reference required? Reference player
		window.mp3player = player;

		// Set volume prop
		if( 'function' === typeof player.setVolume ) {
			yield call( [ player, 'setVolume' ], ( volume / 100 ) );
		}

		// Play
		if( 'function' === typeof player.play ) {
			yield call( [ player, 'play' ] );
		}
	}
}

/**
 * @function watchInitTdPlayer
 * Generator used to bind action and callback
 */
export default function* watchPlayAudio() {
	yield takeLatest( [ACTION_PLAY_AUDIO], yieldPlayAudio );
}
