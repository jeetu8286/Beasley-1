(function ($) {
	$(document).ready(function () {
		$('#start-from-date').datepicker({
			dateFormat : 'M d, yy',
			minDate: 'now',
			altField: '#start-from-date-value',
			altFormat: 'yy-mm-dd'
		});
	});
})(jQuery);