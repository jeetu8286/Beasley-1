(function () {

	var self = this;

	tinymce.PluginManager.add('gm_timed_content_mce_button', function (editor, url) {
		self.editor = editor;
		self.editor.addButton('gm_timed_content_mce_button', {
			//text   : 'Time Restrict',
			icon   : 'gm-timed-content-icon',
			onclick: function () {
				var time_restricted_editor_popup = {

					title: 'Timed Content',

					body: [

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

						if (e.data.show && '' !== e.data.show) {
							show_attr = 'show="' + e.data.show + '"';
						}
						else {
							show_attr = '';
						}

						if (e.data.hide && '' !== e.data.hide) {
							hide_attr = 'hide="' + e.data.hide + '"';
						}
						else {
							hide_attr = '';
						}

						editor.insertContent('[time-restricted ' + show_attr + ' ' + hide_attr + ']' + content + '[/time-restricted]');

					}
				}

				self.editor.windowManager.open(time_restricted_editor_popup);

			}
		});
	});

	wp.mce.views.register('time-restricted', {

		View: {
			//template: media.template( 'editor-gallery' ),
			template: _.template(GreaterMediaTimedContent.templates.tinymce),

			// The fallback post ID to use as a parent for galleries that don't
			// specify the `ids` or `include` parameters.
			//
			// Uses the hidden input on the edit posts page by default.
			postID  : jQuery('#post_ID').val(),

			initialize: function (options) {
				this.shortcode = options.shortcode;
			},

			getHtml: function () {
				var attrs = this.shortcode.attrs.named,
					attachments = false,
					options;

				// Format the "show" date for display using the date.format library
				if (attrs.show) {
					attrs.show = new Date(attrs.show).format(GreaterMediaTimedContent.formats.mce_view_date);
				}

				// Format the "hide" date for display using the date.format library
				if (attrs.hide) {
					attrs.hide = new Date(attrs.hide).format(GreaterMediaTimedContent.formats.mce_view_date);
				}

				options = {
					content: this.shortcode.content,
					show   : undefined,
					hide   : undefined
				};
				_.extend(options, attrs);

				return this.template(options);
			}
		},

		edit: function (node) {

			var time_restricted_editor_popup = {

				title: 'Timed Content',

				body: [

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
					{
						type: 'textbox',
						multiline: true,
						minHeight: 200,
						name: 'content',
						label: 'Content',
						value: node.querySelector('.content').innerHTML
					}

				],

				buttons: [
					{
						text: 'Ok',
						onclick: function(){
							//some code here that modifies the selected node in TinyMCE
							tinymce.activeEditor.windowManager.close();
						}
					},
					{
						text: 'Cancel',
						onclick: 'close'
					}
				],

				width: 600,
				height: 340
			};

			self.editor.windowManager.open(time_restricted_editor_popup);
			
		}

	});

})();
