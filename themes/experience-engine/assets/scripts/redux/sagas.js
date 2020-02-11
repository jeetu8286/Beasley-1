// Sagas Controller

import { all } from 'redux-saga/effects';
import {
	watchPlaybackStop,
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
} from './sagas/';

/**
 * Root saga that watches for side effects.
 */
export default function* rootSaga() {
	yield all( [
		watchPlaybackStop(),
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
	] );
}
