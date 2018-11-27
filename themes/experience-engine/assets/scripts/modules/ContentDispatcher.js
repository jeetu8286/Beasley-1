import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import ContentBlock from '../components/content/ContentBlock';
import { initPage, loadPage, updatePage } from '../redux/actions/screen';

const specialPages = [
	'/wp-admin/',
	'/wp-signup.php',
	'/wp-login.php',
];

class ContentDispatcher extends Component {

	constructor( props ) {
		super( props );

		const self = this;
		self.onClick = self.handleClick.bind( self );
		self.onPageChange = self.handlePageChange.bind( self );
	}

	componentDidMount() {
		const self = this;

		window.addEventListener( 'click', self.onClick );
		window.addEventListener( 'popstate', self.onPageChange );

		// replace current state with proper markup
		const { history, location, pageXOffset, pageYOffset } = window;
		const state = { data: document.documentElement.outerHTML, pageXOffset, pageYOffset };
		history.replaceState( state, document.title, location.href );

		// load current page into the state
		self.props.init();
	}

	componentWillUnmount() {
		window.removeEventListener( 'click', this.onClick );
		window.removeEventListener( 'popstate', this.onPageChange );
	}

	handleClick( e ) {
		const self = this;
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

		// do nothing if this link has to be opened in a new window
		if ( '_blank' === linkNode.getAttribute( 'target' ) ) {
			return;
		}

		const { location } = window;
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

		// fetch next page
		self.props.load( link );
	}

	handlePageChange( event ) {
		if ( event && event.state ) {
			const { data, pageXOffset, pageYOffset } = event.state;
			// update content state
			this.props.update( data );
			// scroll to the top of the page
			setTimeout( () => window.scrollTo( pageXOffset, pageYOffset ), 100 );
		}
	}

	render() {
		const { content, embeds, partials } = this.props;

		if ( !content || !content.length ) {
			return false;
		}

		const extraBlocks = [];
		Object.keys( partials ).forEach( ( key ) => {
			extraBlocks.push( <ContentBlock key={key} {...partials[key]} partial /> );
		} );

		return (
			<Fragment>
				<ContentBlock content={content} embeds={embeds} />
				{extraBlocks}
			</Fragment>
		);
	}

}

ContentDispatcher.propTypes = {
	content: PropTypes.string.isRequired,
	embeds: PropTypes.arrayOf( PropTypes.object ).isRequired,
	partials: PropTypes.shape( {} ).isRequired,
	init: PropTypes.func.isRequired,
	load: PropTypes.func.isRequired,
	update: PropTypes.func.isRequired,
};

const mapStateToProps= ( { screen } ) => ( {
	content: screen.content,
	embeds: screen.embeds,
	partials: screen.partials,
} );

const mapDispatchToProps = ( dispatch ) => bindActionCreators( {
	init: initPage,
	load: loadPage,
	update: updatePage,
}, dispatch );

export default connect( mapStateToProps, mapDispatchToProps )( ContentDispatcher );
