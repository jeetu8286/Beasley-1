import { call, takeLatest, put, delay } from 'redux-saga/effects';
import { isAudioAdOnly } from '../../library/strings';
import {
	ACTION_AD_PLAYBACK_START,
	ACTION_AD_PLAYBACK_STOP,
	ACTION_AD_PLAYBACK_ERROR,
} from '../actions/player';

/**
 * @function yieldAdPlaybackStart
 * Generator runs whenever ACTION_AD_PLAYBACK_START is dispatched
 */
function* yieldAdPlaybackStart() {

	console.log( 'yieldAdPlaybackStart' );

	// Check for falsey isAudioAdOnly
	if ( !isAudioAdOnly() ) {

		// Add class to body
		yield call( [ document.body.classList, 'add' ], 'locked' );
	}

	// Start timer for ad playback stop
	yield call( beginAdPlaybackStopTimer );
}

/**
 * @function beginAdPlaybackStopTimer
 */
export function* beginAdPlaybackStopTimer() {

	console.log( 'beginAdPlaybackStopTimer' );

	// This needs to dispatch to the stopPlayback action
	// after 70000ms
	yield delay( 70000 );

	// After delay, put new action
	yield put( {
		type: ACTION_AD_PLAYBACK_STOP,
		payload: {
			actionType: ACTION_AD_PLAYBACK_ERROR,
		},
	} );
}

/**
 * @function watchAdPlaybackStart
 * Generator used to bind action and callback
 */
export default function* watchAdPlaybackStart() {
	yield takeLatest( [ACTION_AD_PLAYBACK_START], yieldAdPlaybackStart );
}
