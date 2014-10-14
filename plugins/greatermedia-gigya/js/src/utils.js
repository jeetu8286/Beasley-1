var escapeValue = function(source) {
	if (typeof(source) === 'string') {
		source = source.replace(/"/g, 'C_DOUBLE_QUOTE');
		source = source.replace(/'/g, 'C_SINGLE_QUOTE');
		source = source.replace(/\\/g, 'C_BACKSLASH');
	}

	return source;
};

var unescapeValue = function(source) {
	if (typeof(source) === 'string') {
		source = source.replace(/C_DOUBLE_QUOTE/g, '"');
		source = source.replace(/C_SINGLE_QUOTE/g, "'");
		source = source.replace(/C_BACKSLASH/g, "\\");
	}

	return source;
};

var getTemplate = function(name) {
	return window.JST['src/templates/' + name + '.jst'];
};

var renderTemplate = function(name, data, settings) {
	if (!settings) {
		settings = {};
	}

	var template = getTemplate(name);
	var html     = template(data);

	return $(html);
};

if (typeof Object.create != 'function') {
	Object.create = (function() {
		var Object = function() {};
		return function (prototype) {
			if (arguments.length > 1) {
				throw Error('Second argument not supported');
			}
			if (typeof prototype != 'object') {
				throw TypeError('Argument must be an object');
			}
			Object.prototype = prototype;
			var result = new Object();
			Object.prototype = null;
			return result;
		};
	})();
}

var WpAjaxApi = function(config) {
	this.config = config;
};

WpAjaxApi.prototype = {

	nonceFor: function(action) {
		return this.config[action + '_nonce'];
	},

	urlFor: function(action) {
		var queryParams = {};
		queryParams[action + '_nonce'] = this.nonceFor(action);

		var url = this.config.ajax_url;
		url += url.indexOf('?') === -1 ? '?' : '&';
		url += $.param(queryParams);

		return url;
	},

	request: function(action, data) {
		if (!data) {
			data = {};
		}

		var url         = this.urlFor(action);
		var requestData = {
			'action': action,
			'action_data': JSON.stringify(data)
		};

		return $.post(url, requestData);
	},

};

