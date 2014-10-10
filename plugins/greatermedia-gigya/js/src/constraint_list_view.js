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
		var li = $('<li></li>')
			.append($('<p></p>', { 'class': 'constraint-empty' }).text('Click to add filters'));

		return li;
	},

	toolbarForConstraint: function(constraint) {
		var list = $('<ul></ul>').attr('class', 'constraint-toolbar');
		var item, link;

		item = $('<li></li>');
		link = $('<a></a>', {
			'data-id': constraint.id,
			'alt': 'f105',
			'class': 'dashicons dashicons-admin-page copy-constraint',
			'href': '#',
			'title': 'Duplicate'
		});
		item.append(link);
		list.append(item);

		item = $('<li></li>');
		link = $('<a></a>', {
			'data-id': constraint.id,
			'alt': 'f105',
			'class': 'dashicons dashicons-trash remove-constraint',
			'href': '#',
			'title': 'Remove'
		});
		item.append(link);
		list.append(item);

		return list;
	},

	inputForConstraint: function(constraint) {
		var input = $('<input />', {
			'data-id': constraint.id,
			'type': 'text',
			'value': constraint.value,
			'class': 'constraint-value'
		});

		return input;
	},

	selectForConjunction: function(constraint) {
		var currentConjunction = constraint.conjunction;
		var select = $('<select />', {
			'data-id': constraint.id,
			'class': 'constraint-conjunction'
		});
		var conjunctions = ['and', 'or'];
		var n = conjunctions.length;
		var i, conjunction, label, option;

		for (i = 0; i < n; i++) {
			conjunction = conjunctions[i];
			label       = conjunction;
			option      = $('<option></option>', { value: conjunction }).text(label);

			if (currentConjunction === conjunction) {
				option.prop('selected', true);
			}

			select.append(option);
		}

		return select;
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
		var valueType = constraint.valueType;
		var currentOperator = constraint.operator;
		var select    = $('<select />', {
			'data-id': constraint.id,
			'class': 'constraint-operator'
		});
		var operators = this.operatorsFor(valueType);
		var n         = operators.length;
		var i, operator, label, option;

		for (i = 0; i < n; i++) {
			operator = operators[i];
			label    = this.operatorLabelFor(operator);
			option   = $('<option></option>', { value: operator }).text(label);

			if (currentOperator === operator) {
				option.prop('selected', true);
			}

			select.append(option);
		}

		return select;
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

