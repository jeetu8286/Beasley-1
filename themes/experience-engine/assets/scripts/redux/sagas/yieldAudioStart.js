import { call, takeLatest, select } from 'redux-saga/effects';
import {
	sendInlineAudioPlaying,
} from '../../library/google-analytics';
import {
	ACTION_AUDIO_START,
} from '../actions/player';

/**
 * @function yieldAudioStart
 * Generator runs whenever ACTION_AUDIO_START is dispatched
 */
function* yieldAudioStart() {

	console.log( 'yieldAudioStart' );

	// Get interval from global
	const interval = window.bbgiconfig.intervals.live_streaming;

	// Get inlineAudioInterval from window, default null
	let { inlineAudioInterval = null } = window;

	// If interval
	if (
		interval &&
		0 < interval
	) {

		// Clear if set
		if( inlineAudioInterval ) {
			yield call( [ window, 'clearInterval' ], inlineAudioInterval );
		}

		// Set inlineAudioInterval
		inlineAudioInterval = yield call( [ window, 'setInterval' ], function() { sendInlineAudioPlaying(); }, interval * 60 * 1000 );
	}
}

/**
 * @function watchAudioStart
 * Generator used to bind action and callback
 */
export default function* watchAudioStart() {
	yield takeLatest( [ACTION_AUDIO_START], yieldAudioStart );
}
