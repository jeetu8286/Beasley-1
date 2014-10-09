/**
 * Created by allen on 9/22/14.
 */

(function ($,window,undefined) {
	"use strict";

	// variables
	var document = window.document,
		$document = $(document),
		menuLinkSelector = $('#container a');

	function addLinkClass() {
		menuLinkSelector.addClass('pjaxer');
	}

	$document.ready(function($){
		addLinkClass();
	});

} )(jQuery,window);

pjax.connect('container', 'pjaxer');