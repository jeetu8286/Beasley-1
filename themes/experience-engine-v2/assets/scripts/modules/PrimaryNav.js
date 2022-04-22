import React, { PureComponent } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';

import { removeChildren } from '../library/dom';
import { hideModal } from '../redux/actions/modal';
import { setNavigationCurrent } from '../redux/actions/navigation';
import { refreshDropdownAd, hideDropdownAd } from '../redux/actions/dropdownad';

import { fetchPublisherInformation, isSafari } from '../library';

const $ = window.jQuery;
const config = window.bbgiconfig;

const navRoot = document.getElementById('js-primary-mega-nav');
const sidebarContainer = document.querySelector(
	'.primary-sidebar-navigation-new',
);
const listenliveContainer = document.getElementById('listen-live-button');
const sButtonContainer = document.getElementById('wp-search-submit');

class PrimaryNav extends PureComponent {
	constructor(props) {
		super(props);

		this.primaryNavRef = React.createRef();
		this.state = {
			navHtml: navRoot.innerHTML,
		};

		this.onResize = this.onResize.bind(this);
		this.handleSubMenu = this.handleSubMenu.bind(this);
		this.onPageChange = this.handlePageChange.bind(this);
		this.handleOnLoadFix = this.handleOnLoadFix.bind(this);
		this.handleClickOutSide = this.handleClickOutSide.bind(this);
		this.handleListenliveClick = this.handleListenliveClick.bind(this);
		this.handleSearchClick = this.handleSearchClick.bind(this);
		this.handleScrollNavigation = this.handleScrollNavigation.bind(this);
		this.closeMenus = this.closeMenus.bind(this);
		this.handleSubMenuSize = this.handleSubMenuSize.bind(this);

		removeChildren(navRoot);
	}

	componentDidMount() {
		window.addEventListener('resize', this.onResize);
		window.addEventListener('scroll', this.handleScrollNavigation);
		window.addEventListener('popstate', this.onPageChange);
		window.addEventListener('pushstate', this.onPageChange);

		// Add close button in the active mega menu and set current active menu
		this.handleOnLoadFix();

		const container = this.primaryNavRef.current;
		container.addEventListener('click', this.handleSubMenu);

		document.addEventListener('click', this.handleClickOutSide);

		if (window.matchMedia('(min-width: 1301px)').matches) {
			navRoot.parentNode.setAttribute('aria-hidden', false);
		}

		// Fix for login link in Safari
		if (isSafari()) {
			sidebarContainer.classList.add('is-safari');
		}

		listenliveContainer.addEventListener('click', this.handleListenliveClick);

		// Defend against null sButtonContainer which is occurring on some local environments including Mike's Local.
		if (sButtonContainer) {
			sButtonContainer.addEventListener('click', this.handleSearchClick);
		}

		document.body.classList.remove('-lock');
	}

	componentWillUnmount() {
		window.removeEventListener('resize', this.onResize);
		window.removeEventListener('scroll', this.handleScrollNavigation);
		window.removeEventListener('popstate', this.onPageChange);
		window.removeEventListener('pushstate', this.onPageChange);

		const container = this.primaryNavRef.current;
		container.removeEventListener('click', this.handleSubMenu);

		document.removeEventListener('click', this.handleClickOutSide);

		listenliveContainer.removeEventListener(
			'click',
			this.handleListenliveClick,
		);
		sButtonContainer.removeEventListener('click', this.handleSearchClick);
	}

	componentDidUpdate(prevProps) {
		if (prevProps.songs !== this.props.songs) {
			const { songs } = this.props;
			let callsign = '';
			let viewMoreLink = '';
			let recentHtml = ``;

			if (!Array.isArray(songs) || !songs.length) {
				return;
			}

			if (config.streams && config.streams.length > 0) {
				callsign = config.streams[0].stream_call_letters;
				viewMoreLink = `/stream/${callsign}/`;
			}

			const items = songs.map(song => {
				return `<li><span>${song.artistName.toLowerCase()}</span></li>`;
			});

			const recentlyPlayed = document.getElementById(
				'live-player-recently-played',
			);
			if (items.length) {
				const filterItems = items.slice(0, 4);

				const previousRecentlyPlayed = document.querySelectorAll(
					'.recently-played-section-ul',
				);

				if (previousRecentlyPlayed.length) {
					previousRecentlyPlayed.forEach(el => {
						el.remove();
					});
				}

				recentHtml = `<ul class="recently-played-section-ul">`;
				recentHtml += `
						<li><strong>Recently Played</strong></li>
						${filterItems.join('')}`;
				if (items.length > 4) {
					recentHtml += `<li><a href="${viewMoreLink}">VIEW MORE</strong></li>`;
				}
				recentHtml += `</ul>`;
				recentlyPlayed.innerHTML += recentHtml;
			}
		}
	}

