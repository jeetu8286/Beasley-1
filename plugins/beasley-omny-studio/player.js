(function($, playerjs) {
	var $document = $(document);

	var connectToEmbeds = function() {
		$('iframe[src^="https://omny.fm/"]').each(function () {
			var player = new playerjs.Player(this);

			player.on(playerjs.EVENTS.READY, function() {
				var pausePlayer = function() {
					player.pause();
				};

				var customAudio = window.customAudio || false;
				if (customAudio) {
					if (customAudio.addEventListener) {
						customAudio.addEventListener('playing', pausePlayer);
					} else if (customAudio.attachEvent) {
						customAudio.attachEvent('playing', pausePlayer);
					}
				}

				var streamAudio = window.player || false;
				if (streamAudio) {
					if (streamAudio.addEventListener) {
						streamAudio.addEventListener('stream-start', pausePlayer);
						streamAudio.addEventListener('ad-playback-start', pausePlayer);
					} else if (player.attachEvent) {
						streamAudio.attachEvent('stream-start', pausePlayer);
						streamAudio.attachEvent('ad-playback-start', pausePlayer);
					}
				}
			});

			player.on(playerjs.EVENTS.PLAY, function() {
				var customAudio = window.customAudio || false;
				var streamAudio = window.player || false;

				if (customAudio) {
					customAudio.pause();
				}

				if (streamAudio) {
					streamAudio.pause();
				}
			});
		});
	};

	$document.bind('pjax:end', connectToEmbeds).ready(connectToEmbeds);
})(jQuery, playerjs);
