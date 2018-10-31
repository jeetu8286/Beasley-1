import React, { PureComponent } from 'react';
import ReactDOM from 'react-dom';

import { removeChildren } from '../library/dom';

const navRoot = document.getElementById( 'js-primary-nav' );
const siteMenuToggle = document.getElementById( 'js-menu-toggle' );

class PrimaryNav extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;
		const navHtml = navRoot.innerHTML;

		self.primaryNavRef = React.createRef();
		self.state = {
			navHtml,
			primaryMenuOpen: false,
			subMenuOpen: false,
		};

		self.handleSubMenu = self.handleSubMenu.bind( self );
		self.handleMobileNav = self.handleMobileNav.bind( self );
		self.onResize = self.onResize.bind( self );

		removeChildren( navRoot );
	}

	componentDidMount() {
		const self = this;
		const container = navRoot.parentNode;

		window.addEventListener( 'resize', self.onResize );
		self.primaryNavRef.current.addEventListener( 'click', self.handleSubMenu );
		siteMenuToggle.addEventListener( 'click', self.handleMobileNav );

		if ( window.matchMedia( '(min-width: 768px)' ).matches ) {
			container.setAttribute( 'aria-hidden', false );
			this.setState( { primaryMenuOpen: false } );
		}
	}

	componentWillUnmount() {
		const self = this;
		window.removeEventListener( 'resize', self.onResize );
		self.primaryNavRef.current.removeEventListener( 'click', self.handleSubMenu );
		siteMenuToggle.removeEventListener( 'click', self.handleMobileNav );
	}

	handleSubMenu( e ) {
		const { target } = e;

		const self = this;
		const { subMenuOpen } = self.state;

		const subMenu = target.parentNode.querySelector( '.sub-menu' );
		const actives = document.querySelectorAll( '.menu-item-has-children .is-active' );

		if ( target.classList.contains( 'sub-menu-activator' ) ) {
			if ( true === subMenuOpen && !target.classList.contains( 'is-active' ) ) {
				for ( let i = 0; i < actives.length; i++ ) {
					const el = actives[i];
					el.classList.remove( 'is-active' );
					el.setAttribute( 'aria-hidden', true );
					self.setState( { subMenuOpen: false } );
				}
			}

			subMenu.classList.toggle( 'is-active' );
			target.classList.toggle( 'is-active' );
			self.setState( ( state ) => ( { subMenuOpen: !state.subMenuOpen } ) );
			subMenu.setAttribute( 'aria-hidden', !this.state.subMenuOpen );
		}
	}

	handleMobileNav() {
		const self = this;
		const container = navRoot.parentNode;
		const { primaryMenuOpen } = self.state;

		if ( true === primaryMenuOpen ) {
			container.setAttribute( 'aria-hidden', true );
			container.classList.remove( 'is-active' );
			container.parentNode.classList.remove( 'menu-is-active' );
			self.setState( { primaryMenuOpen: false } );
		} else if ( false === primaryMenuOpen ) {
			container.classList.add( 'is-active' );
			container.parentNode.classList.add( 'menu-is-active' );
			container.setAttribute( 'aria-hidden', false );
			self.setState( { primaryMenuOpen: true } );
		}
	}

	onResize() {
		const container = navRoot.parentNode;
		window.requestAnimationFrame( () => {
			container.parentNode.classList.remove( 'menu-is-active' );

			if ( window.matchMedia( '(min-width: 768px)' ).matches ) {
				container.setAttribute( 'aria-hidden', false );
				this.setState( { primaryMenuOpen: true } );
			} else {
				container.setAttribute( 'aria-hidden', true );
				this.setState( { primaryMenuOpen: false } );
			}
		} );
	}

	render() {
		const self = this;
		const { navHtml } = self.state;

		// render back into #primary-nav container
		return ReactDOM.createPortal(
			<div ref={self.primaryNavRef} dangerouslySetInnerHTML={{ __html: navHtml }} />,
			document.getElementById( 'js-primary-nav' )
		);
	}

}

export default PrimaryNav;
