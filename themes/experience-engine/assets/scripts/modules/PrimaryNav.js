import React, { Component } from 'react';
import ReactDOM from 'react-dom';

const navRoot = document.getElementById( 'js-primary-nav' );
const siteMenuToggle = document.getElementById( 'js-menu-toggle' );

class PrimaryNav extends Component {
	constructor( props ) {
		super( props );

		const self = this;
		const navHtml = navRoot.innerHTML;

		self.primaryNavRef = React.createRef();

		while ( navRoot.firstChild ) {
			navRoot.removeChild( navRoot.firstChild );
		}

		self.state = {
			navHtml,
			primaryMenuOpen: false,
			subMenuOpen: false,
		};

		self.handleSubMenu = self.handleSubMenu.bind( self );
		self.handleMobileNav = self.handleMobileNav.bind( self );
		self.onResize = self.onResize.bind( self );
	}

	componentDidMount() {
		const self = this;
		const container = navRoot.parentNode;
		const itemsWithChildren = document.querySelectorAll( '.menu-item-has-children' );

		for ( let i = 0; i < itemsWithChildren.length; i++ ) {
			const el = itemsWithChildren[i];
			const subMenuActivator = document.createElement( 'button' );
			const subMenuEl = el.querySelector( '.sub-menu' );

			subMenuActivator.classList.add( 'sub-menu-activator' );
			el.insertBefore( subMenuActivator, subMenuEl );
			el.querySelector( 'a' ).setAttribute( 'aria-haspopup', 'true' );
			subMenuEl.setAttribute( 'aria-hidden', 'true' );
			subMenuEl.setAttribute( 'aria-label', 'Submenu' );
		}

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

	handleSubMenu( el ) {
		const self = this;
		const parent = el.target.parentNode;
		const subMenu = parent.querySelector( '.sub-menu' );
		const actives = document.querySelectorAll( '.menu-item-has-children .is-active' );
		const { subMenuOpen } = self.state;


		if ( el.target.classList.contains( 'sub-menu-activator' ) ) {

			if ( true === subMenuOpen && !el.target.classList.contains( 'is-active' ) ) {
				for ( let i = 0; i < actives.length; i++ ) {
					const el = actives[i];
					el.classList.remove( 'is-active' );
					el.setAttribute( 'aria-hidden', true );
					self.setState( { subMenuOpen: false } );
				}
			}

			subMenu.classList.toggle( 'is-active' );
			el.target.classList.toggle( 'is-active' );
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
		return ReactDOM.createPortal( <div ref={this.primaryNavRef} dangerouslySetInnerHTML={{ __html: this.state.navHtml }} />, document.getElementById( 'js-primary-nav' ) ); // render back into #primary-nav container
	}
}

export default PrimaryNav;