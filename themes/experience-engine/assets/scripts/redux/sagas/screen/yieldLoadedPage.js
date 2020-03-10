import { call, put, takeLatest, select } from 'redux-saga/effects';
import NProgress from 'nprogress';
import {
	manageScripts,
	manageBbgiConfig,
	hideSplashScreen,
	updateTargeting,
} from '../../utilities';
import { ACTION_LOADED_PAGE, ACTION_HISTORY_HTML_SNAPSHOT } from '../../actions/screen';
import { slugify, dispatchEvent } from '../../../library';

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
 * @function yieldLoadedPage
 * Generator runs whenever [ ACTION_LOADED_PAGE ]
 * is dispatched
 *
 * @param { Object } action Dispatched action
 */
function* yieldLoadedPage( action ) {
	const { url, response, options, parsedHtml } = action;

	const urlSlugified = slugify( url );
	const pageDocument = parsedHtml.document;

	// Screen store from state
	const screenStore = yield select( ( { screen } ) => screen );

	// Update BBGI Config
	yield call( manageBbgiConfig, pageDocument );

	// Update Ad Targeting
	yield call( updateTargeting );

	// Fix WP Admin Bar
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

	// Start the loading progress bar.
	yield call( [ NProgress, 'done' ] );

	// Update Scripts.
	yield call( manageScripts, parsedHtml.scripts, screenStore.scripts );

	// make sure the user scroll bar is into view.
	yield call( scrollIntoView );

	// make sure to hide splash screen.
	yield call( hideSplashScreen );

	// last step is update history, return early if it's not needed.
	if ( options.suppressHistory ) {
		return;
	}

	yield call( updateHistory, url, pageDocument.title );

	document.title = pageDocument.title;
	document.body.className = pageDocument.body.className;
}


/**
 * @function watchLoadedPage
 * Generator used to bind action and callback
 */
export default function* watchLoadedPage() {
	yield takeLatest( [ ACTION_LOADED_PAGE ], yieldLoadedPage );
}
