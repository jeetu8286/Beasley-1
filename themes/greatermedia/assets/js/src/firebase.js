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

		var ssAuth = window.SecondStreetThirdPartyAuth || false;
		if (ssAuth) {
			if (user) {
				ssAuth.triggerLoginHandlers();
			} else {
				ssAuth.triggerLogoutHandlers();
			}
		}
	});
})(jQuery, window.platformConfig.firebase);