	handleSubMenuSize() {
		if (window.matchMedia('(min-width: 1301px)').matches) {
			const adEle = document.getElementById('main-custom-logo');
			let adEleStyleHeight = '';
			if (adEle) {
				const adEleStyle = window.getComputedStyle(adEle);
				adEleStyleHeight = adEleStyle.height
					? Math.ceil(parseFloat(adEleStyle.height))
					: 0;
			}
			if (adEleStyleHeight > 40) {
				const menuLinkEl = document.querySelectorAll('.mega-menu-link');
				adEleStyleHeight -= 10;
				for (let i = 0; i < menuLinkEl.length; i++) {
					const mainel = menuLinkEl[i];
					mainel.style.height = `${adEleStyleHeight}px`;
				}
			}

			const mainUl = document.getElementById('mega-menu-primary-nav');
			const mainUlLeft = mainUl ? mainUl.getBoundingClientRect().left : 0;
			const container = this.primaryNavRef.current;

			const mainlinks = container.querySelectorAll(
				'.mega-menu-item-has-children > a',
			);
			for (let i = 0; i < mainlinks.length; i++) {
				const mainel = mainlinks[i];
				const nextEl = mainel.nextElementSibling;
				if (
					nextEl.nodeName.toUpperCase() === 'UL' &&
					nextEl.classList.contains('mega-sub-menu')
				) {
					const leftoffset = `calc(-${mainUlLeft}px + 1vw)`;
					nextEl.style.left = leftoffset;
					nextEl.style.width = '98vw';
				}
			}
		} else {
			const facebookURL = fetchPublisherInformation('facebook');
			const twitterURL = fetchPublisherInformation('twitter');
			const instagramURL = fetchPublisherInformation('instagram');
			const menuUL = document.getElementById('mega-menu-primary-nav');

			const newSocialEl = document.createElement('li');
			newSocialEl.classList.add('mega-menu-item');
			const ifSocialExist = document.getElementsByClassName(
				'mobile-head-social',
			);
			if (ifSocialExist.length === 0) {
				let mobileSocialHtml = `<div class="mobile-head-social">`;
				if (facebookURL) {
					mobileSocialHtml += `
						<a href="${facebookURL}" aria-label="Go to station's Facebook page" target="_blank" rel="noopener">
							<svg width="10" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<title>Facebook</title>
								<path d="M6.12 19.428H2.448V9.714H0V6.366h2.449l-.004-1.973C2.445 1.662 3.19 0 6.435 0h2.7v3.348H7.449c-1.263 0-1.324.468-1.324 1.342l-.005 1.675h3.036l-.358 3.348-2.675.001-.003 9.714z"></path>
							</svg>
						</a>
					`;
				}
				if (twitterURL) {
					mobileSocialHtml += `
						<a href="${twitterURL}" aria-label="Go to station's Twitter page" target="_blank" rel="noopener">
							<svg width="21" height="18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<title>Twitter</title>
								<path d="M20.13 2.896a8.31 8.31 0 0 1-2.372.645 4.115 4.115 0 0 0 1.816-2.266c-.798.47-1.682.81-2.623.994A4.14 4.14 0 0 0 13.937.976c-2.281 0-4.13 1.833-4.13 4.095 0 .322.036.634.107.934A11.757 11.757 0 0 1 1.4 1.725a4.051 4.051 0 0 0-.559 2.06c0 1.42.73 2.674 1.838 3.409A4.139 4.139 0 0 1 .809 6.68v.052c0 1.985 1.423 3.64 3.312 4.016a4.172 4.172 0 0 1-1.865.07 4.13 4.13 0 0 0 3.858 2.845 8.33 8.33 0 0 1-5.129 1.754c-.333 0-.662-.02-.985-.058a11.758 11.758 0 0 0 6.33 1.84c7.597 0 11.75-6.24 11.75-11.654 0-.177-.003-.354-.011-.53a8.352 8.352 0 0 0 2.06-2.12z"></path>
							</svg>
						</a>
					`;
				}
				if (instagramURL) {
					mobileSocialHtml += `
						<a href="${instagramURL}" aria-label="Go to station's Instagram page" target="_blank" rel="noopener">
							<svg width="17" height="18" fill="none" xmlns="http://www.w3.org/2000/svg">
								<title>Instagram</title>
								<path d="M15.3.976H1.7c-.935 0-1.7.765-1.7 1.7v13.6c0 .935.765 1.7 1.7 1.7h13.6c.935 0 1.7-.765 1.7-1.7v-13.6c0-.935-.765-1.7-1.7-1.7zm-6.8 5.1c1.87 0 3.4 1.53 3.4 3.4 0 1.87-1.53 3.4-3.4 3.4a3.41 3.41 0 0 1-3.4-3.4c0-1.87 1.53-3.4 3.4-3.4zm-6.375 10.2c-.255 0-.425-.17-.425-.425V8.626h1.785c-.085.255-.085.595-.085.85 0 2.805 2.295 5.1 5.1 5.1 2.805 0 5.1-2.295 5.1-5.1 0-.255 0-.595-.085-.85H15.3v7.225c0 .255-.17.425-.425.425H2.125zM15.3 4.8c0 .255-.17.425-.425.425h-1.7c-.255 0-.425-.17-.425-.425V3.1c0-.255.17-.425.425-.425h1.7c.255 0 .425.17.425.425v1.7z"></path>
							</svg>
						</a>
					`;
				}
				mobileSocialHtml += `</div>`;
				newSocialEl.innerHTML = mobileSocialHtml;
				menuUL.insertBefore(newSocialEl, menuUL.firstChild);
			}

			const newFormEl = document.createElement('li');
			newFormEl.classList.add('mega-menu-item');
			const ifExist = document.getElementsByClassName('mobile-search-form');
			if (ifExist.length === 0) {
				newFormEl.innerHTML = `
					<form role="search" method="get" class="mobile-search-form" action="${window.location.origin}/">
						<label for="mobile-search-q" id="mobile-q" class="screen-reader-text">Search for:</label>
						<input id="mobile-search-q" type="search" class="search-field" name="s" value="" placeholder="Search">
						<button type="submit" class="search-submit" aria-label="Submit search">
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="15">
								<path d="M10.266 9.034h-.65l-.23-.222a5.338 5.338 0 0 0 1.216-4.385C10.216 2.144 8.312.32 6.012.042a5.342 5.342 0 0 0-5.97 5.97c.279 2.3 2.102 4.204 4.385 4.59a5.338 5.338 0 0 0 4.385-1.215l.222.23v.649l3.49 3.49c.337.336.887.336 1.224 0s.336-.887 0-1.224l-3.482-3.498zm-4.928 0c-2.044 0-3.695-1.65-3.695-3.696s1.65-3.695 3.695-3.695 3.696 1.65 3.696 3.695-1.65 3.696-3.696 3.696z" fill="currentcolor"/>
							</svg>
						</button>
					</form>`;
				menuUL.insertBefore(newFormEl, menuUL.firstChild);
			}
		}
	}

