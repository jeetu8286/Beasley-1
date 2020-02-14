import { call, takeLatest, select } from 'redux-saga/effects';
import { lyticsTrack } from '../utilities/';
import {
	ACTION_PAUSE,
} from '../actions/player';

/**
 * @function yieldPause
 * Generator runs whenever ACTION_PAUSE is dispatched
 */
function* yieldPause() {

	console.log( 'yieldPause' );

	// Get player from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure from playerStore in state
	const {
		trackType,
		cuePoint,
	} = playerStore;

	// Destructure from window
	const {
		mp3player,
		omnyplayer,
		tdplayer,
	} = window;

	// If mp3player
	if (
		mp3player &&
		'function' === typeof mp3player.pause
	) {
		yield call( [ mp3player, 'pause' ] );

	// Else if omnyplayer
	} else if (
		omnyplayer &&
		'function' === typeof omnyplayer.pause
	) {
		yield call( [ omnyplayer, 'pause' ] );

	// Else if tdplayer
	} else if (
		tdplayer &&
		'function' === typeof tdplayer.stop
	) {
		yield call( [ tdplayer, 'stop' ] );
	}

	if (
		cuePoint &&
		'podcast' === trackType &&
		'function' === typeof lyticsTrack
	) {
		yield call( lyticsTrack, 'pause', cuePoint );
	}
}

/**
 * @function watchPause
 * Generator used to bind action and callback
 */
export default function* watchPause() {
	yield takeLatest( [ACTION_PAUSE], yieldPause );
}

