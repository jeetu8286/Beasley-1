var EntryConstraint = function(properties) {
	Constraint.call(this, properties);
};

EntryConstraint.prototype = Object.create(Constraint.prototype);
EntryConstraint.prototype.constructor = EntryConstraint;


EntryConstraint.prototype.fromJSON = function(json) {
	Constraint.prototype.fromJSON.call(this, json);

	this.entryType = json.entryType;
	this.entryTypeID = json.entryTypeID;
	this.entryFieldID = json.entryFieldID;
	this.entryFieldType = json.entryFieldType;
};

EntryConstraint.prototype.toJSON = function() {
	var json = Constraint.prototype.toJSON.call(this);

	json.entryType = this.entryType;
	json.entryTypeID = this.entryTypeID;
	json.entryFieldID = this.entryFieldID;
	json.entryFieldType = this.entryFieldType;

	return json;
};

EntryConstraint.prototype.clone = function() {
	var constraint = new EntryConstraint();
	constraint.fromJSON(this.toJSON());

	return constraint;
};

EntryConstraint.prototype.toGQL = function() {
	var query = this.pairToClause('entry_type', this.entryType) + ' and ';
	query += this.pairToClause('entry_type_id', this.entryTypeID) + ' and ';
	query += this.pairToClause('entry_field_id', this.entryFieldID) + ' and ';
	query += this.pairToClause('entry_field_type', this.entryFieldType) + ' and ';
	query += this.pairToClause('entry_field_value', this.value, this.operator);

	//console.log(query);
	return query;
};

EntryConstraint.prototype.toFieldKey = function(key) {
	return this.fieldPath + '.' + key
};

EntryConstraint.prototype.pairToClause = function(key, value, operator) {
	if (!operator) {
		operator = '=';
	}
	var fieldKey = this.toFieldKey(key);
	var fieldValue;
	var valueType = typeof(value);

	// TODO: use Gravity Form entry Types
	if (valueType === 'string') {
		fieldValue = "'" + escapeValue(value) + "'";
		fieldKey += '_s';
	} else {
		fieldValue = value;
		if (valueType === 'number') {
			fieldKey += '_f';
		}
	}

	return fieldKey + ' ' + operator + ' ' + fieldValue;
};
