var ConstraintFactory = function() {

};

ConstraintFactory.prototype.build = function(json) {
	var ConstraintType = this.constraintFor(json.type);
	var instance = new ConstraintType();

	instance.fromJSON(json);

	return instance;
};

ConstraintFactory.prototype.constraintFor = function(type) {
	switch (type) {
		case 'entry_constraint':
			return EntryConstraint;

		default:
			return Constraint;
	}
};

ConstraintFactory.instance = new ConstraintFactory();
