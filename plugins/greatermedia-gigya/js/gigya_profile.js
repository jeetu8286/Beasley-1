(function($) {

	var get_current_screen_name = function() {
		switch (gigya_profile_meta.current_page) {
			case 'register':
				return 'gigya-register-screen';

			case 'login':
				return 'gigya-login-screen';

			case 'forgot-password':
				return 'gigya-forgot-password-screen';

			case 'logout':
				// WIP
				return 'gigya-logout-screen';

			default:
				return 'gigya-register-screen';
		}
	};

	$(document).ready(function() {
		gigya.accounts.showScreenSet({
			screenSet: 'GMR-RegistrationLogin',
			startScreen: get_current_screen_name(),
			containerID: 'profile-content'
		});
	});

}(jQuery));
