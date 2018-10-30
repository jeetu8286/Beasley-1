import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const navRoot = document.getElementById( 'js-primary-nav' );
const siteMenuToggle = document.getElementById( 'js-menu-toggle' );

class PrimaryNav extends Component {
	constructor( props ) {
		super( props );
		this.primaryNavRef = React.createRef();

		const self = this;
		const navHtml = navRoot.innerHTML;

		while ( navRoot.firstChild ) {
			navRoot.removeChild( navRoot.firstChild );
		}

		self.state = {
			navHtml,
			primaryMenuOpen: false,
			subMenuOpen: false,
		};

		self.handleSubMenu = self.handleSubMenu.bind( self );
	}

	componentDidMount() {
		document.querySelectorAll( '.menu-item-has-children' ).forEach( el => {
			const subMenuActivator = document.createElement( 'button' );
			subMenuActivator.classList.add( 'sub-menu-activator' );
			el.insertBefore( subMenuActivator, el.querySelector( '.sub-menu' ) );
		} );

		this.primaryNavRef.current.addEventListener( 'click', el => {
			if ( el.target.classList.contains( 'sub-menu-activator' ) ) {
				this.handleSubMenu( el );
			}
		} );

		siteMenuToggle.addEventListener( 'click', () => {
			this.handleMobileNav();
		} );

		if( window.matchMedia( '(min-width: 768px)' ).matches ) {
			navRoot.setAttribute( 'aria-hidden', false );
		}
	}

	handleSubMenu( el ) {
		const parent = el.target.parentNode;
		const subMenu = parent.querySelector( '.sub-menu' );
		const actives = document.querySelectorAll( '.menu-item-has-children .is-active' );
		const { subMenuOpen } = this.state;

		if ( true === subMenuOpen && !el.target.classList.contains( 'is-active' ) ) {
			actives.forEach( el => {
				el.classList.remove( 'is-active' );
			} );
		}

		subMenu.classList.toggle( 'is-active' );
		el.target.classList.toggle( 'is-active' );

		this.setState( { subMenuOpen: true } );
	}

	handleMobileNav() {
		const { primaryMenuOpen } = this.state;

		if ( true === primaryMenuOpen ) {
			navRoot.setAttribute( 'aria-hidden', true );
			navRoot.classList.remove( 'is-active' );
		} else if ( false === primaryMenuOpen ) {
			navRoot.classList.add( 'is-active' );
			navRoot.setAttribute( 'aria-hidden', false );
		}

		this.setState( ( state ) => { return { primaryMenuOpen: !state.primaryMenuOpen }; } );
	}

	render() {
		return ReactDOM.createPortal( <div ref={this.primaryNavRef} dangerouslySetInnerHTML={{ __html: this.state.navHtml }} />, document.getElementById( 'js-primary-nav' ) ); // render back into #primary-nav container
	}
}

export default PrimaryNav;