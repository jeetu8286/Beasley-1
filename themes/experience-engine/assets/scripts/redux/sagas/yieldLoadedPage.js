import { call, takeLatest, select } from 'redux-saga/effects';
import NProgress from 'nprogress';
import {
	manageScripts,
	manageBbgiConfig,
	hideSplashScreen,
	updateTargeting,
} from '../utilities/';
import { ACTION_LOADED_PAGE } from '../actions/screen';

/**
 * @function yieldLoadedPage
 * Generator runs whenever [ ACTION_LOADED_PAGE ]
 * is dispatched
 *
 * @param { Object } action Dispatched action
 */
function* yieldLoadedPage( action ) {

	console.log( 'yieldLoadedPage' );

	// Screen store from state
	const screenStore = yield select( ( { screen } ) => screen );

	// Destructure from action
	const { document: pageDocument } = action;

	// Call manageBbgiConfig
	yield call( manageBbgiConfig, pageDocument );

	// Call updateTargeting
	yield call( updateTargeting );

	// DOM mods
	if ( pageDocument ) {
		const barId = 'wpadminbar';
		const wpadminbar = document.getElementById( barId );
		if ( wpadminbar ) {
			const newbar = pageDocument.getElementById( barId );
			if ( newbar ) {
				wpadminbar.parentNode.replaceChild( newbar, wpadminbar );
			}
		}
	}

	// Call NProgress
	yield call( [ NProgress, 'done' ] );

	// Call manageScripts
	yield call( manageScripts, action.scripts, screenStore.scripts );

	// Call hideSplashScreen
	yield call( hideSplashScreen );
}


/**
 * @function watchLoadedPage
 * Generator used to bind action and callback
 */
export default function* watchLoadedPage() {
	yield takeLatest( [ ACTION_LOADED_PAGE ], yieldLoadedPage );
}
