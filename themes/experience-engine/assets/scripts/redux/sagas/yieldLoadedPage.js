import { call, put, takeLatest, select } from 'redux-saga/effects';
import NProgress from 'nprogress';
import {
	manageScripts,
	manageBbgiConfig,
	hideSplashScreen,
	updateTargeting,
} from '../utilities/';
import { ACTION_LOADED_PAGE, ACTION_HISTORY_HTML_SNAPSHOT } from '../actions/screen';
import { slugify, parseHtml } from '../../library';

/**
 * Scrolls to the top of content.
 */
function scrollIntoView() {
	// Get content container
	const content = document.getElementById( 'content' );

	// Scroll to top of content
	if( content ) {
		content.scrollIntoView( true );
	}
}

/**
 * Updates window.history with new url and title
 *
 * @param {string} url The URL to update history with
 * @param {object} pageDocument
 */
function updateHistory( url, title ) {
	const { history, location, pageXOffset, pageYOffset } = window;
	const uuid = slugify( url );

	history.replaceState(
		{ ...history.state, pageXOffset, pageYOffset },
		document.title,
		location.href,
	);
	history.pushState(
		{ uuid, pageXOffset: 0, pageYOffset: 0 },
		title,
		url,
	);

	dispatchEvent( 'pushstate' );
}

/**
 * Updates DOM related stuff for the loaded page document.
 *
 * @param {object} pageDocument
 */
function updateDOM( pageDocument ) {
	document.title = pageDocument.title;
	document.body.className = pageDocument.body.className;
}

/**
 * @function yieldLoadedPage
 * Generator runs whenever [ ACTION_LOADED_PAGE ]
 * is dispatched
 *
 * @param { Object } action Dispatched action
 */
function* yieldLoadedPage( action ) {
	console.log( 'yieldLoadedPage' );

	const { url, response, options } = action.payload;

	const urlSlugified = slugify( url );
	const parsed = parseHtml( response.html );
	const pageDocument = parsed.document;

	// Screen store from state
	const screenStore = yield select( ( { screen } ) => screen );

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

	// dispatch history html snapshot
	yield put( {
		type: ACTION_HISTORY_HTML_SNAPSHOT,
		uuid: urlSlugified,
		data: response.html,
	} );

	// Call NProgress
	yield call( [ NProgress, 'done' ] );

	// Call manageScripts
	yield call( manageScripts, parsed.scripts, screenStore.scripts );

	yield call( scrollIntoView );

	// Call hideSplashScreen
	yield call( hideSplashScreen );

	// last step is update history, return early if it's not needed.
	if ( options.suppressHistory ) {
		return;
	}

	yield call( updateHistory, url, pageDocument.title );

	yield call( updateDOM, pageDocument );
}


/**
 * @function watchLoadedPage
 * Generator used to bind action and callback
 */
export default function* watchLoadedPage() {
	yield takeLatest( [ ACTION_LOADED_PAGE ], yieldLoadedPage );
}
