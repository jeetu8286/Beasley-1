import { call, takeLatest, select } from 'redux-saga/effects';
import { lyticsTrack } from '../utilities/';
import {
	ACTION_RESUME,
} from '../actions/player';

/**
 * @function yieldResume
 * Generator runs whenever ACTION_RESUME is dispatched
 */
function* yieldResume() {

	console.log( 'yieldResume' );

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
		'function' === typeof mp3player.play
	) {
		yield call( [ mp3player, 'play' ] );

	// Else if omnyplayer
	} else if (
		omnyplayer &&
		'function' === typeof omnyplayer.play
	) {
		yield call( [ omnyplayer, 'play' ] );

	// Else if tdplayer
	} else if (
		tdplayer &&
		'function' === typeof tdplayer.resume
	) {
		yield call( [ tdplayer, 'resume' ] );
	}

	if (
		cuePoint &&
		'podcast' === trackType &&
		'function' === typeof lyticsTrack
	) {
		yield call( lyticsTrack, 'play', cuePoint );
	}
}

/**
 * @function watchResume
 * Generator used to bind action and callback
 */
export default function* watchResume() {
	yield takeLatest( [ACTION_RESUME], yieldResume );
}

