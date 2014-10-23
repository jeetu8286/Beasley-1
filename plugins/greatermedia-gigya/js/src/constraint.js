var Constraint = function(properties) {
	this.id = Constraint.nextID();

	if (properties) {
		for (var property in properties) {
			if (properties.hasOwnProperty(property)) {
				this[property] = properties[property];
			}
		}
	}
};

Constraint.idCounter = 0;
Constraint.nextID = function() {
	return Constraint.idCounter++;
};

Constraint.prototype = {

	fromJSON: function(json) {
		this.type        = json.type;
		this.title       = json.title;

		this.fieldPath   = json.fieldPath;
		this.value       = unescapeValue(json.value);
		this.valueType   = json.valueType;
		this.operator    = json.operator;
		this.conjunction = json.conjunction;
	},

	toJSON: function() {
		var json         = {};
		json.type        = this.type;
		json.title       = this.title;
		json.value       = escapeValue(this.value);
		json.valueType   = this.valueType;
		json.conjunction = this.conjunction;
		json.fieldPath   = this.fieldPath;
		json.operator    = this.operator;

		return json;
	},

	toGQL: function() {
		var gql = this.fieldPath + ' ' + this.operator + ' ';
		if (this.valueType === 'string') {
			gql += "'" + escapeValue(this.value) + "'";
		} else {
			gql += this.value;
		}

		return gql;
	},

	clone: function() {
		var constraint = new Constraint();
		constraint.fromJSON(this.toJSON());

		return constraint;
	},

	getOperators: function() {
		var operators = [];

		if (this.valueType === 'string') {
			operators.push.apply(operators, ['contains', 'not contains']);
		} else if (this.valueType === 'number') {
			operators.push.apply(operators, ['>', '>=', '<', '<=']);
		}

		operators.push.apply(operators, ['=', '!=']);

		return operators;
	},


};
