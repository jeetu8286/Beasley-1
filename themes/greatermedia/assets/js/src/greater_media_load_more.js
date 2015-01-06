(function ($, gmr) {
	var __ready = function() {
		var sync = false;

		$('.posts-pagination').on('click', '.posts-pagination--load-more', function() {
			var $button = $(this);
			
			if (!sync) {
				sync = true;
				$button.addClass('loading');

				// let's use ?ajax=1 to distinguish AJAX and non AJAX requests
				// if we don't do it and enabled HTTP caching, then we might encounter
				// unpleasant condition when users see cached version of a page loaded by AJAX
				// instead of normal one.
				$.get(gmr.pattern.replace('{{page}}', ++gmr.page), {ajax: 1}).done(function(response) {
					sync = false;
					$button.removeClass('loading');
					
					$($('<div>' + $.trim(response) + '</div>').html()).insertAfter($(document).find('*[role="article"]:last'));
				}).fail(function() {
					$button.attr('disabled', 'disabled').text(gmr.not_found);
				});
			}
			
			return false;
		});
	};

	$(document).bind('pjax:end', __ready).ready(__ready);
})(jQuery, gmr_load_more);