import { removeChildren, dispatchEvent } from '../../library/dom';
import { getStateFromContent, parseHtml } from '../../library/html-parser';

export const ACTION_INIT_PAGE = 'ACTION_INIT_PAGE';
export const ACTION_LOADING_PAGE = 'ACTION_LOADING_PAGE';
export const ACTION_LOADED_PAGE = 'ACTION_LOADED_PAGE';

export const initPage = () => {
	const content = document.getElementById( 'content' );
	const results = getStateFromContent( content );

	// clean up content block for now, it will be poplated in the render function
	removeChildren( content );

	return {
		type: ACTION_INIT_PAGE,
		content: results.content,
		embeds: results.embeds,
	};
};

export const loadPage = ( url ) => ( dispatch ) => {
	const { history, location, pageXOffset, pageYOffset } = window;

	dispatch( {
		type: ACTION_LOADING_PAGE,
		url,
	} );

	const onError =  ( error ) => {
		console.error( error ); // eslint-disable-line no-console

		dispatch( {
			type: ACTION_LOADED_PAGE,
			content: '',
			embeds: [],
			error,
		} );
	};

	const onSuccess = ( response ) => response.text().then( ( data ) => {
		const results = parseHtml( data );
		const pageDocument = results.document;

		const currentState = Object.assign( {}, history.state, {
			pageXOffset,
			pageYOffset,
		} );

		const newState = {
			data,
			pageXOffset: 0,
			pageYOffset: 0,
		};

		history.replaceState( currentState, document.title, location.href );
		history.pushState( newState, pageDocument.title, url );

		dispatchEvent( 'pushstate' );

		document.title = pageDocument.title;
		document.body.className = pageDocument.body.className;

		dispatch( {
			type: ACTION_LOADED_PAGE,
			content: results.content,
			embeds: results.embeds,
			error: '',
		} );

		window.scrollTo( 0, 0 );
	} );

	fetch( url ).then( onSuccess ).catch( onError );
};

export const updatePage = ( data ) => {
	const results = parseHtml( data );

	const pageDocument = results.document;
	document.body.className = pageDocument.body.className;

	return {
		type: ACTION_LOADED_PAGE,
		content: results.content,
		embeds: results.embeds,
		error: '',
	};
};


export default {
	initPage,
	loadPage,
	updatePage,
};
