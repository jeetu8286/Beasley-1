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
 * Parses and dispatches the raw's HTML responde to the store
 *
 * @param {function} dispatch Redux dispatch function
 * @param {string} url The URL of the page
 * @param {object} response The page raw HTMl responde
 */
function parseHtmlToStore( dispatch, url, response ) {
	const urlSlugified = slugify( url );
	const parsed = parseHtml( response.html );
	const pageDocument = parsed.document;

	dispatch( {
		type: ACTION_LOADED_PAGE,
		url,
		...parsed,
		isHome: pageDocument.body.classList.contains( 'home' ),
	} );

	dispatch( {
		type: ACTION_HISTORY_HTML_SNAPSHOT,
		uuid: urlSlugified,
		data: response.html,
	} );

	return { urlSlugified, pageDocument };
}

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

		parseHtmlToStore( dispatch, url, response );

		scrollIntoView();
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

		// redirects.
		if ( [301, 302, 303,307, 308].includes( response.status ) ) {
			if ( response.redirect.url && ! response.redirect.internal ) {
				window.location.href = response.redirect;
			} else{
				// internal redirect
				dispatch( fetchPage( response.redirect.url, options ) );
			}

			return;
		}

		// unsuccessful status code.
		if ( 200 !== response.status && 201 !== response.status ) {
			dispatch( { type: ACTION_LOAD_ERROR } );
			return;
		}

		const { pageDocument } = parseHtmlToStore( dispatch, url, response );
		const { redirect } = response;

		scrollIntoView();

		// last step is update history, return early if it's not needed.
		if ( options.suppressHistory ) {
			return;
		}

		// we want to update history with the redirected url.
		if ( redirect && redirect.redirect_to ) {
			updateHistory( redirect.redirect_to, pageDocument.title );
		} else {
			// otherwise update history with the regular url.
			updateHistory( url, pageDocument.title );
		}

		updateDOM( pageDocument );

	} catch( error ) {
		dispatch( { type: ACTION_LOAD_ERROR, error } );
	}

};

/**
 * Parses the HTML and updated the current page.
 *
 * @param {string} Html of the page.
 */
export function updatePage( html ) {
	const parsed = parseHtml( html );
	const pageDocument = parsed.document;

	document.body.className = pageDocument.body.className;

	return {
		type: ACTION_LOADED_PAGE,
		force: true,
		...parsed,
	};
}

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
