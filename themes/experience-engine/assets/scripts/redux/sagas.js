// Sagas Controller

import { all } from 'redux-saga/effects';
import {
	watchInitPage,
	watchLoadingPage,
} from './sagas/';

/**
 * Root saga that watches for side effects.
 */
export default function* rootSaga() {
	yield all( [
		watchInitPage(),
		watchLoadingPage(),
	] );
}
