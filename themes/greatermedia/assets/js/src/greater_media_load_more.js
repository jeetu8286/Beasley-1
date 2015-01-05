(function ($, gmr) {
	var __ready = function() {
		var sync = false;

		$('.posts-pagination').on('click', '.posts-pagination--load-more', function() {
			var $button = $(this);
			
			if (!sync) {
				sync = true;
				$button.addClass('loading');
				
				$.get(gmr.pattern.replace('{{page}}', ++gmr.page), {ajax: 1}).done(function(results) {
					sync = false;
					$button.removeClass('loading');
				}).fail(function() {
					$button.attr('disabled', 'disabled').text(gmr.not_found);
				});
			}
			
			return false;
		});
	};

	$(document).bind('pjax:end', __ready).ready(__ready);
})(jQuery, gmr_load_more);