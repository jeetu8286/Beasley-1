import { call, takeLatest, select } from 'redux-saga/effects';
import { lyticsTrack } from '../../utilities';
import {
	ACTION_AUDIO_STOP,
} from '../../actions/player';

/**
 * @function yieldAudioStop
 * Generator runs whenever ACTION_AUDIO_STOP is dispatched
 */
function* yieldAudioStop() {

	console.log( 'yieldAudioStop' );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure from state
	const {
		trackType,
		duration,
		time,
		cuePoint,
		userInteraction,
	} = playerStore;

	// Get inlineAudioInterval from window, default null
	let { inlineAudioInterval = null } = window;

	// Clear interval
	if( inlineAudioInterval ) {
		yield call( [ window, 'clearInterval' ], inlineAudioInterval );
	}

	// Checks then call lyticsTrack
	if (
		trackType &&
		'podcast' === trackType &&
		1 >= Math.abs( duration - time ) &&
		!userInteraction &&
		'function' === typeof lyticsTrack
	) {
		yield call( lyticsTrack, 'end', cuePoint );
	}
}

/**
 * @function watchAudioStop
 * Generator used to bind action and callback
 */
export default function* watchAudioStop() {
	yield takeLatest( [ACTION_AUDIO_STOP], yieldAudioStop );
}
