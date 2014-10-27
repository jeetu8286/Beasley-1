jQuery(function () {

	var livePlayerListen = jQuery('#live-player--listen_now'),
		livePlayerTest = jQuery('.live-player--test');

	function listenLive() {
		livePlayerListen.css('visibility', 'visible');

		livePlayerListen.click(function() {
			if ( livePlayerTest.css('visibility') == 'visible') {
				livePlayerTest.css('visibility', 'hidden');
				livePlayerListen.css('visibility', 'visible');
			} else {
				livePlayerTest.css('visibility', 'visible');
				livePlayerListen.css('visibility', 'hidden');
			}
		});
	}

	listenLive();

	function showPlayer() {

		var livePlayer = jQuery('.gm-liveplayer'),
			livePlayerSwitch = jQuery('.live-player--test');

		livePlayer.css('display', 'none');

		livePlayerSwitch.click(function() {
			livePlayer.toggle(this.checked);
		});
	}

	showPlayer();

});