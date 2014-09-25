/*! Greater Media Prototype - v0.1.0 - 2014-09-25
 * http://wordpress.org/themes
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function (window, undefined) {
	'use strict';

	function init() {
		gigya.accounts.getAccountInfo({ callback: getAccountInfoResponse });
		bindRegisterLogin();

		// Handle successful login / registration
		gigya.socialize.addEventHandlers({
			onLogin:loginSuccess
		});
	}

	function bindRegisterLogin() {
		jQuery('.register').on('click', showRegisterForm);
		jQuery('.login').on('click', showLoginForm);
	}

	function showRegisterForm(e) {
		e.preventDefault();
		gigya.accounts.showScreenSet({screenSet: 'Survey-registration', startScreen:'gigya-register-screen', containerID:'gigya-controls'});
	}

	function showLoginForm(e) {
		e.preventDefault();
		gigya.accounts.showScreenSet({screenSet: 'Survey-registration', startScreen:'gigya-login-screen', containerID:'gigya-controls'});
	}

	function updateProfile( accountData ){
		gigya.accounts.setAccountInfo({ data: accountData });
	}

	/**
	 * Checks to see if the user is logged in, changes the header appropriately.
	 * @param response
	 */
	function getAccountInfoResponse(response) {

		if (response.errorCode == 0) { // Success, this user is logged in

			// set hidden form fields
			jQuery('input#gigya_UID').val(response.UID);
			jQuery('input#gigya_name').val(response.profile.firstName + ' ' + response.profile.lastName);

			// hide login/registration form
			jQuery('#gigya-login-wrap').hide();

			// show gravity form
			jQuery('form.hide').removeClass('hide');

		} else { // User not yet logged

			// show login/registration form
			gigya.accounts.showScreenSet({ screenSet:'Survey-registration', startScreen:'gigya-register-screen', containerID:'gigya-controls' });

			// disable gform fields
			jQuery('form.hide input, form.hide textarea, form.hide select').attr('disabled','disabled');

		}
	}

	/**
	 * On successful login, hide tab controls & show thank you screen
	 * @param response
	 */
	function loginSuccess(eventObj) {

		// Display thank you screen
		gigya.accounts.showScreenSet({
			screenSet:'Survey-registration',
			startScreen:'gigya-thank-you-screen',
			containerID:'gigya-controls'
		});

		// hide login/registration form
		jQuery('#gigya-login-wrap').hide();

		// show gravity form
		jQuery('form.hide input, form.hide textarea, form.hide select').removeAttr('disabled');
		jQuery('form.hide').removeClass('hide');

	}

	init();

})(this);