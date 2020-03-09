import { all } from 'redux-saga/effects';
import {
	watchInitTdPlayer,
	watchPlayAudio,
	watchPlayStation,
	watchPlayOmny,
	watchPause,
	watchResume,
	watchSetVolume,
	watchCuePointChange,
	watchSeekPosition,
	watchStreamStart,
	watchStreamStop,
	watchAudioStart,
	watchAudioStop,
	watchAdPlaybackStart,
	watchAdPlaybackComplete,
	watchAdPlaybackStop,
	watchInitPage,
	watchLoadingPage,
	watchLoadedPage,
	watchLoadedPartial,
	watchHideSplashScreen,
} from './sagas/';

/**
 * Root saga that watches for side effects.
 */
export default function* rootSaga() {
	yield all( [
		watchInitTdPlayer(),
		watchPlayAudio(),
		watchPlayStation(),
		watchPlayOmny(),
		watchPause(),
		watchResume(),
		watchSetVolume(),
		watchCuePointChange(),
		watchSeekPosition(),
		watchStreamStart(),
		watchStreamStop(),
		watchAudioStart(),
		watchAudioStop(),
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
