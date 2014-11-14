(function($) {

	/*
	 * Lite Version of Gigya Profile. This gets loaded on non profile
	 * pages and does not have the ability to modify the session.
	 *
	 */
	var GigyaSessionStore = function() {
		this.cookieValue = {};
	};

	GigyaSessionStore.prototype = {

		isEnabled: function() {
			return Cookies.enabled;
		},

		get: function(key) {
			return this.cookieValue[key];
		},

		load: function() {
			if (!this.isEnabled()) {
				return;
			}

			var cookieText  = Cookies.get(this.getCookieName());
			this.cookieValue = this.deserialize(cookieText);
		},

		getCookieOptions: function(persistent) {
			return {
				path    : this.getCookiePath(),
				domain  : this.getCookieDomain(),
				secure  : this.isSecurePage(),
				expires : this.getCookieTimeout(persistent)
			};
		},

		getCookieName: function() {
			return 'gigya_profile';
		},

		getCookiePath: function() {
			return '/';
		},

		getCookieDomain: function() {
			return location.hostname;
		},

		getCookieTimeout: function(persistent) {
			// TODO: must mirror gigya sessions
			if (persistent) {
				return 365 * 24 * 60 * 60; // 1 year
			} else {
				return 30 * 60; // 30 minutes
			}
		},

		deserialize: function(cookieText) {
			if (cookieText) {
				var cookieValue;

				try {
					if (atob) {
						cookieText = atob(cookieText);
					}
					cookieValue = JSON.parse(cookieText);
				} finally {
					if (!this.isObject(cookieValue)) {
						cookieValue = {};
					}
				}

				return cookieValue;
			} else {
				return {};
			}
		},

		isObject: function(obj) {
			return (!!obj) && (obj.constructor === Object);
		},

		isSecurePage: function() {
			return location.protocol === 'https:';
		},

	};

	var GigyaSession = function(store) {
		this.store   = store;
	};

	GigyaSession.prototype = {

		isEnabled: function() {
			return this.store.isEnabled();
		},

		isLoggedIn: function() {
			return !!this.store.get('UID');
		},

		getUserID: function() {
			return this.getUserField('UID');
		},

		getUserField: function(field) {
			if (this.isLoggedIn()) {
				return this.store.get(field);
			} else {
				return null;
			}
		}

	};

	var store   = new GigyaSessionStore();
	var session = new GigyaSession(store);

	store.load();

	window.is_gigya_user_logged_in = function() {
		return session.isEnabled() && session.isLoggedIn();
	};

	window.get_gigya_user_id = function() {
		return session.getUserID();
	};

	window.get_gigya_user_field = function(field) {
		return session.getUserField(field);
	};

}(jQuery));
