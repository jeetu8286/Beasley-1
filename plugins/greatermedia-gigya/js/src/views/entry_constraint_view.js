var EntryConstraintView = ConstraintView.extend({

	template: getTemplate('entry_constraint'),
	entrySelectTemplate: getTemplate('entry_select'),
	entryAnswerTemplate: getTemplate('entry_answer'),

	initialize: function(model, opts) {
		ConstraintView.prototype.initialize.call(this, model, opts);
		this.listenTo(this.model.getEntryTypes(), 'reset', this.renderEntryTypes);
		this.listenTo(this.model.getEntryFields(), 'reset', this.renderEntryField);
	},

	renderEntryTypes: function() {
		var $entrySelect = $('.entry-select-type', this.el);
		var choices = this.model.getEntryTypes().toJSON();
		var data = {
			choices: choices,
			currentChoice: this.model.get('entryTypeID')
		};

		var html = this.entrySelectTemplate(data);
		$entrySelect.html(html);
	},

	renderEntryField: function() {
		var $entrySelectChoice = $('.entry-select-field', this.el);
		var choices = this.model.getEntryFields().toJSON();
		var data = {
			choices: choices,
			currentChoice: this.model.get('entryFieldID')
		};

		var html = this.entrySelectTemplate(data);
		$entrySelectChoice.html(html);

		this.renderEntryAnswer();
	},

	renderEntryValueSelect: function() {
		var $entrySelectChoice = $('.constraint-value', this.el);
		var choices = this.model.getEntryFieldChoices();
		var data = {
			choices: choices,
			currentChoice: this.model.get('value')
		};

		var html = this.entrySelectTemplate(data);
		$entrySelectChoice.html(html);
	},

	renderEntryAnswer: function() {
		var $entryAnswer   = $('.entry-answer-group', this.el);
		var data           = this.model.toViewJSON();
		data.view          = this;
		data.choices       = this.model.getEntryFieldChoices();
		data.currentChoice = this.model.get('value');

		var html = this.entryAnswerTemplate(data);
		$entryAnswer.html(html);
	},

	updateConstraint: function(constraint) {
		var operator     = $('.constraint-operator', this.el).val();
		var conjunction  = $('.constraint-conjunction', this.el).val();
		var value        = $('.constraint-value', this.el).val();
		var entryTypeID  = $('.entry-select-type', this.el).val();
		var entryFieldID = $('.entry-select-field', this.el).val();
		value            = this.parseValue(value, constraint.get('valueType'));

		var changes     = {
			operator: operator,
			value: value,
			conjunction: conjunction,
			entryTypeID: this.parseValue(entryTypeID, 'integer'),
			entryFieldID: this.parseValue(entryFieldID, 'integer')
		};

		//console.log('updateConstraint', changes);
		constraint.set(changes);
		this.renderEntryAnswer();
	},

	hasChoices: function() {
		return this.model.hasChoices();
	}

});
