jQuery(function () {

	// Hide content if the criteria (logged in or not logged in) is met
	function show_shield() {
		var post_id = jQuery(this).data('postid');
		jQuery(this).hide();
		jQuery('.login-restricted-shield').filter('[data-postid=' + post_id + ']').show();
	}

	if (window.is_gigya_user_logged_in && is_gigya_user_logged_in()) {
		jQuery('.login-restricted-content').filter('[data-status="logged-out"]').each(show_shield);
	}
	else {
		jQuery('.login-restricted-content').filter('[data-status="logged-in"]').each(show_shield);
	}

});