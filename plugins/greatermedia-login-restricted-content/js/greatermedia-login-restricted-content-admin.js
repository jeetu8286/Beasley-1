jQuery(function () {

	window.GreaterMediaLoginRestrictedContentAdmin = (function () {

		var module = {};

		module.template = GreaterMediaLoginRestrictedContent.templates.tinymce;

		module.create_popup_title = GreaterMediaLoginRestrictedContent.strings['Login Restricted Content'];
		module.edit_popup_title = GreaterMediaLoginRestrictedContent.strings['Login Restricted Content'];
		
		module.view_gethtml = function () {

			var attrs = this.shortcode.attrs.named,
				options = {
					content: this.shortcode.content,
					status : undefined
				};

			if (attrs.status) {
				options.status = attrs.status;
			}

			return this.template(options);

		};
		
		/**
		 * Process toolbar button click event.
		 *
		 * @param Event submit_event
		 * @returns {{status: *}}
		 */
		module.toolbar_button_action = function (submit_event) {
			return {
				status: 'logged-in'
			}
		};

		// Implement the postbox feature
		var login_restriction_div = jQuery('#loginrestrictiondiv');
		login_restriction_div.html(GreaterMediaLoginRestrictedContent.templates.login_restriction);

		// Show the radio buttons
		jQuery("a[href='#edit_login_restriction']").click(function () {

			login_restriction_div.slideDown();

			if (true !== login_restriction_div.data('populated')) {

				login_restriction_div.find('input').filter('[name=lr_status]').filter('[value="' + jQuery('#hidden_login_restriction').val() + '"]').attr('checked', 'checked');

				login_restriction_div.data('populated', true);
			}

		});

		// Cancel button
		login_restriction_div.find('.cancel-login-restriction').click(function () {

			login_restriction_div.find('input').filter('[name=lr_status]').filter('[value="' + jQuery('#hidden_login_restriction').val() + '"]').attr('checked', 'checked');

			login_restriction_div.slideUp();

		});

		// Update hidden fields
		login_restriction_div.find('.save-login-restriction').click(function () {

			var checked_option = login_restriction_div.find('input').filter('[name=lr_status]').filter(':checked');

			jQuery('#hidden_login_restriction').val(checked_option.val());

			jQuery('#loginrestrictiondiv').slideUp();

			jQuery('#login-restriction-value').find('b').text(checked_option.parent().text());

		});

		return module;

	})();

});