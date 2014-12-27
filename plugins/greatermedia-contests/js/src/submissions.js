(function ($, gmr) {
	var load_infinite,
		document_ready,
		min_count,
		load_more,
		submissions_list,
		infinite_id = 1,
		infinite_lock = false;

	load_infinite = function () {
		if (true === infinite_lock) {
			return;
		}

		infinite_lock = true;
		load_more.addClass('loading');

		infinite_id++;
		$.get(gmr.endpoints.infinite + infinite_id + '/', function(data) {
			var galleries = $.trim(data);

			if (galleries.length > 0) {
				submissions_list.append(galleries);
				submissions_list.gridPreview();
				
				if ($(galleries).find('> *').length >= min_count) {
					$.waypoints('refresh');
					infinite_lock = false;
					load_more.removeClass('loading');
				} else {
					load_more.hide();
				}
			} else {
				load_more.hide();
			}
		});
	};

	document_ready = function() {
		submissions_list = $('.contest-submissions--list');
		submissions_list.gridPreview();
		min_count = submissions_list.find('> *').length;

//		submissions_list.on('click', '.contest-submission--link', function() {
//			var li = $(this).parent();
//
//			submissions_list.find('.expanded div').remove();
//			submissions_list.find('.expanded').removeClass('expanded');
//
//			li.addClass('expanded');
//			li.append($('#contest-submissions--tmpl').html());
//
//			return false;
//		});
		
		load_more = $('.contest-submissions--load-more');
		load_more.on('click', load_infinite);

		$('.footer').waypoint({
			offset: '150%',
			triggerOnce: false,
			handler: function(direction) {
				if (direction === 'down') {
					load_infinite();
				}
			}
		});

		$.waypoints('refresh');
	};

	$(document).bind('pjax:end', document_ready).ready(document_ready);
})(jQuery, GreaterMediaContests);