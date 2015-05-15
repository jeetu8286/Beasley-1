(function($) {

	var Convertor = function() {
		this.toHTMLFragmentProxy   = $.proxy(this.toHTMLFragment, this);
		this.replaceShortcodeProxy = $.proxy(this.replaceShortcode, this);
	};

	Convertor.prototype = {

		toHTML: function(content) {
			return wp.shortcode.replace(
				this.plugin.getShortcodeTag(), content, this.toHTMLFragmentProxy
			);
		},

		toShortcode: function(content) {
			var $root       = $('<div></div>');
			$root.html(content);

			var selector    = this.getSelector();
			var $shortcodes = $(selector, $root);

			$shortcodes.each(this.replaceShortcodeProxy);

			var html = $root.html();

			return html;
		},

		/* helpers */
		toHTMLFragment: function(shortcode) {
			var data        = shortcode.attrs.named;
			var dataAttrs   = this.toDataAttrs(data);
			dataAttrs.class = this.plugin.getClassName();

			var $root   = $('<div></div>', dataAttrs);
			var $header = $('<span></span>', { class: 'meta', contenteditable: false });
			var $body   = $('<div></div>', { class: 'body' });

			$body.html(shortcode.content);
			$header.html(this.plugin.getMetaLabel(data));

			$root.data('status', data.status);
			$root.append($header);
			$root.append($body);

			var html = $root.prop('outerHTML');

			return html;
		},

		toDataAttrs: function(data) {
			var attrs = {};

			for (var key in data) {
				if (data.hasOwnProperty(key)) {
					attrs['data-' + key] = data[key];
				}
			}

			return attrs;
		},

		replaceShortcode: function(index, shortcode) {
			var $shortcode  = $(shortcode);
			var $body       = $('.body', $shortcode);
			var data        = $shortcode.data();
			var wpShortcode = new wp.shortcode({
				tag     : this.plugin.getShortcodeTag(),
				attrs   : data,
				type    : this.getType(),
				content : $body.html()
			});

			var html = wpShortcode.string();

			$shortcode.replaceWith(html);
		},

		getSelector: function() {
			return '.' + this.plugin.getClassName();
		},

		getType: function() {
			return 'closed';
		}

	};

	var Menu = function() {

	};

	Menu.prototype = {

		plugin: null,

		register: function() {
			var editor  = this.plugin.getEditor();
			var command = this.plugin.getCommand();
			var buttons = this.getButtons();
			var button;

			for ( var i = 0; i < buttons.length; i++ ) {
				button = buttons[i];
				editor.addButton(command, button);
			}
		},

		getButtons: function() {
			return [
				{
					title: this.plugin.getDisplayName(),
					cmd: this.plugin.getCommand(),
					image: '',
				}
			];
		}

	};

	var Dialog = function() {

	};

	Dialog.prototype = {

		plugin : null,
		win    : null,
		data: {},
		activeNode: null,

		open: function() {
			var editor        = this.plugin.getEditor();
			var params        = this.getParams();
			var windowManager = editor.windowManager;

			this.activeNode = this.plugin.getSelectedNode();
			this.win        = windowManager.open(params);

			this.didOpen();
		},

		close: function() {
			this.win.close();
		},

		didOpen: function() {

		},

		didSubmit: function(event) {
			var data = event.data;
			var plugin = this.plugin;
			var editor = plugin.getEditor();

			if (this.plugin.isFocussed()) {
				var focusState = plugin.getFocusState();
				var mode       = focusState.value;
				var target     = focusState.target;
				var $target = $(target);
				var $meta = $('.meta', $target);

				$target.data(data);
				$meta.html(this.plugin.getMetaLabel(data));
			} else {
				var selectedText = this.plugin.getSelectedText();
				var shortcode = new wp.shortcode({
					tag     : this.plugin.getShortcodeTag(),
					attrs   : data,
					type    : 'closed',
					content : selectedText,
				});

				var content = shortcode.string();

				editor.execCommand('mceInsertContent', false, content);
				editor.selection.collapse();
			}
		},

		didClose: function(event) {

		},

		didRemove: function(event) {
			var $activeNode = $(this.activeNode);
			var className = this.plugin.getClassName();

			if ($activeNode.hasClass(className)) {
				$activeNode.removeClass(className);
			} else {
				$parent = $activeNode.parents('.' + className);
				if ($parent.length !== 0) {
					var body = $('.body', $parent);
					$parent.replaceWith(body.html());
				}
			}

			this.close();
		},

		getData: function() {
			return this.data;
		},

		getParams: function() {
			var size   = this.getSize();
			var params = {
				title   : this.getTitle(),
				width   : size.width,
				height  : size.height,
				buttons : this.getButtons(),
				body    : this.getBody(),

				onsubmit : $.proxy(this.didSubmit, this),
				onclose  : $.proxy(this.didClose, this),
			};

			return params;
		},

		getSize: function() {
			return { width: 400, height: 200 };
		},

		getTitle: function() {
			return 'abstract:Dialog';
		},

		getButtons: function(isNew) {
			return [
				{
					text: 'Remove',
					id: 'content-restricted-remove-button',
					onclick: $.proxy(this.didRemove, this),
				},
				{
					text: 'Ok',
					onclick: 'submit'
				},
				{
					text: 'Cancel',
					onclick: 'close'
				}
			];
		},

		getBody: function(isNew) {
			return 'abstract';
		},

	};

	var Plugin = function() {

	};

	Plugin.prototype = {

		getName: function() {
			return 'shortcode-plugin';
		},

		getPluginName: function() {
			return this.getName().replace('-', '');
		},

		getShortcodeTag: function() {
			return this.getName();
		},

		getClassName: function() {
			return this.getShortcodeTag();
		},

		getDisplayName: function() {
			return 'Shortcode Plugin';
		},

		getCommand: function() {
			return this.getName();
		},

		getNamespace: function() {
			return 'tinymce.plugins.' + this.getPluginName();
		},

		getMetaLabel: function(data) {
			return 'abstract:meta-label';
		},

		getConvertor: function() {
			if (!this.convertor) {
				this.convertor = new Convertor();
				this.convertor.plugin = this;
			}

			return this.convertor;
		},

		getDialog: function() {
			var dialog = new Dialog();
			dialog.plugin = this;

			return dialog;
		},

		getMenu: function() {
			var menu = new Menu();
			menu.plugin = this;

			return menu;
		},

		getEditor: function() {
			return this.editor;
		},

		getSelection: function() {
			return this.getEditor().selection;
		},

		getSelectedNode: function() {
			return this.getSelection().getNode();
		},

		getSelectedText: function() {
			return this.getSelection().getContent();
		},

		getSelectedData: function() {
			var focusState = this.getFocusState();
			var target = focusState.target;

			if (target) {
				var $target = $(target);
				return $target.data();
			} else {
				return {};
			}
		},

		getFocusState: function() {
			var node      = this.getSelectedNode();
			var $node     = $(node);
			var className = this.getClassName();
			var state     = 'outside';
			var target    = null;

			if ($node.hasClass(className)) {
				state = 'at';
				target = node;
			} else {
				var parents = $node.parents('.' + className);
				if (parents.length !== 0) {
					state  = 'inside';
					target = parents;
				}
			}

			return { value: state, target: target };
		},

		isFocussed: function() {
			var state = this.getFocusState();
			return state.value === 'at' || state.value === 'inside';
		},

		register: function() {
			var pluginName = this.getPluginName();
			var callback   = this.callback('didRegister');

			tinymce.PluginManager.add(pluginName, callback);
		},

		/* TinyMCE Events */
		didRegister: function(editor) {
			this.editor = editor;
			this.editor.addCommand(this.getCommand(), this.callback('didCommand'));

			this.menu = this.getMenu();
			this.menu.register();

			this.editor.onNodeChange.add(this.callback('didNodeChange'));
			this.editor.on('BeforeSetContent', this.callback('didBeforeSetContent'));
			this.editor.on('PostProcess', this.callback('didPostProcess'));
		},

		didCommand: function() {
			var dialog  = this.getDialog();
			dialog.data = this.getSelectedData();
			dialog.open();

			var $removeButton = $('#content-restricted-remove-button');
			$removeButton.css('left', '10px');
		},

		didNodeChange: function(editor, command, node) {
			command.setActive(this.isFocussed());

			var focusState = this.getFocusState();
			var isInside   = false;
			var target     = focusState.target;
			var className  = this.getClassName() + '-focus';

			if (this.$prevTarget) {
				this.$prevTarget.toggleClass(className, false);
			}

			if (target) {
				var $target = $(target);

				$target.toggleClass(className, true);
				this.$prevTarget = $target;
			}
		},

		didBeforeSetContent: function(event) {
			if (content) {
				var convertor = this.getConvertor();
				event.content = convertor.toHTML(event.content);
			}
		},

		didPostProcess: function(event) {
			if (event.get) {
				var convertor = this.getConvertor();
				event.content = convertor.toShortcode(event.content);
			}
		},

		/* helpers */
		callback: function(method) {
			return $.proxy(this[method], this);
		},
	};

	window.VisualShortcodeRedux = {
		Convertor: Convertor,
		Menu: Menu,
		Dialog: Dialog,
		Plugin: Plugin,
	};

}(jQuery));
