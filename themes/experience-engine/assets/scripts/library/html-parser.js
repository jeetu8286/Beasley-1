let embedsIndex = 0;

const getSecondStreetEmbedParams = ( element ) => ( {
	script: element.getAttribute( 'src' ),
	embed: element.getAttribute( 'data-ss-embed' ),
	opguid: element.getAttribute( 'data-opguid' ),
	routing: element.getAttribute( 'data-routing' ),
} );

const getAudioEmbedParams = ( element ) => {
	const sources = {};
	const tags = element.getElementsByTagName( 'source' );
	for ( let i  = 0, len = tags.length; i < len; i++ ) {
		sources[tags[i].getAttribute( 'src' )] = tags[i].getAttribute( 'type' );
	}

	return {
		src: element.getAttribute( 'src' ) || '',
		sources,
	};
};

const getOmnyEmbedParams = ( element ) => ( {
	src: element.getAttribute( 'src' ),
	title: element.getAttribute( 'data-title' ),
	author: element.getAttribute( 'data-author' ),
	omny: true,
} );

const getLazyImageParams = ( element ) => ( {
	src: element.getAttribute( 'data-src' ),
	width: element.getAttribute( 'data-width' ),
	height: element.getAttribute( 'data-height' ),
	aspect: element.getAttribute( 'data-aspect' ),
} );

const processEmbeds = ( container, type, selector, callback ) => {
	const embeds = [];

	const elements = container.querySelectorAll( selector );
	for ( let i = 0, len = elements.length; i < len; i++ ) {
		const element = elements[i];
		const placeholder = document.createElement( 'div' );

		placeholder.setAttribute( 'id', `__cd-${++embedsIndex}` );

		embeds.push( {
			type,
			params: {
				placeholder: placeholder.getAttribute( 'id' ),
				...callback( element ),
			},
		} );

		element.parentNode.replaceChild( placeholder, element );
	}

	return embeds;
};

export const getStateFromContent = ( container ) => {
	const state = {
		embeds: [],
		content: '',
	};

	if ( container ) {
		state.embeds = [
			...processEmbeds( container, 'secondstreet', '.secondstreet-embed', getSecondStreetEmbedParams ),
			...processEmbeds( container, 'audio', '.wp-audio-shortcode', getAudioEmbedParams ),
			...processEmbeds( container, 'audio', '.omny-embed', getOmnyEmbedParams ),
			...processEmbeds( container, 'lazyimage', '.lazy-image', getLazyImageParams ),
			...processEmbeds( container, 'share', '.share-buttons', () => ( {} ) ),
		];

		// MUST follow after embeds processing
		state.content = container.innerHTML;
	}

	return state;
};

export const parseHtml = ( html ) => {
	const parser = new DOMParser();

	const pageDocument = parser.parseFromString( html, 'text/html' );
	const content = pageDocument.querySelector( '#content' );

	const state = getStateFromContent( content );
	state.document = pageDocument;

	return state;
};

export default {
	getStateFromContent,
	parseHtml,
};
