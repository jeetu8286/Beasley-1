/*! Greater Media Prototype - v0.1.0 - 2014-10-08
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

	};

	var AccountMenuView = function(session) {
		this.session = session;
		this.session.on('change', $.proxy(this.didSessionChange, this));

		this.container = $('.account');
		this.container.on('click', $.proxy(this.didContainerClick, this));
	};

	AccountMenuView.prototype = {

		render: function() {
			var authorized = this.session.isAuthorized();

			$('.login').css('visibility', 'visible');
			$('.register').css('visibility', authorized ? 'hidden' : 'visible');

			if (authorized) {
				$('.login').text('Logout');
			} else {
				$('.login').text('Login');
			}
		},

		didContainerClick: function(event) {
			var className = $(event.target).attr('class');

			switch (className) {
				case 'register':
					this.showRegisterScreen();
					return false;

				case 'login':
					if (!this.session.isAuthorized()) {
						this.showLoginScreen();
					} else {
						this.showLogoutScreen();
					}
					return false;

			}
		},

		didSessionChange: function() {
			this.render();
		},

		showScreenSet: function(name) {
			gigya.accounts.showScreenSet({
				screenSet: 'GMR-RegistrationLogin',
				startScreen: name
			});
		},

		showLoginScreen: function() {
			this.showScreenSet('gigya-login-screen');
		},

		showRegisterScreen: function() {
			/* TODO: Find Gigya's onRegister event */
			this.session.willRegister = true;
			this.showScreenSet('gigya-register-screen');
		},

		showLogoutScreen: function() {
			gigya.accounts.logout({
				cid: this.session.get_cid(),
				callback: $.proxy(this.refresh, this)
			});
		},

		refresh: function() {
			location.reload();
		}

	};

	$(document).ready(function() {
		var sessionData    = window.gigya_session_data || { data: {} };
		sessionData = sessionData.data;

		var session         = new GigyaSession(sessionData);
		var accountMenuView = new AccountMenuView(session);

		session.authorize();
	});

})(this);
