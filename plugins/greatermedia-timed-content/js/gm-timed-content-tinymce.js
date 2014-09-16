(function () {
	tinymce.PluginManager.add('gm_timed_content_mce_button', function (editor, url) {
		editor.addButton('gm_timed_content_mce_button', {
			text   : 'Time Restrict',
			icon   : false,
			onclick: function () {
				editor.windowManager.open({
					title   : 'Timed Content',
					body    : [
						{
							type : 'textbox',
							name : 'show',
							label: 'Show content on',
							value: ''
						},
						{
							type : 'textbox',
							name : 'hide',
							label: 'Hide content on',
							value: ''
						},

					],
					onsubmit: function (e) {

						var show_attr = '', hide_attr = '', content = tinymce.activeEditor.selection.getContent();

						if(e.data.show && '' !== e.data.show ) {
							show_attr = 'show="' + e.data.show + '"';
						}
						else {
							show_attr = '';
						}

						if(e.data.hide && '' !== e.data.hide) {
							hide_attr = 'hide="' + e.data.hide + '"';
						}
						else {
							hide_attr = '';
						}

						editor.insertContent('[time-restricted ' + show_attr + ' ' + hide_attr + ']' + content + '[/time-restricted]');

					}
				});
			}
		});
	});
})();