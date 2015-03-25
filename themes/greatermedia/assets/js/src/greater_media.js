/**
 * Greater Media
 *
 * Various theme specific functions
 *
 * Copyright (c) 2014 10up
 * Licensed under the GPLv2+ license.
 */
(function () {

	/**
	 * global variables
	 *
	 * @type {jQuery}
	 */
	var $ = jQuery;

	var body = document.querySelector('body');
	var html = document.querySelector('html');
	var header = document.getElementById('header');
	var footer = document.querySelector('.footer');
	var $table = $('table');
	var $tableTd = $('table td');

	/**
	 * Adds a class to a HTML table to make the table responsive
	 */
	function responsiveTables() {
		$table.addClass('responsive');
		$tableTd.removeAttr('width');
	}

	/**
	 * Fallback for adding a body class when a user is a Gigya authenticated user
	 */
	function addGigyaBodyClass() {
		if (! body.classList.contains('gmr-user')) {
			body.classList.add('gmr-user');
		}
	}

	(function ($) {
		$(document).on('click', '.popup', function () {
			var href = $(this).attr('href'),
				x = screen.width / 2 - 700 / 2,
				y = screen.height / 2 - 450 / 2;

			window.open(href, href, 'height=485,width=700,scrollbars=yes,resizable=yes,left=' + x + ',top=' + y);

			return false;
		});

		/**
		 * Toggles a target element.
		 * @param {MouseEvent} e
		 */
		$(document).on('click', '*[data-toggle="collapse"]', function(e) {
			var target = $($(this).attr('data-target')).get(0),
				currentText = $(this).html(),
				newText = $(this).attr('data-alt-text');

			target.style.display = target.style.display != 'none' ? 'none' : 'block';

			$(this).html(newText);
			$(this).attr('data-alt-text', currentText);

			return false;
		});

		$(document).ready(function() {
			$('.article__content').fitVids({customSelector: "div[id^='playerwrapper']"});
		});
	})(jQuery);

	/**
	 * Personality Toggle
	 */
	function personality_toggle() {
		var $button = jQuery('.person-toggle');
		start = jQuery('.personality__meta').first().height(); // get the height of the meta before we start, basically tells us whether we're using the mobile or desktop height

		$button.on('click', function (e) {
			var $this = $(this);
			$parent = $this.parent().parent('.personality');
			$meta = $this.siblings('.personality__meta');
			curr = $meta.height();
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

	if (is_gigya_user_logged_in()) {
		addGigyaBodyClass();
	}

	$(document).ready(function() {
		personality_toggle();
	});

	/**
	 * Functions that run after the pjax:end event
	 */
	$(document).bind( 'pjax:end', function () {
		personality_toggle();
	});
	
})();