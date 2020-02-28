import { call, takeLatest } from 'redux-saga/effects';
import {
	hideSplashScreen,
} from '../utilities/';
import { ACTION_HIDE_SPLASH_SCREEN } from '../actions/screen';

/**
 * @function yieldHideSplashScreen
 * Generator runs whenever [ ACTION_HIDE_SPLASH_SCREEN ]
 * is dispatched
 *
 * @param { Object } action Dispatched action
 */
function* yieldHideSplashScreen( action ) {

	console.log( 'yieldHideSplashScreen' );

	yield call( hideSplashScreen );

}

/**
 * @function watchHideSplashScreen
 * Generator used to bind action and callback
 */
export default function* watchHideSplashScreen() {
	yield takeLatest( [ ACTION_HIDE_SPLASH_SCREEN ], yieldHideSplashScreen );
}
