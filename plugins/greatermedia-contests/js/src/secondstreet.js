(function ($, document) {
	var $document = $(document),
		__ready;

	__ready = function() {
		$('div[data-ss-embed]').each(function() {
			var $ssembed = $(this),
				$script = $(document.createElement('script'));

			$.each($ssembed.prop("attributes"), function() {
				$script.attr(this.name, this.value);
			});

			$ssembed.replaceWith($script);
		});
	};

	$document.bind('pjax:end', __ready).ready(__ready);
})(jQuery, document);