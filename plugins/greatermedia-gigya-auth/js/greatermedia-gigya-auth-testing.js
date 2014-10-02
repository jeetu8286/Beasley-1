/**
 * Stub for Gigya JavaScript API. Build out as needed, but beware of YAGNI
 * http://developers.gigya.com/020_Client_API/020_Accounts/accounts.addEventHandlers
 */
var gigya = gigya || {};
gigya.accounts = gigya.accounts || {};
gigya.accounts.eventHandlers = gigya.accounts.eventHandlers || {};

/**
 * @see http://developers.gigya.com/020_Client_API/020_Accounts/accounts.addEventHandlers
 * @type {object}
 */
gigya.accounts.addEventHandlers = gigya.accounts.addEventHandlers || function (params) {

	// I have no idea how Gigya keeps track of multiple even handlers internally. I'm just faking it to get this done.
	var uniqid = Math.floor((Math.random() * 100000) + 1);

	if (undefined === gigya.accounts.eventHandlers[uniqid]) {
		gigya.accounts.eventHandlers[uniqid] = {};
	}

	if (undefined !== params.onLogin) {
		gigya.accounts.eventHandlers[uniqid].login = params.onLogin;
	}

	if (undefined !== params.onLogout) {
		gigya.accounts.eventHandlers[uniqid].logout = params.onLogout;
	}

	if (undefined !== params.cid) {
		gigya.accounts.eventHandlers[uniqid].cid = params.cid;
	}

	if (undefined !== params.callback) {
		gigya.accounts.eventHandlers[uniqid].callback = params.callback;
	}

	if (undefined !== params.context) {
		gigya.accounts.eventHandlers[uniqid].cid = params.context;
	}

	return {
		errorCode   : 0,
		errorMessage: '',
		operation   : 'addEventHandlers',
		context     : params.context || {}
	};

}

gigya.accounts._callEventHandlers = gigya.accounts._callEventHandlers || function (event_name) {

	var event_data = {
		eventName: event_name,
	}

	console.log('called ' + event_name);
	console.log(gigya.accounts.eventHandlers);

	for (var registration_index in gigya.accounts.eventHandlers) {
		if (gigya.accounts.eventHandlers.hasOwnProperty(registration_index)) {
			if (undefined !== gigya.accounts.eventHandlers[registration_index][event_name]) {

				event_data.context = gigya.accounts.eventHandlers[registration_index].context || undefined;
				if ('login' === event_name) {
					event_data = {
						eventName         : event_name,
						context           : gigya.accounts.eventHandlers[registration_index].context || undefined,
						UID               : '12345',
						UIDSignature      : '12345',
						signatureTimestamp: new Date().getTime(),
						loginMode         : 'standard',
						provider          : 'Twitter',
						profile           : {},
						data              : {},
						remember          : true
					};
				}

				gigya.accounts.eventHandlers[registration_index][event_name].call(window, event_data);
			}
		}
	}
}

jQuery(function () {

	// Default the checkbox to the correct status
	if (GreaterMediaGigyaAuth.is_gigya_user_logged_in()) {
		jQuery('#myonoffswitch').prop('checked', 'checked');
	}

	// Trigger custom events
	jQuery('#greatermedia-gigya-auth-testing').on('click', '#myonoffswitch', function () {

		var event;

		if (jQuery('#myonoffswitch').is(':checked')) {
			// Logged into gigya
			gigya.accounts._callEventHandlers('login');
		}
		else {
			// Logged out of gigya
			gigya.accounts._callEventHandlers('logout');
		}



	});

});