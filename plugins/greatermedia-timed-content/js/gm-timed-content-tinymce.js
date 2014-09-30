(function () {

	var self = this;

	/**
	 * Parse an attribute out of a shortcode
	 * @param {string} shortcode the complete shortcode
	 * @param {string} attribute_name to extract
	 * @returns {string}
	 */
	function parse_shortcode_attribute(shortcode, attribute_name) {

		if (undefined === shortcode || undefined === attribute_name) {
			return '';
		}

		var matches = decodeURIComponent(shortcode).match(attribute_name + '="([^\"]*)"');

		if (undefined !== matches[1]) {
			return matches[1];
		}

		// Default
		return '';

	}

	/**
	 * Build a [time-restricted] shortcode with the appropriate show/hide dates and content
	 *
	 * @param {Date} show_date
	 * @param {Date} hide_date
	 * @param {string} content
	 * @returns {string}
	 */
	function build_shortcode(show_date, hide_date, content) {

		var shortcode = '[time-restricted ';

		if ('' !== show_date) {
			shortcode += 'show="' + show_date.toISOString() + '" ';
		}

		if ('' !== hide_date) {
			shortcode += 'hide="' + hide_date.toISOString() + '" ';
		}

		shortcode += ']' + content + '[/time-restricted]';

		return shortcode;

	}

	// Register a custom button in the TinyMCE toolbar
	tinymce.PluginManager.add('gm_timed_content_mce_button', function (editor, url) {

		self.editor = editor;
		self.editor.addButton('gm_timed_content_mce_button', {
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
						}

					],

					/**
					 * When the popup is submitted, generate a shortcode and insert it into the editor
					 *
					 * @param {Event} e
					 */
					onsubmit: function (e) {

						editor.insertContent(
							build_shortcode(
								new Date(e.data.show),
								new Date(e.data.hide),
								tinymce.activeEditor.selection.getContent()
							)
						);

					}
				};

				self.editor.windowManager.open(time_restricted_editor_popup);

			}
		});

	});

	/**
	 * Function to instantiate a new TinyMCE view
	 * Invoke with "new TimeRestrictedView()"
	 * @returns TinyMCE view object
	 * @constructor
	 */
	var TimeRestrictedView = function () {
		return {

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

		}
	};

	// Register a custom TinyMCE View for displaying the shortcode in the WYSIWYG editor
	wp.mce.views.register('time-restricted', {

		View: new TimeRestrictedView(),

		edit: function (node) {

			var edit_self = this;

			var show_time = new Date(parse_shortcode_attribute(node.dataset.wpviewText, 'show')),
				hide_time = new Date(parse_shortcode_attribute(node.dataset.wpviewText, 'hide'));

			var time_restricted_editor_popup = {

				title: 'Timed Content',

				body: [

					{
						type : 'textbox',
						name : 'show',
						label: 'Show content on',
						value: show_time.format(GreaterMediaTimedContent.formats.mce_view_date)
					},
					{
						type : 'textbox',
						name : 'hide',
						label: 'Hide content on',
						value: hide_time.format(GreaterMediaTimedContent.formats.mce_view_date)
					},
					{
						type     : 'textbox',
						multiline: true,
						minHeight: 200,
						name     : 'content',
						label    : 'Content',
						value    : node.querySelector('.content').innerHTML
					}

				],

				buttons: [
					{
						text   : 'Ok',
						onclick: 'submit'
					},
					{
						text   : 'Cancel',
						onclick: 'close'
					}
				],

				onsubmit: function (e) {

					var new_shortcode_text = build_shortcode(
						new Date(e.data.show),
						new Date(e.data.hide),
						e.data.content
					);

					editor.insertContent(new_shortcode_text);
					tinymce.activeEditor.windowManager.close();

				},

				width : 600,
				height: 340
			};

			self.editor.windowManager.open(time_restricted_editor_popup);

		}

	});

})();
