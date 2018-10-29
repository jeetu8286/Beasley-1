import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const navRoot = document.getElementById( 'js-primary-nav' );

class PrimaryNav extends Component {
	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			navHtml: null,
			primaryMenuOpen: false,
			subMenuOpen: false,
		};
	}

	componentDidMount() {
		const navHtml = navRoot.innerHTML;
		console.log( navHtml );
		this.setState( { navHtml } );

		// clean up #js-primary-nav container
		while ( navRoot.firstChild ) {
			navRoot.removeChild( navRoot.firstChild );
		}
	}

	render() {
		return ReactDOM.createPortal( <div dangerouslySetInnerHTML={{ __html: this.state.navHtml }} />, navRoot ); // render back into #primary-nav container
	}
}

export default PrimaryNav;