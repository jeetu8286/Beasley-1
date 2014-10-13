var ConstraintItemView = function(container, constraint) {
	this.container     = container;
	this.constraint    = constraint;
};

ConstraintItemView.prototype = {

	render: function() {
		var templateData = {
			view: this,
			constraint: this.constraint
		};

		var html = renderTemplate('constraint_item', templateData);
		this.container.append(html);
	},

	conjunctions: [
		'and', 'or'
	],

	operators : {
		'='   : 'equals',
		'!='  : 'not equals',
		'>'   : 'greater than',
		'>='  : 'greater than or equal to',
		'<'   : 'less than',
		'<='  : 'less than or equal to',
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
