(function () {

	tinymce.PluginManager.add('gm_timed_content_mce_button', function (editor, url) {
		editor.addButton('gm_timed_content_mce_button', {
			//text   : 'Time Restrict',
			icon   : 'gm-timed-content-icon',
			onclick: function() {
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
				});

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

			var gallery = wp.media.gallery,
				self = this,
				frame, data;

			data = window.decodeURIComponent($(node).attr('data-wpview-text'));
			frame = gallery.edit(data);

			frame.state('gallery-edit').on('update', function (selection) {
				var shortcode = gallery.shortcode(selection).string();
				$(node).attr('data-wpview-text', window.encodeURIComponent(shortcode));
				wp.mce.views.refreshView(self, shortcode);
			});

			frame.on('close', function () {
				frame.detach();
			});

		}

	});

})();
