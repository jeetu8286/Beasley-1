(function($) {

	console.log('sample.js')

	var SamplePlugin = function() {

	};

	SamplePlugin.prototype = $.extend(new VisualShortcodeRedux.Plugin(), {

		getName: function() {
			return 'sample';
		}

	});

	$(document).ready(function() {
		//var plugin = new SamplePlugin();
		//plugin.register();
	});

}(jQuery));
