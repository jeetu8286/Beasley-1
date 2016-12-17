/*! Greater Media Contests - v1.3.0
 * http://10up.com/
 * Copyright (c) 2016;
 * Licensed GPLv2+
 */
(function ($, wp) {
	var _wpmce,
		_ss,
		_ss_shortcode = 'ss-promo';

	_wpmce = wp.mce = wp.mce || {};

	_ss = _wpmce.secondstreet = {
		template: _.template('<script src="//embed-<%= op_id %>.secondstreetapp.com/Scripts/dist/embed.js" data-ss-embed="promotion" data-opguid="<%= op_guid %>" data-routing="<%= routing %>"></script>'),

		getContent: function () {
			return this.template(this.shortcode.attrs.named);
		},

		edit: function (data, update) {
			var shortcode_data = wp.shortcode.next(_ss_shortcode, data),
				values = shortcode_data.shortcode.attrs.named;
			values['innercontent'] = shortcode_data.shortcode.content;
			_ss.popupwindow(tinyMCE.activeEditor, values);
		},

		popupwindow: function (editor, values) {
			var _values = $.extend({
				op_id: '',
				op_guid: '',
				routing: ''
			}, values);

			editor.windowManager.open({
				title: 'SecondStreet',
				body: [
					{
						type: 'textbox',
						name: 'op_id',
						label: 'op_id',
						value: _values['op_id']
					},
					{
						type: 'textbox',
						name: 'op_guid',
						label: 'op_guid',
						value: _values['op_guid']
					},
					{
						type: 'textbox',
						name: 'routing',
						label: 'routing',
						value: _values['routing']
					}
				],
				onsubmit: function (e) {
					var content = '[' + _ss_shortcode;

					for (var attr in e.data) {
						if (e.data.hasOwnProperty(attr) && attr !== 'innercontent') {
							content += ' ' + attr + '="' + e.data[attr] + '"';
						}
					}

					content += ']';

					if (typeof e.data.innercontent !== 'undefined') {
						content += e.data.innercontent;
						content += '[/' + _ss_shortcode + ']';
					}

					editor.insertContent(content);
				}
			});
		}
	};

	_wpmce.views.register(_ss_shortcode, _ss);

	tinymce.PluginManager.add('secondstreet', function(editor, url) {
		editor.addButton('secondstreet', {
			tooltip: 'SecondStreet',
			image: url + '/../img/sslogo.png',
			onclick: function () {
				_ss.popupwindow(editor, {});
			}
		});
	});
})(jQuery, wp);