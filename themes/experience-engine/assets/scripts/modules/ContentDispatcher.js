import React, { Component, Fragment } from 'react';
import ReactDOM from 'react-dom';
import NProgress from 'nprogress';

import SecondStreet from '../components/embeds/SecondStreet';

class ContentDispatcher extends Component {

	constructor( props ) {
		super( props );

		const self = this;
		const content = document.getElementById( 'content' );

		self.embedsIndex = 0;
		self.state = this.populateStateFromContent( content );

		// clean up content block for now, it will be poplated in the render function
		while ( content.firstChild ) {
			content.removeChild( content.firstChild );
		}

		self.onClick = self.handleClick.bind( self );
		self.onPageChange = self.handlePageChange.bind( self );
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
		if ( ( origin !== linkOrigin && !link.match( /^\/\w+/ ) ) || -1 < link.indexOf( '/wp-admin/' ) ) {
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

					document.title = pageDocument.title;
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
		element.setAttribute( 'id', `container-${++this.embedsIndex}` );
		return element;
	}

	populateStateFromContent( container ) {
		const state = {
			embeds: [],
			content: '',
		};

		if ( container ) {
			const embeds = [];

			container.querySelectorAll( '.secondstreet-embed' ).forEach( ( element ) => {
				const placeholder = this.generatePlaceholder();

				embeds.push( {
					type: 'secondstreet',
					params: {
						placeholder: placeholder.getAttribute( 'id' ),
						script: element.getAttribute( 'src' ),
						embed: element.getAttribute( 'data-ss-embed' ),
						opguid: element.getAttribute( 'data-opguid' ),
						routing: element.getAttribute( 'data-routing' ),
					},
				} );

				element.parentNode.replaceChild( placeholder, element );
			} );

			state.embeds = embeds;
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
			if ( 'secondstreet' === embed.type ) {
				return <SecondStreet key={embed.params.placeholder} {...embed.params} />;
			}

			return false;
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