	onResize() {
		this.handleSubMenuSize();
	}

	handleOnLoadFix() {
		this.handleSubMenuSize();
		if (window.matchMedia('(min-width: 1301px)').matches) {
			const container = this.primaryNavRef.current;
			const { href, pathname } = window.location;

			// eslint-disable-next-line no-restricted-syntax
			for (const item of container.querySelectorAll('.menu-item')) {
				item.classList.remove('current-menu-item');
			}
			const links = container.querySelectorAll('.menu-item > a');
			for (let i = 0; i < links.length; i++) {
				const element = links[i];
				const link = element.getAttribute('href');
				if (href === link || pathname === link) {
					element.parentNode.classList.add('current-mega-menu-item');
					element.parentNode
						.closest('li.mega-menu-item-has-children')
						.classList.add('current-mega-main-menu-item');
					setNavigationCurrent(element.parentNode.id);
				}
			}
		}

		const megaMenuContainer = $('#mega-menu-primary-nav');
		if (megaMenuContainer.length) {
			const clostButtonhtml = `
				<div class="close-main-mega-menu">
					<button onclick="jQuery(this).parents('li').removeClass('mega-toggle-on');">
						<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0 0 30 30" style=" fill:#ffffff;">    <path d="M 7 4 C 6.744125 4 6.4879687 4.0974687 6.2929688 4.2929688 L 4.2929688 6.2929688 C 3.9019687 6.6839688 3.9019687 7.3170313 4.2929688 7.7070312 L 11.585938 15 L 4.2929688 22.292969 C 3.9019687 22.683969 3.9019687 23.317031 4.2929688 23.707031 L 6.2929688 25.707031 C 6.6839688 26.098031 7.3170313 26.098031 7.7070312 25.707031 L 15 18.414062 L 22.292969 25.707031 C 22.682969 26.098031 23.317031 26.098031 23.707031 25.707031 L 25.707031 23.707031 C 26.098031 23.316031 26.098031 22.682969 25.707031 22.292969 L 18.414062 15 L 25.707031 7.7070312 C 26.098031 7.3170312 26.098031 6.6829688 25.707031 6.2929688 L 23.707031 4.2929688 C 23.316031 3.9019687 22.682969 3.9019687 22.292969 4.2929688 L 15 11.585938 L 7.7070312 4.2929688 C 7.5115312 4.0974687 7.255875 4 7 4 z"></path></svg>
					</button>
				</div>
			`;
			megaMenuContainer.children().each(function() {
				const ulElement = $(this).children('ul');
				if (ulElement.length) {
					// ulElement.addClass('got-selected');
					ulElement.prepend(clostButtonhtml);
				}
			});
		}
	}

