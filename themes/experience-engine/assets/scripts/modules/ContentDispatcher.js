import React, { Component, Fragment } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import DelayedComponent from '../components/embeds/DelayedEmbed';
import AudioEmbed from '../components/embeds/Audio';
import SecondStreetEmbed from '../components/embeds/SecondStreet';
import LazyImage from '../components/embeds/LazyImage';
import Share from '../components/embeds/Share';

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
		const { content, embeds } = this.props;

		if ( !content || !content.length ) {
			return false;
		}

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

ContentDispatcher.propTypes = {
	content: PropTypes.string.isRequired,
	embeds: PropTypes.arrayOf( PropTypes.object ).isRequired,
	init: PropTypes.func.isRequired,
	load: PropTypes.func.isRequired,
	update: PropTypes.func.isRequired,
};

const mapStateToProps= ( { screen } ) => ( {
	content: screen.content,
	embeds: screen.embeds,
} );

const mapDispatchToProps = ( dispatch ) => bindActionCreators( {
	init: initPage,
	load: loadPage,
	update: updatePage,
}, dispatch );

export default connect( mapStateToProps, mapDispatchToProps )( ContentDispatcher );
