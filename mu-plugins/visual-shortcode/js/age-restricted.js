(function($) {

	var AgeRestrictedMenu = function() {

	};

	AgeRestrictedMenu.prototype = $.extend(new VisualShortcodeRedux.Menu(), {

	});

	var AgeRestrictedDialog = function() {

	};

	AgeRestrictedDialog.prototype = $.extend(new VisualShortcodeRedux.Dialog(), {

		getTitle: function() {
			return 'Age Restricted Content';
		},

		getBody: function() {
			return [
				{
					type: 'listbox',
					name: 'status',
					label: 'Restricted To',
					values: this.getStatusValues(),
					value: this.getStatus(),
				}
			];
		},

		getStatusValues: function() {
			return [
				{ text: '18+', value: '18plus' },
				{ text: '21+', value: '21plus' },
			];
		},

		getStatus: function() {
			var data = this.getData();

			if (data.status) {
				return data.status;
			} else {
				return '18+';
			}
		}

	});

	var AgeRestrictedPlugin = function() {
	};

	AgeRestrictedPlugin.prototype = $.extend(new VisualShortcodeRedux.Plugin(), {

		getName: function() {
			return 'ageRestricted';
		},

		getShortcodeTag: function() {
			return 'age-restricted';
		},

		getDisplayName: function() {
			return 'Age Restriction';
		},

		getMetaLabel: function(data) {
			return 'Restricted to: ' + this.getMetaStatus(data);
		},

		getMetaStatus: function(data) {
			var status = data.status;

			if (status === '18plus' || status === '18+') {
				return '18+';
			} else if (status === '21plus' || status === '21+') {
				return '21+';
			} else {
				return 'N/A';
			}
		},

		getMenu: function() {
			var menu = new AgeRestrictedMenu();
			menu.plugin = this;

			return menu;
		},

		getDialog: function(isNew) {
			var dialog = new AgeRestrictedDialog();
			dialog.isNew = isNew;
			dialog.data = {};
			dialog.plugin = this;

			return dialog;
		}

	});

	$(document).ready(function() {
		var plugin = new AgeRestrictedPlugin();
		plugin.register();
	});

}(jQuery));
