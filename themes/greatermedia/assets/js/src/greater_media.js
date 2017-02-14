/**
 * Greater Media
 *
 * Various theme specific functions
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function ($, window, document, undefined) {

	/**
	 * Global variables
	 */
	var body = document.querySelector('body');
	var html = document.querySelector('html');
	var header = document.getElementById('header');
	var header_main = document.getElementsByClassName('header__main')[0];
	var header_container = document.querySelectorAll('.header__main > .container')[0];
	var audio_interface = document.getElementById('js-audio-interface');
	var footer = document.querySelector('.footer');
	var $table = $('table');
	var $tableTd = $('table td');

	/**
	 * Headroom.js
	 */
	$( document ).ready( function() {
		// Set variables
		var header_height = header.offsetHeight,
			header_container_height_full = header_container.offsetHeight,
			offset_height = header_container_height_full,
			header_container_height_min,
			admin_bar;

		// Set header height
		header.style.height = header_height + 'px';

		// Is admin bar present
		if (  body.classList.contains( 'admin-bar' ) ) {
			admin_bar = true;
			// offset_height = offset_height + 32;
		}

		// Manage the position of the header during headroom.js events
		var headroomManagePosition = function() {
			header_container_height_min = header_container.offsetHeight;
			header_main.style.top = '-' + header_container_height_min + 'px';
		};

		// Headroom.js
		/* jshint ignore:start */
		var headroom_header = new Headroom( header_main, {
			'offset': offset_height,
			'tolerance': 5,
			onPin: function() {
				header_main.style.top = '0px';
			},
			onUnpin: function() {
				headroomManagePosition();
			},
			onTop: function() {
				header_main.style.top = '0px';
			},
			onNotTop: function() {
				headroomManagePosition();
			}
		} );
		headroom_header.init();
		/* jshint ignore:end */

	} );

	/**
	 * Adds a class to a HTML table to make the table responsive
	 */
	function responsiveTables() {
		$table.addClass('responsive');
		$tableTd.removeAttr('width');
	}

	/**
	 * Function to add pop-up for social links
	 *
	 * @returns {boolean}
	 */
	function socialPopup() {
		var href = $(this).attr('href'),
			x = screen.width / 2 - 700 / 2,
			y = screen.height / 2 - 450 / 2;

		window.open(href, href, 'height=485,width=700,scrollbars=yes,resizable=yes,left=' + x + ',top=' + y);

		return false;
	}

	/**
	 * Toggles a target element for contest rules
	 *
	 * @param {MouseEvent} e
	 * @returns {boolean}
	 */
	function contestRulesToggle(e) {
		var target = $($(this).attr('data-target')).get(0),
			currentText = $(this).html(),
			newText = $(this).attr('data-alt-text');

		target.style.display = target.style.display !== 'none' ? 'none' : 'block';

		$(this).html(newText);
		$(this).attr('data-alt-text', currentText);

		return false;
	}

	/**
	 * Personality Toggle
	 */
	function personality_toggle() {
		var $button = $('.person-toggle'),
			start = $('.personality__meta').first().height(); // get the height of the meta before we start, basically tells us whether we're using the mobile or desktop height

		$button.on('click', function (e) {
			var $this = $(this),
				$parent = $this.parent().parent('.personality'),
				$meta = $this.siblings('.personality__meta'),
				curr = $meta.height(),
				auto = $meta.css('height', 'auto').height(),
				offset = '';

			$parent.toggleClass('open');
			// if( $parent.hasClass('open') ) {
			// 	$meta.height(curr).animate({height: auto * 0.69}, 1000); // the 0.69 adjusts for the difference in height due to the overflow: visible wrapping the text
			// } else {
			// 	$meta.height(curr).animate({height: start}, 1000);
			// }


			if ($this.hasClass('active')) {
				$this.text('More');
			} else {
				$this.text('Less');
			}
			$this.toggleClass('active');
		});
	}

	/**
	 * Init Functions
	 */
	responsiveTables();

	/**
	 * Functions called on Document Ready
	 */
	$(document).ready(function() {
		personality_toggle();
		$('.article__content').fitVids({customSelector: "div[id^='playerwrapper']"});
	});

	/**
	 * Functions specific to document events
	 */
	$(document).on('click', '.popup', socialPopup);

	$(document).on('click', '*[data-toggle="collapse"]', contestRulesToggle);

	/**
	 * Functions that run after the pjax:end event
	 */
	$(document).bind( 'pjax:end', function () {
		personality_toggle();
	});

})(jQuery, window, document);