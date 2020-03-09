import { call, takeLatest } from 'redux-saga/effects';
import NProgress from 'nprogress';
import {
	manageBbgiConfig,
	hideSplashScreen,
} from '../../utilities';
import { ACTION_LOADED_PARTIAL } from '../../actions/screen';

/**
 * @function yieldLoadedPartial
 * Generator runs whenever [ ACTION_LOADED_PARTIAL ]
 * is dispatched
 *
 * @param { Object } action Dispatched action
 */
function* yieldLoadedPartial( action ) {

	console.log( 'yieldLoadedPartial' );

	// Destructure from action payload
	const { document: pageDocument } = action;

	// Call NProgress
	yield call( [ NProgress, 'done' ] );

	// Call manageBbgiConfig
	yield call( manageBbgiConfig, pageDocument );

	// Call hideSplashScreen
	yield call( hideSplashScreen );
}

/**
 * @function watchLoadedPartial
 * Generator used to bind action and callback
 */
export default function* watchLoadedPartial() {
	yield takeLatest( [ ACTION_LOADED_PARTIAL ], yieldLoadedPartial );
}
