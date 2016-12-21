(function($, config) {
	var $document = $(document),
		database,
		auth,
		uid,
		data = {
			plays: 0,
			hours: []
		};

	firebase.initializeApp(config);

	auth = firebase.auth();
	database = firebase.database();

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