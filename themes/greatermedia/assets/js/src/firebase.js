(function(config) {
	firebase.initializeApp(config);

	firebase.auth().signInAnonymously().catch(function(error) {
		console.error(error.message);
	});

	firebase.auth().onAuthStateChanged(function(user) {
		var isAnonymous, uid;

		if (user) {
			isAnonymous = user.isAnonymous;
			uid = user.uid;
		}
	});
})(window.beasley.firebase);