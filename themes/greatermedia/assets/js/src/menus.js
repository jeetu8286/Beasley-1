/**
 * Greater Media
 *
 * Functionality specific to the menus.
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function ($, window, document, undefined) {

	/**
	 * Global variables
	 */
	var body = document.querySelector('body'),
		mobileNavButton = document.querySelector('.mobile-nav__toggle');

	/**
	 * Function to detect if the current browser can use `addEventListener`, if not, use `attachEvent`
	 * this is a specific fix for IE8
	 *
	 * @param elem
	 * @param eventType
	 * @param handler
	 */
	function addEventHandler(elem, eventType, handler) {
		if (elem.addEventListener) {
			elem.addEventListener(eventType, handler, false);
		} else if (elem.attachEvent) {
			elem.attachEvent('on' + eventType, handler);
		}
	}

	/**
	 * Inserts a new element on mobile to provide a blocker
	 *
	 * @returns {*|jQuery|HTMLElement}
	 */
	var getBlockerDiv = function() {
		var $div = $('#mobile-nav-blocker');
		if ($div.length === 0) {
			$('<div id="mobile-nav-blocker"></div>').insertAfter('#mobile-nav');
			$div = $('#mobile-nav-blocker');
			$div.on('click', toggleNavButton);
		}

		return $div;
	};

	/**
	 * Shows the blocker div that is created by getBlockerDiv
	 */
	var showBlocker = function() {
		var $blocker = getBlockerDiv();

		$blocker.css({
			display: 'block'
		});
	};

	/**
	 * Hides the blocker div that is shown by showBlocker
	 */
	var hideBlocker = function() {
		var $blocker = getBlockerDiv();
		$blocker.css({'display': 'none'});
		if ($blocker.hasClass('active')) {
			$blocker.removeClass('active');
		}
	};

	/**
	 * Toggles a class to the body when the mobile nav button is clicked
	 */
	function toggleNavButton() {
		body.classList.toggle('mobile-nav--open');

		if ($('.mobile-nav--open').length) {
			showBlocker();
		} else {
			hideBlocker();
		}
	}

	/**
	 * Adds a overlay to the body when a menu item that has a sub-menu, is hovered
	 */
	function init_menu_overlay() {
		var $menu = jQuery(document.querySelector('.header__nav--list')),
			$secondary = jQuery(document.querySelector('.header__secondary')),
			$overlay = jQuery(document.querySelector('.menu-overlay-mask')),
			$body = jQuery(document.querySelector('body')),
			$logo = jQuery(document.querySelector('.header__logo'));

		$menu.on('mouseover', '.menu-item-has-children', function (e) {
			$overlay.addClass('is-visible');
			if($body.hasClass('news-site')) {
				$logo.addClass('is-visible');
			}
		});
		$menu.on('mouseout', '.menu-item-has-children', function (e) {
			$overlay.removeClass('is-visible');
			if($body.hasClass('news-site')) {
				$logo.removeClass('is-visible');
			}
		});
	}

	/**
	 * Helps with hover issues on mobile
	 */
	function addHoverMobile() {
		$('.header__nav ul li').on('click touchstart', function() {
			$(this).addClass('active');
		});
	}

	/**
	 * Removes the active class added by addHoverMobile
	 */
	function removeHoverMobile() {
		$('.header__nav ul li').removeClass('active');
	}

	/**
	 * Triggered by pjax to remove the `is-visible` class when the pjax:end event is triggered
	 */
	function removeoverlay() {
		var $overlay = jQuery(document.querySelector('.menu-overlay-mask'));

		$overlay.removeClass('is-visible');
	}

	/**
	 * Adds an active class to a menu item when it is hovered
	 */
	function addMenuHover() {
		$('.header__nav ul li').hover(
			function () {
				$(this).addClass('active');
			},
			function () {
				$(this).removeClass('active');
			}
		);
	}

	/**
	 * Adds an visibility class to the logo when the menu is hovered
	 */
	function addLogoVisibility() {
		var $body = jQuery(document.querySelector('body')),
			$logo = jQuery(document.querySelector('.header__logo')),
			$headerMain = jQuery(document.querySelector('.header__main'));

		$headerMain.on('mouseover', function(e) {
			if ($body.hasClass('news-site')) {
				$logo.addClass('is-visible');
			}
		});

		$headerMain.on('mouseout', function(e) {
			if ($body.hasClass('news-site')) {
				$logo.removeClass('is-visible');
			}
		});
	}

	/**
	 * Init Functions
	 */
	addEventHandler(mobileNavButton, 'click', toggleNavButton);
	init_menu_overlay();
	addHoverMobile();
	addMenuHover();
	addLogoVisibility();

	/**
	 * Functions that run after the pjax:end event
	 */
	$(document).bind( 'pjax:end', function () {
		hideBlocker();
		removeHoverMobile();
		removeoverlay();
	});

})(jQuery, window, document);
