import {
	removeChildren,
	dispatchEvent,
	getStateFromContent,
	parseHtml,
	pageview,
	slugify,
} from '../../library';

export const ACTION_INIT_PAGE = 'PAGE_INIT';
export const ACTION_LOADING_PAGE = 'PAGE_LOADING';
export const ACTION_LOADED_PAGE = 'PAGE_LOADED';
export const ACTION_LOADING_PARTIAL = 'PARTIAL_LOADING';
export const ACTION_LOADED_PARTIAL = 'PARTIAL_LOADED';
export const ACTION_LOAD_ERROR = 'LOADING_ERROR';
export const ACTION_HIDE_SPLASH_SCREEN = 'HIDE_SPLASH_SCREEN';
export const ACTION_UPDATE_NOTICE = 'UPDATE_NOTICE ';
export const ACTION_HISTORY_HTML_SNAPSHOT = 'HISTORY_HTML_SNAPSHOT';

/**
 * Parses the current content blocks for redux.
 */
export function initPage() {
	const content = document.getElementById( 'content' );
	const parsed = getStateFromContent( content );

	// clean up content block for now, it will be poplated in the render function
	removeChildren( content );

	return { type: ACTION_INIT_PAGE, ...parsed };
}

/**
 * Sets the html snapshot of the given page.
 *
 * @param {string} uuid A slugifyed representation of the url
 * @param {string} html The html of the page.
 */
export function initPageLoaded( uuid, html ) {
	return { type: ACTION_HISTORY_HTML_SNAPSHOT, uuid, html };
}

/**
 * Fetches the feed content for a user.
 *
 * @param {string} token Firease ID token
 * @param {string} url   Optional URL to associate the feeds content to.
 */
export const fetchFeedsContent = ( token, url = 'feeds-content' ) => async dispatch => {
	dispatch( { type: ACTION_LOADING_PAGE, url } );

	try {
		const response = await fetch(
			`${window.bbgiconfig.wpapi}feeds-content?device=other`,
			{
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: `authorization=${encodeURIComponent( token )}`,
			},
		).then( res => res.json() );

		dispatch( { type: ACTION_LOADED_PAGE, payload: { url, response, options: { suppressHistory: true } }} );
	} catch( error ) {
		dispatch( { type: ACTION_LOAD_ERROR, error } );
	}
};

/**
 * Fetches a page by calling the page endpoint.
 *
 * @param {string} url
 */
export const fetchPage = ( url, options = {} ) => async dispatch => {
	const pageEndpoint = `${window.bbgiconfig.wpapi}\page?url=${encodeURIComponent( url )}`;

	try {
		dispatch( { type: ACTION_LOADING_PAGE, url } );

		const response = await fetch( pageEndpoint ).then( response => response.json() );
		const { redirect } = response;

		// redirects.
		if ( [301, 302, 303,307, 308].includes( response.status ) ) {
			if ( redirect.url && ! redirect.internal ) {
				window.location.href = response.redirect;
			} else {
				// internal redirect
				dispatch( fetchPage( redirect.url, options ) );
			}

			return;
		}

		// unsuccessful status code.
		if ( 200 !== response.status && 201 !== response.status ) {
			dispatch( { type: ACTION_LOAD_ERROR } );
			return;
		}

		dispatch( { type: ACTION_LOADED_PAGE, payload: { url, response, options }} );
	} catch( error ) {
		dispatch( { type: ACTION_LOAD_ERROR, error } );
	}

};

/**
 * Loads a partial page (e.g for LoadMode)
 *
 * @param {string} url
 * @param {*} placeholder
 */
export function loadPartialPage( url, placeholder ) {
	return dispatch => {
		dispatch( { type: ACTION_LOADING_PARTIAL, url } );

		function onError( error ) {
			// eslint-disable-next-line no-console
			console.error( error );
			dispatch( { type: ACTION_LOAD_ERROR, error } );
		}

		function onSuccess( data ) {
			const parsed = parseHtml( data, '#inner-content' );
			dispatch( { type: ACTION_LOADED_PARTIAL, url, ...parsed, placeholder } );
			pageview( parsed.document.title, url );
		}

		fetch( url )
			.then( response => response.text() )
			.then( onSuccess )
			.catch( onError );
	};
}

/**
 * Hides the splash screen
 */
export function hideSplashScreen() {
	return { type: ACTION_HIDE_SPLASH_SCREEN };
}

/**
 * Updates the Notice component message.
 */
export function updateNotice( { isOpen, message } ) {
	return {
		type: ACTION_UPDATE_NOTICE,
		force: true,
		isOpen,
		message,
	};
}

export default {
	hideSplashScreen,
	initPage,
	initPageLoaded,
	loadPartialPage,
	updateNotice,
};
