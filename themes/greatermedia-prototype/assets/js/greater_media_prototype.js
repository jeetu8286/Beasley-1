/*! Greater Media Prototype - v0.1.0 - 2014-10-06
 * http://wordpress.org/themes
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function (window, undefined) {
	'use strict';

	window.console.log('prototype js running');

	function init() {
		gigya.accounts.getAccountInfo({ callback: getAccountInfoResponse });
		bindRegisterLogin();
	}

	function bindRegisterLogin() {
		window.document.querySelector('.register').addEventListener('click', showRegisterForm);
		window.document.querySelector('.login').addEventListener('click', showLoginForm);
	}

	function showRegisterForm(e) {
		e.preventDefault();
		window.console.log('clicked register');
		gigya.accounts.showScreenSet({screenSet: 'Login-web'});
	}

	function showLoginForm(e) {
		e.preventDefault();
		window.console.log('clicked login');
		gigya.accounts.showScreenSet({screenSet: 'Login-web', startScreen: 'gigya-register-screen'});
	}

	/**
	 * Checks to see if the user is logged in, changes the header appropriately.
	 * @param response
	 */
	function getAccountInfoResponse(response) {
		if (response.errorCode == 0) {
			// Success, this user is logged in
			window.console.log(response.profile);
		} else {
			// error
			window.console.log(response);
		}
	}

	init();

})(this);