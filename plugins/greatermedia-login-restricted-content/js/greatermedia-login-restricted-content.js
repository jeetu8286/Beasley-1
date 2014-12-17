jQuery(function () {

	// Hide shields by default
	jQuery('.login-restricted-shield').hide();

	// Hide content if the criteria (logged in or not logged in) is met
	function show_shield() {
		var post_shield_id = jQuery(this).attr('id').replace('login-restricted-post-', 'login-restricted-shield-');
		jQuery(this).hide();
		jQuery('#' + post_shield_id).show();
	}

	if (window.is_gigya_user_logged_in && is_gigya_user_logged_in()) {
		jQuery('.login-restricted-content').filter('.login-restricted-logged-out').each(show_shield);
	}
	else {
		jQuery('.login-restricted-content').filter('.login-restricted-logged-in').each(show_shield);
	}

});