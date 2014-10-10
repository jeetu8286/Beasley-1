jQuery(function () {

	jQuery('#exptimestampdiv').html(GreaterMediaTimedContent.templates.expiration_time);

	// Show the time entry boxes
	jQuery("a[href='#edit_exptimestamp']").click(function () {

		jQuery('#exptimestampdiv').slideDown();

		if (true !== jQuery('#exptimestampdiv').data('populated')) {

			jQuery('#exp_mm').val(jQuery('#hidden_exp_mm').val());
			jQuery('#exp_jj').val(jQuery('#hidden_exp_jj').val());
			jQuery('#exp_aa').val(jQuery('#hidden_exp_aa').val());
			jQuery('#exp_hh').val(jQuery('#hidden_exp_hh').val());
			jQuery('#exp_mn').val(jQuery('#hidden_exp_mn').val());

			jQuery('#exptimestampdiv').data('populated', true);
		}

	});

	// Cancel button
	jQuery('#exptimestampdiv').find('.cancel-timestamp').click(function () {

		jQuery('#exp_mm').val(jQuery('#hidden_exp_mm').val());
		jQuery('#exp_jj').val(jQuery('#hidden_exp_jj').val());
		jQuery('#exp_aa').val(jQuery('#hidden_exp_aa').val());
		jQuery('#exp_hh').val(jQuery('#hidden_exp_hh').val());
		jQuery('#exp_mn').val(jQuery('#hidden_exp_mn').val());

		jQuery('#exptimestampdiv').slideUp();

	})

	// Update hidden fields
	jQuery('#exptimestampdiv').find('.save-timestamp').click(function () {

		jQuery('#hidden_exp_mm').val(jQuery('#exp_mm').val());
		jQuery('#hidden_exp_jj').val(jQuery('#exp_jj').val());
		jQuery('#hidden_exp_aa').val(jQuery('#exp_aa').val());
		jQuery('#hidden_exp_hh').val(jQuery('#exp_hh').val());
		jQuery('#hidden_exp_mn').val(jQuery('#exp_mn').val());

		jQuery('#exptimestampdiv').slideUp();

		var expiration_date = new Date();
		expiration_date.setMonth(parseInt(jQuery('#exp_mm').val(), 10) - 1);
		expiration_date.setDate(jQuery('#exp_jj').val());
		expiration_date.setFullYear(jQuery('#exp_aa').val());
		expiration_date.setHours(jQuery('#exp_hh').val());
		expiration_date.setMinutes(jQuery('#exp_mn').val())

		jQuery('#exptimestamp').find('b').text(expiration_date.format(GreaterMediaTimedContent.formats.date));

	});

	// Remove expiration timestamp
	jQuery('#exptimestampdiv').find('.remove-timestamp').click(function () {

		jQuery('#exp_mm, #hidden_exp_mm').val('');
		jQuery('#exp_jj, #hidden_exp_jj').val('');
		jQuery('#exp_aa, #hidden_exp_aa').val('');
		jQuery('#exp_hh, #hidden_exp_hh').val('');
		jQuery('#exp_mn, #hidden_exp_mn').val('');

		jQuery('#exptimestampdiv').slideUp();
		jQuery('#exptimestamp').find('b').text(GreaterMediaTimedContent.strings.never);

	});

});