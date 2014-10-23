((function (window, document, undefined) {
	document.addEventListener('DOMContentLoaded', function () {

		var wall_index, current_wall;

		for (wall_index in LiveFyreMediaWall.walls) {
			if (LiveFyreMediaWall.walls.hasOwnProperty(wall_index)) {

				current_wall = LiveFyreMediaWall.walls[wall_index];

				/**
				 * Initialize a media wall
				 * @see http://answers.livefyre.com/developers/app-integrations/live-media-wall/
				 */
				Livefyre.require(
					// Libraries from the LiveFyre SDK
					['streamhub-wall#3', 'streamhub-sdk#2'],
					function (LiveMediaWall, SDK) {

						var wall = window.wall = new LiveMediaWall({
							el        : document.getElementById(current_wall['element_id']),
							initial   : current_wall['initial'],
							modal     : ('modal' === current_wall['modal']) ? true : false,
							columns   : current_wall['columns'],
							postButton: false,
							collection: new (SDK.Collection)({
								"network"  : current_wall['network'],
								"siteId"   : current_wall['site'],
								"articleId": current_wall['id']
							})
						});

					}
				);

			}
		}

	});
})(window, document));