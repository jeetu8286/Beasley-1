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
	] );
}
