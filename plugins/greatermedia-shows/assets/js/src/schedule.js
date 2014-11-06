(function ($) {
	$(document).ready(function () {
		var hovered_show, original_color;
		
		$('#start-from-date').datepicker({
			dateFormat : 'M d, yy',
			minDate: '+1d',
			altField: '#start-from-date-value',
			altFormat: 'yy-mm-dd'
		});

		$('#schedule-table td > div').hover(function() {
			var $this = $(this);

			hovered_show = '.' + $this.attr('class');

			original_color = $this.css('background-color');
			$(hovered_show).css('background-color', $this.attr('data-hover-color'));
		}, function() {
			$(hovered_show).css('background-color', original_color);
		});
	});
})(jQuery);