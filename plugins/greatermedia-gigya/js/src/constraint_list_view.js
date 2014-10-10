var ConstraintListView = function(store) {
	this.store         = store;
	this.container     = $('.current-constraints');

	this.store.on('change', $.proxy(this.didStoreChange, this));
	this.container.on('click', $.proxy(this.didItemClick, this));
	this.container.on('change', $.proxy(this.didItemChange, this));

	this.render();
};

ConstraintListView.prototype = {

	didItemClick: function(event) {
		var target = $(event.target);
		var id = target.attr('data-id');

		if (id) {
			id = parseInt(id, 10);
		}

		if (target.hasClass('remove-constraint')) {
			this.removeConstraint(id);
		} else if (target.hasClass('copy-constraint')) {
			this.copyConstraint(id);
		}

		event.preventDefault();
	},

	didItemChange: function(event) {
		var target = $(event.target);
		var id = target.attr('data-id');
		var field;

		if (id) {
			id = parseInt(id, 10);
		}

		if (target.hasClass('constraint-operator')) {
			field = 'operator';
		} else if (target.hasClass('constraint-conjunction')) {
			field = 'conjunction';
		} else if (target.hasClass('constraint-value')) {
			field = 'value';
		}

		if (field) {
			this.updateConstraint(id, field, target.val());
		}
	},

	didStoreChange: function(event) {
		this.render();
	},

	copyConstraint: function(id) {
		this.store.copy(id);
	},

	removeConstraint: function(id) {
		this.store.remove(id);
	},

	updateConstraint: function(id, field, value) {
		this.store.update(id, field, value);
	},

	render: function() {
		this.container.empty();

		var constraints = this.store.current;
		var n           = constraints.length;
		var i, constraint, li;

		for (i = 0; i < n; i++) {
			constraint = constraints[i];
			li         = this.listItemForConstraint(constraint);

			this.container.append(li);
		}

		if (n === 0) {
			this.container.append(this.emptyListItem());
		}
	},

	listItemForConstraint: function(constraint) {
		var li = $('<li></li>')
			.append(this.toolbarForConstraint(constraint))
			.append($('<p></p>', { 'class': 'constraint-title' }).text(constraint.title))
			.append(this.selectForOperator(constraint))
			.append(this.inputForConstraint(constraint))
			.append(this.selectForConjunction(constraint));

		return li;
	},

	emptyListItem: function() {
		return renderTemplate('empty_constraints');
	},

	toolbarForConstraint: function(constraint) {
		var templateData = {
			view: this,
			constraint: constraint
		};

		return renderTemplate('constraint_toolbar', templateData);
	},

	inputForConstraint: function(constraint) {
		var templateData = {
			view: this,
			constraint: constraint
		};

		return renderTemplate('constraint_input', templateData);
	},

	selectForConjunction: function(constraint) {
		var templateData = {
			view: this,
			constraint: constraint,
			conjunctions: ['and', 'or']
		};

		return renderTemplate('conjunction_select', templateData);
	},

	operators : {
		'='   : 'equals',
		'!='  : 'not equals',
		'>'   : 'greater than',
		'>='  : 'greater than or equal to',
		'<'   : 'less than',
		'<='  : 'less than or equal to',
	},

	selectForOperator: function(constraint) {
		var templateData = {
			view: this,
			constraint: constraint,
			operators: this.operatorsFor(constraint.valueType)
		};

		return renderTemplate('operator_select', templateData);
	},

	operatorLabelFor: function(operator) {
		if (this.operators.hasOwnProperty(operator)) {
			return this.operators[operator];
		} else {
			return operator;
		}
	},

	operatorsFor: function(valueType) {
		var operators = ['=', '!='];

		if (valueType === 'number') {
			operators.push.apply(operators, ['>', '>=', '<', '<=']);
		} else if (valueType === 'string') {
			operators.push.apply(operators, ['contains', 'not contains']);
		}

		return operators;
	}

};

