import { call, takeLatest } from 'redux-saga/effects';
import { ACTION_GAM_AD_PLAYBACK_START } from '../../actions/player';

/**
 * @function yieldAdPlaybackStart
 * Generator runs whenever ACTION_AD_PLAYBACK_START is dispatched
 */
function* yieldGamAdPlaybackStart() {
	// Add class to body
	yield call([document.body.classList, 'add'], 'locked');
}

/**
 * @function watchAdPlaybackStart
 * Generator used to bind action and callback
 */
export default function* watchGamAdPlaybackStart() {
	yield takeLatest([ACTION_GAM_AD_PLAYBACK_START], yieldGamAdPlaybackStart);
}
