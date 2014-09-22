/*! Greater Media Live Player - v0.1.0
 * http://wordpress.org/plugins
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function ($) {
	"use strict";

	function pjaxTime(){
		$(':checkbox').attr('checked', $.cookie('pjax'))

		if ( !$(':checkbox').attr('checked') )
			$.fn.pjax = $.noop

		$(':checkbox').change(function() {
			if ( $.pjax == $.noop ) {
				$(this).removeAttr('checked')
				return alert( "Sorry, your browser doesn't support pjax :(" )
			}

			if ( $(this).attr('checked') )
				$.cookie('pjax', true)
			else
				$.cookie('pjax', null)

			window.location = location.href
		})
	}

	function togglePlayer(){
		var toggleButton = $('.gmlp-nav-toggle'),
			body = $('body');

		toggleButton.click(function(){
			body.toggleClass('gmlp-open');
		});
	}

	var menuLinkSelector = 'li a',
		article = 'article';

	pjax.connect(article, menuLinkSelector);

	$(document).ready(function($){
		togglePlayer();
		pjaxTime();
	});

} )(jQuery);