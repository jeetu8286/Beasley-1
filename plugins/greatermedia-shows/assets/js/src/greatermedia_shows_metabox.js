(function ($) {
	$(document).ready(function () {
		$('.mis-pub-radio').each(function() {
			var $this = $(this),
				$switchSelect = $this.find('.radio-select'),
				$editLink = $this.find('.edit-radio'),
				origin_value = $switchSelect.find('input:radio:checked').val();

			$editLink.click(function() {
				if ($switchSelect.is(':hidden')) {
					$switchSelect.slideDown('fast').find('input[type="radio"]').first().focus();
					$(this).hide();
				}
				return false;
			});

			$switchSelect.find('.cancel-radio').click(function() {
				$switchSelect.slideUp('fast', function() {
					$editLink.show().focus();

					$switchSelect.find('input:radio').each(function() {
						$(this).prop('checked', $(this).val() === origin_value);
					});
				});

				return false;
			});

			$switchSelect.find('.save-radio').click(function() {
				var selected = $switchSelect.find('input:radio:checked');

				$switchSelect.slideUp('fast', function() {
					$editLink.show();

					origin_value = selected.val();
					$this.find('.radio-value').text(selected.parent().text());
				});

				return false;
			});
		});
	});
})(jQuery);

(function ($) {
	$(document).ready(function () {
		var $this = $('#show-schedule'),
			$switchSelect = $this.find('.schedule-select'),
			$editLink = $this.find('.edit-schedule'),
			$time = $switchSelect.find('select[name="show_schedule_time"]'),
			selected_time = $time.val(),
			selected_days = [],
			selected_day_labels = [],
			update_selected_days;

		update_selected_days = function() {
			selected_days = [];
			selected_day_labels = [];

			$switchSelect.find('input:checkbox:checked').each(function() {
				selected_days.push($(this).val());
				selected_day_labels.push($(this).attr('data-abbr'));
			});
		};

		$editLink.click(function() {
			if ($switchSelect.is(':hidden')) {
				$switchSelect.slideDown('fast');
				$(this).hide();
			}
			return false;
		});

		$switchSelect.find('.cancel-schedule').click(function() {
			$switchSelect.slideUp('fast', function() {
				$editLink.show().focus();

				$time.val(selected_time);
				$switchSelect.find('input:checkbox').each(function() {
					$(this).prop('checked', $.inArray($(this).val(), selected_days) >= 0);
				});
			});

			return false;
		});

		$switchSelect.find('.save-schedule').click(function() {
			selected_time = $time.val();
			update_selected_days();

			$switchSelect.slideUp('fast', function() {
				$editLink.show();

				if (selected_time === '' || selected_days.length === 0) {
					$this.find('.schedule-value').html('&#8212;');
				} else {
					$this.find('.schedule-value').text($time.find('option:selected').text() + ' on ' + selected_day_labels.join(', '));
				}
			});

			return false;
		});

		update_selected_days();
	});
})(jQuery);