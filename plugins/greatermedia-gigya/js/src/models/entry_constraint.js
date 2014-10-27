var EntryConstraint = Constraint.extend({

	defaults         : {
		type         : 'record:contest',
		operator     : 'equals',
		conjunction  : 'and',
		valueType    : 'string', // TODO: this is actually polymorphic
		value        : '',
		entryTypeID  : -1,
		entryFieldID : -1
	},

	initialize: function(attr, opts) {
		Constraint.prototype.initialize.call(this, attr, opts);
		this.listenTo(this, 'change:entryTypeID', this.didChangeEntryTypeID);
	},

	getEntryType: function() {
		var types = this.get('type').split(':');
		var kind  = types[1];

		return kind;
	},

	getEntryTypes: function() {
		if (!this.entryTypes) {
			this.entryTypes = new EntryTypeCollection();
			this.loadEntryTypes();
		}

		return this.entryTypes;
	},

	loadEntryTypes: function() {
		var params = { entryType: this.getEntryType() };
		ajaxApi.request('list_entry_types', params)
			.then($.proxy(this.didLoadEntryTypes, this))
			.fail($.proxy(this.didLoadEntryTypesError, this));

	},

	didLoadEntryTypes: function(response) {
		var entryTypes = this.entryTypes;
		entryTypes.reset(response.data);

		if (this.get('entryTypeID') === -1) {
			// auto-select first entry
			this.set('entryTypeID', entryTypes.at(0).get('value'));
		} else {
			this.didChangeEntryTypeID();
		}
	},

	didLoadEntryTypesError: function(response) {
		console.log(response);
	},

	didChangeEntryTypeID: function() {
		this.loadEntryFields();
	},

	loadEntryFields: function() {
		var params = { entryTypeID: this.get('entryTypeID') };
		ajaxApi.request('list_entry_fields', params)
			.then($.proxy(this.didLoadEntryFields, this))
			.fail($.proxy(this.didLoadEntryFieldsError, this));
	},

	didLoadEntryFields: function(response) {
		var entryFields = this.getEntryFields();
		entryFields.reset(response.data);

		if (this.get('entryFieldID') === -1) {
			// auto-select first field
			this.set('entryFieldID', entryFields.at(0).get('value'));
		}
	},

	didLoadEntryFieldsError: function(response) {
		console.log(response);
	},

	getEntryFields: function() {
		if (!this.entryFields) {
			this.entryFields = new EntryFieldCollection();
		}

		return this.entryFields;
	},

	getEntryFieldChoices: function() {
		var fieldCollection = this.getEntryFields();
		var field           = fieldCollection.findWhere({
			value: this.get('entryFieldID')
		});

		if (field) {
			var choices = field.get('choices');
			if (choices && choices.length > 0) {
				return choices;
			} else {
				return [];
			}
		} else {
			return [];
		}
	},

	hasChoices: function() {
		var choices = this.getEntryFieldChoices();
		return choices.length > 0;
	}

});
