(function($) {

	var WpAjaxApi = function(config) {
		this.config = config;
	};

	WpAjaxApi.prototype = {

		nonceFor: function(action) {
			return this.config[action + '_nonce'];
		},

		urlFor: function(action) {
			var queryParams = {};
			queryParams[action + '_nonce'] = this.nonceFor(action);

			var url = this.config.ajax_url;
			url += url.indexOf('?') === -1 ? '?' : '&';
			url += $.param(queryParams);

			return url;
		},

		request: function(action, data) {
			if (!data) {
				data = {};
			}

			var url         = this.urlFor(action);
			var requestData = {
				'action': action,
				'action_data': JSON.stringify(data)
			};

			return $.post(url, requestData);
		},

	};

	var GigyaSession      = function(sessionData, ajaxApi) {
		this.sessionData  = sessionData;
		this.ajaxApi      = ajaxApi;
		this.authorized   = false;
		this.willRegister = false;
		this.mediator     = $({});

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
			var target = $(event.target);

			if (target.attr('id') === 'register-button') {
				this.showRegisterScreen();
				return false;
			} else if (target.attr('id') === 'login-button') {
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

	};

	$(document).ready(function() {
		var sessionData     = window.gigya_session_data.data;
		var ajaxApi         = new WpAjaxApi(sessionData);
		var session         = new GigyaSession(sessionData, ajaxApi);
		var accountMenuView = new AccountMenuView(session);

		session.authorize();
	});

	/* jquery */
}(jQuery));
