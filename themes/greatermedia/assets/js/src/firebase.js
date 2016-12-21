(function($, config) {
	var $document = $(document),
		database,
		auth,
		uid,
		data = {
			plays: 0,
			hours: []
		};

	// do nothing if apiKey is not set
	if ($.trim(config.apiKey).length < 1) {
		return;
	}

	// initialize a firebase instance
	firebase.initializeApp(config);

	// grab auth and database services
	auth = firebase.auth();
	database = firebase.database();

	// listen to auth state change and authenticate an user anonymously if user is not logged in
	auth.onAuthStateChanged(function(user) {
		if (user) {
			var userRef = database.ref('users/' + user.uid);

			uid = user.uid;
			userRef.on('value', function(snapshot) {
				data = snapshot.val() || data;
			});
		} else {
			auth.signInAnonymously();
		}
	});

	// listen to player start event to track listenings count
	$document.on('player:starts', function() {
		var date = new Date(),
			hour = date.getHours();

		if (uid) {
			data.plays++;
			data.hours = data.hours || [];
			data.hours[hour] = data.hours[hour] || 0;
			data.hours[hour]++;

			database.ref('users/' + uid).set(data);
		}
	});
})(jQuery, window.beasley.firebase);