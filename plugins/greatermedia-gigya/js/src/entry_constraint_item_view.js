var EntryConstraintItemView = function(container, constraint) {
	ConstraintItemView.call(this, container, constraint);
	this.entryTypes = [];
	this.entryFields = [];
};

EntryConstraintItemView.prototype = Object.create(ConstraintItemView.prototype);
EntryConstraintItemView.prototype.constructor = EntryConstraintItemView;

var p = EntryConstraintItemView.prototype;
p.render = function() {
	var templateData = {
		view: this,
		constraint: this.constraint
	};

	var html = renderTemplate('entry_constraint_item', templateData);
	this.container.append(html);
	this.loadEntryTypes();
};

p.loadEntryTypes = function() {
	var params = { entryType: 'contest' };
	ajaxApi.request('list_entry_types', params)
		.then($.proxy(this.didLoadEntryTypes, this))
		.fail($.proxy(this.didLoadEntryTypesError, this));
};

p.didLoadEntryTypes = function(response) {
	this.entryTypes = response.data;

	var select = $('#entry-select-type-' + this.constraint.id);
	var html = this.renderSelect(this.entryTypes, this.constraint.entryTypeID);

	select.html(html);
	select.on('change', $.proxy(this.didEntryTypeChange, this));
	select.trigger('change');
};

p.didLoadEntryTypesError = function(response) {
	console.log('didLoadEntryTypesError', response);
};

p.didEntryTypeChange = function(event) {
	var choice = $(event.target).val();
	this.constraint.entryTypeID = choice;
	this.loadEntryFields(choice);
};

p.loadEntryFields = function(entryTypeID) {
	var params = { entryTypeID: entryTypeID };
	ajaxApi.request('list_entry_fields', params)
		.then($.proxy(this.didLoadEntryFields, this))
		.fail($.proxy(this.didLoadEntryFieldsError, this));
};

p.didLoadEntryFields = function(response) {
	this.entryFields = response.data;

	var select = $('#entry-select-field-' + this.constraint.id);
	var html   = this.renderSelect(this.entryFields, this.constraint.entryFieldID);

	select.html(html);
	select.on('change', $.proxy(this.didEntryFieldChange, this));
	select.trigger('change');
};

p.didLoadEntryFieldsError = function(response) {
	console.log('didLoadEntryFieldsError', response);
};

p.didEntryFieldChange   = function(event) {
	var choice          = $(event.target).val();
	var field           = this.getFieldByID(choice);
	var type            = field.type;
	var answerType = field.type === 'select' || field.type === 'checkbox' ? 'choice' : '';
	var templateData = {
		view: this,
		choices: field.choices,
		currentChoice: this.getChoiceIndex(field.choices, this.constraint.value),
		answerType: answerType,
		constraint: this.constraint
	};

	this.constraint.entryFieldID = field.value;
	this.constraint.entryFieldType = field.type;

	var html = renderTemplate('entry_answer', templateData);
	var div = $('#entry-answer-' + this.constraint.id);
	div.html(html);

	this.constraint.value = '';

	if (answerType === 'choice') {
		div.on('change', $.proxy(this.didEntryAnswerChange, this));
		var select = $('#constraint-value-select-' + this.constraint.id);
		select.trigger('change');
	} else {
		var input = $('#constraint-value-input-' + this.constraint.id);
		input.trigger('change');
	}
};

p.didEntryAnswerChange = function(event) {
	var target = $(event.target);
	var value = target.val();

	this.constraint.value = value;
	store.notify('inplaceChange');
	return false;
};

p.getFieldByID = function(id) {
	return _.find(this.entryFields, function(field) {
		return field.value == id;
	});
};

p.getChoiceIndex = function(choices, choiceID) {
	var n = choices.length;
	for (var i = 0; i < n; i++) {
		choice = choices[i];
		if (choice.value == choiceID) {
			return i;
		}
	}

	return -1;
};

p.renderSelect = function(choices, choiceID) {
	var templateData = {
		view: this,
		id: this.constraint.id,
		choices: choices,
		currentChoice: this.getChoiceIndex(choices, choiceID)
	};

	var html = renderTemplate('entry_select', templateData);
	return html;
};
