import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class ContentDispatcher extends Component {

	constructor( props ) {
		super( props );

		const self = this;
		const container = document.getElementById( 'content' );

		let html = false;
		if ( container ) {
			html = container.innerHTML;
			container.innerHTML = '';
		}

		self.state = {
			content: html,
			loading: false,
		};

		self.onClick = self.handleClick.bind( self );
		self.onPageLoad = self.handlePageLoad.bind( self );
	}

	componentDidMount() {
		window.addEventListener( 'click', this.onClick );
	}

	componentWillUnmount() {
		window.removeEventListener( 'click', this.onClick );
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
		if ( origin !== linkOrigin && !link.match( /^\/\w+/ ) ) {
			return;
		}

		// target link is internal page, thus stop propagation and prevent default actions
		e.preventDefault();
		e.stopPropagation();

		// set loading state
		this.setState( { loading: true } );

		// fetch next page
		fetch( link )
			.then( response => response.text() )
			.then( this.onPageLoad )
			.catch( error => console.error( error ) ); // eslint-disable-line no-console
	}

	handlePageLoad( data ) {
		const parser = new DOMParser();
		const doc = parser.parseFromString( data, 'text/html' );
		const content = doc.querySelector( '#content' );
		if ( !content ) {
			return;
		}

		this.setState( {
			content: content.innerHTML,
			loading: false,
		} );
	}

	render() {
		const { content } = this.state;
		const container = document.getElementById( 'content' );
		if ( !content ) {
			return <div />;
		}

		return ReactDOM.createPortal( <div dangerouslySetInnerHTML={{ __html: content }} />, container );
	}

}

export default ContentDispatcher;
