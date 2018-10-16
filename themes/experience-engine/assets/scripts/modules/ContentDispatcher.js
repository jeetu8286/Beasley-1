import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import NProgress from 'nprogress';

class ContentDispatcher extends Component {

	constructor( props ) {
		super( props );

		const self = this;
		const content = document.getElementById( 'content' );

		self.state = this.fragmentToState( content );

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
		const { history, location } = window;
		history.replaceState( document.documentElement.outerHTML, document.title, location.href );
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

		const { origin } = window.location;
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
				const pageDocument = this.handlePageChange( data );

				const { history } = window;
				history.pushState( data, pageDocument.title, response.url );
				document.title = pageDocument.title;

				NProgress.done();
			} ) )
			.catch( error => console.error( error ) ); // eslint-disable-line no-console
	}

	handlePageChange( data ) {
		// parse HTML markup and grab content
		const parser = new DOMParser();
		const markup = 'string' === typeof data ? data : data.state;
		const pageDocument = parser.parseFromString( markup, 'text/html' );
		const content = pageDocument.querySelector( '#content' );
		if ( !content ) {
			return;
		}

		// update content state
		this.setState( this.fragmentToState( content ) );

		// scroll to the top of the page
		window.scrollTo( 0, 0 );

		return pageDocument;
	}

	fragmentToState( element ) {
		const state = {};
		const embeds = [];

		state.embeds = embeds;
		state.content = element.innerHTML;

		return state;
	}

	render() {
		const { content } = this.state;

		return ReactDOM.createPortal(
			<div dangerouslySetInnerHTML={{ __html: content }} />,
			document.getElementById( 'content' )
		);
	}

}

export default ContentDispatcher;
