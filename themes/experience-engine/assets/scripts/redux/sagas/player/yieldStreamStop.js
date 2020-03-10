import { call, takeLatest } from 'redux-saga/effects';
import {
	ACTION_STREAM_STOP,
} from '../../actions/player';

/**
 * @function yieldStreamStop
 * Generator runs whenever ACTION_STREAM_STOP is dispatched
 */
function* yieldStreamStop() {

	console.log( 'yieldStreamStop' );

	// Destructure from window
	const { liveStreamInterval = null } = window;

	// If global is set, run window clearInterval method
	if(
		liveStreamInterval
	) {
		yield call( [ window, 'clearInterval' ], liveStreamInterval );
	}
}

/**
 * @function watchStreamStop
 * Generator used to bind action and callback
 */
export default function* watchPause() {
	yield takeLatest( [ACTION_STREAM_STOP], yieldStreamStop );
}
