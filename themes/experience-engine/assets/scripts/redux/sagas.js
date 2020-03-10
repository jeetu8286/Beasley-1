import { all } from 'redux-saga/effects';
import {
	watchSetPlayer,
	watchStart,
	watchStop,
	watchPause,
	watchResume,
	watchSetVolume,
	watchCuePointChange,
	watchSeekPosition,
	watchAdPlaybackStart,
	watchAdPlaybackComplete,
	watchAdPlaybackStop,
	watchInitPage,
	watchLoadingPage,
	watchLoadedPage,
	watchLoadedPartial,
	watchHideSplashScreen,
} from './sagas/';
import watchPlay from './sagas/player/yieldPlay';

/**
 * Root saga that watches for side effects.
 */
export default function* rootSaga() {
	yield all( [
		watchSetPlayer(),
		watchStart(),
		watchPlay(),
		watchStop(),
		watchPause(),
		watchResume(),
		watchSetVolume(),
		watchCuePointChange(),
		watchSeekPosition(),
		watchAdPlaybackStart(),
		watchAdPlaybackComplete(),
		watchAdPlaybackStop(),
		watchInitPage(),
		watchLoadingPage(),
		watchLoadedPage(),
		watchLoadedPartial(),
		watchHideSplashScreen(),
	] );
}
