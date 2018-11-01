import React, { Component, Fragment } from 'react';
import ReactDOM from 'react-dom';
import NProgress from 'nprogress';

import DelayedComponent from '../components/embeds/DelayedEmbed';
import AudioEmbed from '../components/embeds/Audio';
import SecondStreetEmbed from '../components/embeds/SecondStreet';
import LazyImage from '../components/embeds/LazyImage';
import Share from '../components/embeds/Share';

import { removeChildren } from '../library/dom';

const specialPages = [
	'/wp-admin/',
	'/wp-signup.php',
	'/wp-login.php',
];

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

const getShareParams = () => ( {} );

class ContentDispatcher extends Component {

	constructor( props ) {
		super( props );

		const self = this;
		const content = document.getElementById( 'content' );

		self.embedsIndex = 0;
		self.state = this.populateStateFromContent( content );

		self.onClick = self.handleClick.bind( self );
		self.onPageChange = self.handlePageChange.bind( self );

		// clean up content block for now, it will be poplated in the render function
		removeChildren( content );
	}

	componentDidMount() {
		window.addEventListener( 'click', this.onClick );
		window.addEventListener( 'popstate', this.onPageChange );

		// replace current state with proper markup
		const { history, location, pageXOffset, pageYOffset } = window;
		const state = { data: document.documentElement.outerHTML, pageXOffset, pageYOffset };
		history.replaceState( state, document.title, location.href );
	}

	componentWillUnmount() {
		window.removeEventListener( 'click', this.onClick );
		window.removeEventListener( 'popstate', this.onPageChange );
	}

	handleClick( e ) {
		const { target } = e;
		let linkNode = target;

		// find if a click has been made by an anchor or an element that is a child of an anchor
		while ( linkNode && 'A' !== linkNode.nodeName.toUpperCase() ) {
			linkNode = linkNode.parentElement;
		}

		// do nothing if anchor is not found
		if ( !linkNode ) {
			return;
		}

		const { location, history, pageXOffset, pageYOffset } = window;
		const { origin } = location;

		const link = linkNode.getAttribute( 'href' );
		const linkOrigin = link.substring( 0, origin.length );

		// return if different origin or a relative link that doesn't start from forward slash
		if ( ( origin !== linkOrigin && !link.match( /^\/\w+/ ) ) ) {
			return;
		}

		// return if it is an admin link or a link to a special page
		if ( specialPages.find( url => -1 < link.indexOf( url ) ) ) {
			return;
		}

		// target link is internal page, thus stop propagation and prevent default actions
		e.preventDefault();
		e.stopPropagation();

		// set loading state
		NProgress.start();

		// fetch next page
		fetch( link )
			.then( response => response.text().then( data => {
				const payload = {
					state: {
						data,
						pageXOffset: 0,
						pageYOffset: 0,
					},
				};

				const pageDocument = this.handlePageChange( payload );
				if ( pageDocument ) {
					const state = Object.assign( {}, history.state );
					state.pageXOffset = pageXOffset;
					state.pageYOffset = pageYOffset;

					history.replaceState( state, document.title, location.href );
					history.pushState( payload.state, pageDocument.title, response.url );

					let event = false;
					if ( 'function' === typeof( Event ) ) {
						event = new Event( 'pushstate' );
					} else {
						// ie11 compatibility
						event = document.createEvent( 'Event' );
						event.initEvent( 'pushstate', true, true );
					}

					if ( event ) {
						window.dispatchEvent( event );
					}

					document.title = pageDocument.title;
					document.body.className = pageDocument.body.className;
				}

				NProgress.done();
			} ) )
			.catch( error => console.error( error ) ); // eslint-disable-line no-console
	}

	handlePageChange( event ) {
		if ( !event || !event.state ) {
			return false;
		}

		// parse HTML markup and grab content
		const parser = new DOMParser();
		const { data, pageXOffset, pageYOffset } = event.state;
		const pageDocument = parser.parseFromString( data, 'text/html' );

		// update content state
		const content = pageDocument.querySelector( '#content' );
		this.setState( this.populateStateFromContent( content ) );

		// scroll to the top of the page
		setTimeout( () => {
			window.scrollTo( pageXOffset, pageYOffset );
		}, 100 );

		return pageDocument;
	}

	generatePlaceholder() {
		const element = document.createElement( 'div' );
		element.setAttribute( 'id', `__cd-${++this.embedsIndex}` );
		return element;
	}

	processEmbeds( container, type, selector, callback ) {
		const embeds = [];
		const elements = container.querySelectorAll( selector );
		for ( let i = 0, len = elements.length; i < len; i++ ) {
			const element = elements[i];
			const placeholder = this.generatePlaceholder();

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
	}

	populateStateFromContent( container ) {
		const self = this;
		const state = { embeds: [], content: '' };
		if ( container ) {
			state.embeds = [
				...self.processEmbeds( container, 'secondstreet', '.secondstreet-embed', getSecondStreetEmbedParams ),
				...self.processEmbeds( container, 'audio', '.wp-audio-shortcode', getAudioEmbedParams ),
				...self.processEmbeds( container, 'audio', '.omny-embed', getOmnyEmbedParams ),
				...self.processEmbeds( container, 'lazyimage', '.lazy-image', getLazyImageParams ),
				...self.processEmbeds( container, 'share', '.share-buttons', getShareParams ),
			];

			// MUST follow after embeds processing
			state.content = container.innerHTML;
		}

		return state;
	}

	render() {
		const { content, embeds } = this.state;

		const portal = ReactDOM.createPortal(
			<div dangerouslySetInnerHTML={{ __html: content }} />,
			document.getElementById( 'content' )
		);

		const embedComponents = embeds.map( ( embed ) => {
			const { type, params } = embed;
			const { placeholder } = params;

			let component = false;
			switch ( type ) {
				case 'secondstreet':
					component = SecondStreetEmbed;
					break;
				case 'audio':
					component = AudioEmbed;
					break;
				case 'lazyimage':
					component = LazyImage;
					break;
				case 'share':
					component = Share;
					break;
			}

			if ( component ) {
				component = React.createElement( component, params );
				component = (
					<DelayedComponent key={placeholder} placeholder={placeholder}>
						{component}
					</DelayedComponent>
				);
			}

			return component;
		} );

		return (
			<Fragment>
				{portal}
				{embedComponents}
			</Fragment>
		);
	}

}

export default ContentDispatcher;
