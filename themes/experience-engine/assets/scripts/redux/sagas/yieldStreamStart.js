import { call, takeLatest } from 'redux-saga/effects';
import {
	sendLiveStreamPlaying,
} from '../../library/google-analytics';
import {
	ACTION_STREAM_START,
} from '../actions/player';

/**
 * @function yieldStreamStart
 * Generator runs whenever ACTION_STREAM_START is dispatched
 */
function* yieldStreamStart() {

	console.log( 'yieldStreamStart' );

	// Get interval from global
	const interval = window.bbgiconfig.intervals.live_streaming;

	// Get liveStreamInterval from window, default null
	let { liveStreamInterval = null } = window;

	// If interval
	if (
		interval &&
		0 < interval
	) {

		// Clear if set
		if( liveStreamInterval ) {
			yield call( [ window, 'clearInterval' ], liveStreamInterval );
		}

		// Set liveStreamInterval
		liveStreamInterval = yield call ( [ window, 'setInterval' ], function() { sendLiveStreamPlaying(); }, interval * 60 * 1000 );
	}
}

/**
 * @function watchStreamStart
 * Generator used to bind action and callback
 */
export default function* watchStreamStart() {
	yield takeLatest( [ACTION_STREAM_START], yieldStreamStart );
}
