import { call, takeLatest, select } from 'redux-saga/effects';
import { loadNowPlaying } from '../utilities/';
import {
	ACTION_AD_PLAYBACK_COMPLETE,
	ACTION_AD_PLAYBACK_ERROR,
} from '../actions/player';

/**
 * @function yieldAdPlaybackComplete
 * Generator runs whenever [ ACTION_AD_PLAYBACK_COMPLETE, ACTION_AD_PLAYBACK_ERROR ]
 * is dispatched
 */
function* yieldAdPlaybackComplete() {
	console.log( 'yieldAdPlaybackComplete' );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );

	// Call loadNowPlaying
	yield call( loadNowPlaying, playerStore );

}

/**
 * @function watchAdPlaybackComplete
 * Generator used to bind action and callback
 */
export default function* watchAdPlaybackComplete() {
	yield takeLatest( [ ACTION_AD_PLAYBACK_COMPLETE, ACTION_AD_PLAYBACK_ERROR ], yieldAdPlaybackComplete );
}
