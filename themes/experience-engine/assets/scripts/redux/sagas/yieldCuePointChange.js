import { call, takeLatest, select } from 'redux-saga/effects';
import {
	loadNowPlaying,
	lyticsTrack,
} from '../reducers/player';
import {
	ACTION_CUEPOINT_CHANGE,
} from '../actions/player';

/**
 * @function yieldCuePointChange
 * Generator runs whenever ACTION_CUEPOINT_CHANGE is dispatched
 * NOTE: Omny doesn't support sound provider, thus we can't change/control volume :(
 *
 * @param {Object} action dispatched action
 * @param {String} action.cuePoint cuePoint from action
 */
function* yieldCuePointChange( { cuePoint } ) {

	console.log( 'yieldCuePointChange' );

	// Get player from state
	const playerStore = yield select( ( { player } ) => player );

	// Destructure
	const {
		station,
		trackType,
	} = playerStore;

	// If station
	if( station ) {
		yield call( loadNowPlaying, station );
	}

	// If action passes cuePoint
	// If trackType in state is podcast
	// If lyticsTrack has a play method
	if (
		cuePoint &&
		'podcast' === trackType &&
		'function' === typeof lyticsTrack.play
	) {
		// TODO: Look at storing UI state in Redux Store
		window.userInteraction = false;

		// Call lyticsTrack
		yield call( [ lyticsTrack, 'play' ], cuePoint );
	}
}

/**
 * @function watchCuePointChange
 * Generator used to bind action and callback
 */
export default function* watchCuePointChange() {
	yield takeLatest( [ACTION_CUEPOINT_CHANGE], yieldCuePointChange );
}
