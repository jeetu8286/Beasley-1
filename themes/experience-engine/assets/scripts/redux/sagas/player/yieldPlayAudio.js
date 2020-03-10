import { call, takeLatest, select, put } from 'redux-saga/effects';
import { fullStop } from '../../utilities';
import {
	ACTION_PLAY_AUDIO,
	setPlayer,
} from '../../actions/player';

/**
 * @function yieldPlayAudio
 * Generator runs whenever ACTION_PLAY_AUDIO is dispatched
 */
function* yieldPlayAudio( { player } ) {

	console.log( 'yieldPlayAudio' );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure playerStore
	const {
		volume = null,
	} = playerStore;

	// Call fullStop method
	yield call( fullStop, playerStore );

	// Update state player
	yield put( setPlayer( player, 'mp3player' ) );

	// If player and volume
	if(
		player &&
		volume
	) {

		// Set volume prop
		if( 'function' === typeof player.setVolume ) {
			yield call( [ player, 'setVolume' ], ( volume / 100 ) );
		}

		// Play
		if( 'function' === typeof player.play ) {
			console.log( 'yes we hit the play method---------------' );
			yield call( [ player, 'play' ] );
		}
	}
}

/**
 * @function watchPlayAudio
 * Generator used to bind action and callback
 */
export default function* watchPlayAudio() {
	yield takeLatest( [ACTION_PLAY_AUDIO], yieldPlayAudio );
}
