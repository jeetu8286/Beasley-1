(function($, config) {
	var auth;

	// do nothing if apiKey is not set
	if (!config.apiKey || $.trim(config.apiKey).length < 1) {
		return;
	}

	// initialize a firebase instance
	firebase.initializeApp(config);

	// grab auth service
	auth = firebase.auth();

	// listen to auth state change and authenticate an user anonymously if user is not logged in
	auth.onAuthStateChanged(function(user) {
		if (!user) {
			auth.signInAnonymously();
		}

		if (window.SecondStreetThirdPartyAuth) {
			if (user) {
				callLoginHandlers();
			} else {
				callLogoutHandlers();
			}
		}
	});
})(jQuery, window.platformConfig.firebase);
