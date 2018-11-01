import React, { PureComponent } from 'react';
import ReactDOM from 'react-dom';

import { removeChildren } from '../library/dom';

const navRoot = document.getElementById( 'js-primary-nav' );
const siteMenuToggle = document.getElementById( 'js-menu-toggle' );

class PrimaryNav extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.primaryNavRef = React.createRef();
		self.state = { navHtml: navRoot.innerHTML };

		self.handleSubMenu = self.handleSubMenu.bind( self );
		self.handleMobileNav = self.handleMobileNav.bind( self );
		self.onSearchSubmit = self.handleSearchSubmit.bind( self );
		self.onPageChange = self.handlePageChange.bind( self );
		self.onResize = self.onResize.bind( self );

		removeChildren( navRoot );
	}

	componentDidMount() {
		const self = this;

		window.addEventListener( 'resize', self.onResize );
		window.addEventListener( 'popstate', self.onPageChange );
		window.addEventListener( 'pushstate', self.onPageChange );

		const container = self.primaryNavRef.current;
		container.addEventListener( 'click', self.handleSubMenu );

		const searchForm = container.querySelector( '.search-form' );
		if ( searchForm ) {
			searchForm.addEventListener( 'submit', self.onSearchSubmit );
		}

		siteMenuToggle.addEventListener( 'click', self.handleMobileNav );

		if ( window.matchMedia( '(min-width: 768px)' ).matches ) {
			navRoot.parentNode.setAttribute( 'aria-hidden', false );
		}
	}

	componentWillUnmount() {
		const self = this;

		window.removeEventListener( 'resize', self.onResize );
		window.removeEventListener( 'popstate', self.onPageChange );
		window.removeEventListener( 'pushstate', self.onPageChange );

		const container = self.primaryNavRef.current;
		container.removeEventListener( 'click', self.handleSubMenu );

		const searchForm = container.querySelector( '.search-form' );
		if ( searchForm ) {
			searchForm.removeEventListener( 'submit', self.onSearchSubmit );
		}

		siteMenuToggle.removeEventListener( 'click', self.handleMobileNav );
	}

	handlePageChange() {
		const self = this;
		const { primaryNavRef } = self;
		const container = primaryNavRef.current;

		const currentUrl = window.location.href;

		const previouslySelected = container.querySelectorAll( '.current-menu-item' );
		for ( let i = 0; i < previouslySelected.length; i++ ) {
			previouslySelected[i].classList.remove( 'current-menu-item' );
		}

		const links = container.querySelectorAll( '.menu-item > a' );
		for ( let i = 0; i < links.length; i++ ) {
			const element = links[i];
			if ( element.getAttribute( 'href' ) === currentUrl ) {
				element.parentNode.classList.add( 'current-menu-item' );
			}
		}

		if ( !window.matchMedia( '(min-width: 768px)' ).matches ) {
			self.handleMobileNav();
		}
	}

	handleSubMenu( e ) {
		const { target } = e;
		const menuItem = target.parentNode;

		const self = this;
		const { primaryNavRef } = self;
		const container = primaryNavRef.current;

		if ( 'BUTTON' === target.nodeName.toUpperCase() ) {
			const toggler = menuItem.querySelector( '.sub-menu-activator' );
			if ( toggler ) {
				toggler.classList.toggle( 'is-active' );
			}

			const subMenu = menuItem.querySelector( '.sub-menu' );
			if ( subMenu ) {
				subMenu.setAttribute( 'aria-hidden', subMenu.classList.contains( 'is-active' ) );
				subMenu.classList.toggle( 'is-active' );
			}

			const actives = container.querySelectorAll( '.menu-item-has-children .is-active' );
			for ( let i = 0; i < actives.length; i++ ) {
				const element = actives[i];
				if ( element !== toggler && element !== subMenu ) {
					element.classList.remove( 'is-active' );
					element.setAttribute( 'aria-hidden', true );
				}
			}
		}
	}

	handleMobileNav( e ) {
		if ( e ) {
			e.preventDefault();
			e.stopPropagation();
		}

		const container = navRoot.parentNode;
		container.classList.toggle( 'is-active' );
		container.parentNode.classList.toggle( 'menu-is-active' );
		container.setAttribute( 'aria-hidden', 'false' === container.getAttribute( 'aria-hidden' ) );
	}

	handleSearchSubmit( e ) {
		e.preventDefault();
	}

	onResize() {
		const container = navRoot.parentNode;
		window.requestAnimationFrame( () => {
			container.parentNode.classList.remove( 'menu-is-active' );

			if ( window.matchMedia( '(min-width: 768px)' ).matches ) {
				container.setAttribute( 'aria-hidden', false );
			} else {
				container.setAttribute( 'aria-hidden', true );
			}
		} );
	}

	render() {
		const self = this;
		const { navHtml } = self.state;

		// render back into #primary-nav container
		return ReactDOM.createPortal(
			<div ref={self.primaryNavRef} dangerouslySetInnerHTML={{ __html: navHtml }} />,
			document.getElementById( 'js-primary-nav' ),
		);
	}

}

export default PrimaryNav;