	// Set whether Breaking News Is Visible And Return New Scroll Position
	setBreakingNewsVisibility(shouldShow) {
		const breakingNewsElement = document.getElementById('breaking-news-banner');
		if (breakingNewsElement) {
			if (shouldShow) {
				breakingNewsElement.style.display = 'block';
			} else {
				breakingNewsElement.style.display = 'none';
			}
		}
		return window.scrollY;
	}

	handleScrollNavigation() {
		const { y } = this.state;
		let yOffset = window.scrollY;
		const primaryTopbar = document.querySelector('.primary-mega-topbar');
		if (!window.matchMedia('(min-width: 1301px)').matches) {
			if (y > yOffset) {
				primaryTopbar.classList.remove('sticky-header-listenlive');
				if (yOffset === 0) {
					primaryTopbar.classList.remove('sticky-header');
				}
				yOffset = this.setBreakingNewsVisibility(true);
			} else if (y < yOffset) {
				if (yOffset > 100) {
					primaryTopbar.classList.add('sticky-header');
				}
				if (yOffset > 600) {
					primaryTopbar.classList.add('sticky-header-listenlive');
					yOffset = this.setBreakingNewsVisibility(false);
				}
			}
		}
		this.setState({ y: yOffset });
	}

	isPlayerButtonEvent(event) {
		const isListenLiveButtonEvent = () => {
			const playerButtonDiv = document.getElementById('player-button-div');
			return (
				event &&
				event.srcElement &&
				playerButtonDiv &&
				playerButtonDiv.contains(event.srcElement)
			);
		};
		const isListenLiveAlternativeStreamEvent = () => {
			return (
				event &&
				event.srcElement &&
				event.srcElement.parentElement &&
				(event.srcElement.className === 'control-station-button' ||
					event.srcElement.parentElement.className === 'control-station-button')
			);
		};
		const isPodcastPlayButtonEvent = () => {
			return (
				event &&
				event.srcElement &&
				event.srcElement.parentElement &&
				event.srcElement.parentElement.parentElement &&
				(event.srcElement.className === 'play-btn' ||
					event.srcElement.className === 'pause-btn' ||
					event.srcElement.className === 'resume-btn' ||
					event.srcElement.className === 'loading-btn' ||
					event.srcElement.parentElement.className === 'play-btn' ||
					event.srcElement.parentElement.className === 'pause-btn' ||
					event.srcElement.parentElement.className === 'resume-btn' ||
					event.srcElement.parentElement.className === 'loading-btn' ||
					event.srcElement.parentElement.parentElement.className ===
						'play-btn' ||
					event.srcElement.parentElement.parentElement.className ===
						'pause-btn' ||
					event.srcElement.parentElement.parentElement.className ===
						'resume-btn' ||
					event.srcElement.parentElement.parentElement.className ===
						'loading-btn')
			);
		};
		return (
			isListenLiveButtonEvent() ||
			isListenLiveAlternativeStreamEvent() ||
			isPodcastPlayButtonEvent()
		);
	}

	handleClickOutSide(event) {
		if (this.isPlayerButtonEvent(event)) {
			return;
		}

		if (event.target.classList.contains('mega-menu-link')) {
			event.target.parentNode.classList.toggle('mega-toggle-on');
		}

		const llbutton = document.getElementById('listen-live-button');
		const listenlivecontainer = document.getElementById('my-listen-dropdown2');
		if (
			event &&
			!listenlivecontainer.contains(event.target) &&
			!llbutton.contains(event.target)
		) {
			if (window.matchMedia('(min-width: 1301px)').matches) {
				const listenliveStyle = window.getComputedStyle(listenlivecontainer);
				if (listenliveStyle.display !== 'none') {
					listenlivecontainer.style.display = 'none';
				}
			}
		}
		return true;
	}

