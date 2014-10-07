(function($) {

	var GigyaSession     = function(sessionData) {
		this.sessionData = sessionData;
		this.authorized  = false;
		this.willRegister = false;
		this.mediator    = $({});

		gigya.accounts.addEventHandlers({
			onLogin: $.proxy(this.didLogin, this),
			onLogout: $.proxy(this.didLogout, this)
		});
	};

	GigyaSession.prototype = {

		get_cid: function() {
			return this.sessionData.cid || '';
		},

		isAuthorized: function() {
			return this.authorized;
		},

		authorize: function() {
			var cid = this.get_cid();

			if (cid !== '') {
				gigya.accounts.getAccountInfo({
					cid: cid,
					callback: $.proxy(this.gotAccountInfo, this)
				});
			} else {
				this.notify();
			}
		},

		gotAccountInfo: function(response) {
			if (response.errorCode === 0) {
				this.authorized = true;
				this.account    = response;
			} else {
				this.authorized = false;
			}

			this.notify();
		},

		on: function(event, listener) {
			this.mediator.on(event, listener);
		},

		notify: function(event) {
			if (!event) {
				event = 'change';
			}

			this.mediator.trigger(event, this);
		},

		didLogin: function(response) {
			if (this.willRegister) {
				this.didRegister(response);
			}

			this.account    = response;
			this.authorized = true;
			this.notify();
		},

		didRegister: function(response) {
			this.willRegister = false;

			var listNames = [];

			// TODO: fix this after Gigya support ticket response
			if (response.data.vipGroup) {
				listNames.push('VIP Newsletter');
			}

			if (response.data.birthdayGreetingsGroup) {
				listNames.push('Birthday Greetings');
			}

			if (response.data.bigFrigginDealGroup) {
				listNames.push('Big Deal');
			}

			var data  = {
				'action': 'register_account',
				'action_data': JSON.stringify({
					'UID': response.UID,
					'listNames': listNames
				})
			};

			var url = this.sessionData.ajaxurl + '?' + $.param({
				'register_account_nonce': this.sessionData.register_account_nonce,
			});

			var promise = $.post(url, data);

			promise
				.then($.proxy(this.didRegisterRelay, this))
				.fail($.proxy(this.didRegisterRelayError, this));
		},

		didRegisterRelay: function(response) {
			location.reload();
		},

		didRegisterRelayError: function(response) {
			// TODO
			console.log('didRegisterRelayError', response);
		},

		didLogout: function(response) {
			this.authorized = false;
			this.notify();
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

}(jQuery));