	handleSubMenu(e) {
		const { target } = e;
		if (target.nodeName.toUpperCase() === 'A') {
			this.closeMenus();
		}
	}

	handlePageChange() {
		const { primaryNavRef } = this;
		const { setNavigationCurrent, hideModal } = this.props;
		const container = primaryNavRef.current;

		const { href, pathname } = window.location;

		const previouslySelected = container.querySelectorAll(
			'.current-mega-menu-item',
		);
		const previouslySelectedMainMenu = container.querySelectorAll(
			'.current-mega-main-menu-item',
		);

		hideModal();

		for (let i = 0; i < previouslySelected.length; i++) {
			previouslySelected[i].classList.remove('current-mega-menu-item');
		}
		for (let i = 0; i < previouslySelectedMainMenu.length; i++) {
			previouslySelectedMainMenu[i].classList.remove(
				'current-mega-main-menu-item',
			);
		}

		const links = container.querySelectorAll('.menu-item > a');
		for (let i = 0; i < links.length; i++) {
			const element = links[i];
			const link = element.getAttribute('href');
			if (href === link || pathname === link) {
				element.parentNode.classList.add('current-mega-menu-item');
				element.parentNode
					.closest('li.mega-menu-item-has-children')
					.classList.add('current-mega-main-menu-item');
				setNavigationCurrent(element.parentNode.id);
			}
		}

		if (!window.matchMedia('(min-width: 1301px)').matches) {
			const mobileMenuContainer = document.getElementsByClassName(
				'mega-menu-toggle',
			);
			if (mobileMenuContainer && mobileMenuContainer.length > 0) {
				if (mobileMenuContainer[0].classList.contains('mega-menu-open')) {
					mobileMenuContainer[0].classList.remove('mega-menu-open');
				}
			}
		}

		this.closeMenus();
	}

	closeMenus() {
		const megaMenuUl = document.getElementById('mega-menu-primary-nav');
		if (megaMenuUl.children.length) {
			for (let i = 0; i < megaMenuUl.children.length; i++) {
				megaMenuUl.children[i].classList.remove('mega-toggle-on');
			}
		}
	}

	handleListenliveClick(event) {
		if (this.isPlayerButtonEvent(event)) {
			return;
		}

		const dropdownToggle = document.getElementById('my-listen-dropdown2');
		const dropdownStyle = window.getComputedStyle(dropdownToggle);
		if (dropdownStyle.display !== 'none') {
			dropdownToggle.style.display = 'none';
			const { hideDropdownAd } = this.props;
			hideDropdownAd();
		} else {
			dropdownToggle.style.display = 'block';
			const { refreshDropdownAd } = this.props;
			refreshDropdownAd();
		}
	}

	handleSearchClick() {
		const searchToggle = document.getElementsByClassName('header-search-form');
		if (searchToggle[0]) {
			const searchStyle = window.getComputedStyle(searchToggle[0]);
			if (searchStyle.display !== 'none') {
				searchToggle[0].style.display = 'none';
			} else {
				searchToggle[0].style.display = 'block';
			}
		}
	}

	render() {
		const { navHtml } = this.state;

		// render back into #primary-nav container
		return ReactDOM.createPortal(
			<div
				ref={this.primaryNavRef}
				dangerouslySetInnerHTML={{ __html: navHtml }}
			/>,
			document.getElementById('js-primary-mega-nav'),
		);
	}
}

PrimaryNav.propTypes = {
	setNavigationCurrent: PropTypes.func.isRequired,
	hideModal: PropTypes.func.isRequired,
	refreshDropdownAd: PropTypes.func.isRequired,
	hideDropdownAd: PropTypes.func.isRequired,
	songs: PropTypes.arrayOf(PropTypes.shape({})),
};

PrimaryNav.defaultProps = {
	songs: [],
};

function mapStateToProps({ player }) {
	return {
		songs: player.songs,
	};
}

function mapDispatchToProps(dispatch) {
	const actions = {
		setNavigationCurrent,
		hideModal,
		refreshDropdownAd,
		hideDropdownAd,
	};

	return bindActionCreators(actions, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(PrimaryNav);
