var ConstraintStore = function() {
	this.mediator   = $({});
	this.available  = this.getAvailableConstraints();
	this.current    = this.getCurrentConstraints();
};

ConstraintStore.prototype = {

	getAvailableConstraints: function() {
		return AVAILABLE_CONSTRAINTS;
	},

	getCurrentConstraints: function() {
		var items = member_query_data.constraints || [];
		var constraints = [];
		var n = items.length;
		var i, item, constraint;

		for (i = 0; i < n; i++) {
			item       = items[i];
			constraint = ConstraintFactory.instance.build(item);
			constraints.push(constraint);
		}

		return constraints;
	},

	fromJSON: function(json) {
		this.current = [];

		var n = json.length;
		var i, item, constraint;

		for (i = 0; i < n; i++) {
			item       = json[i];
			constraint = ConstraintFactory.instance.build(item);
			this.current.push(constraint);
		}

		this.notify();
	},

	toJSON: function() {
		var n = this.current.length;
		var i, constraint;
		var json = [];

		for (i = 0; i < n; i++) {
			constraint = this.current[i];
			json.push(constraint.toJSON());
		}

		return json;
	},

	toGQL: function() {
		/* TODO: Optimize to only get the fields we need */
		var n   = this.current.length;
		if (n === 0) {
			return '';
		}

		var gql = 'select * from accounts where ';
		var i, constraint;
		var prevConstraint;

		for (i = 0; i < n; i++) {
			constraint = this.current[i];
			if (prevConstraint) {
				gql += ' ' + prevConstraint.conjunction;
			}

			gql += ' ' + constraint.toGQL();
			prevConstraint = constraint;
		}

		return gql;
	},

	add: function(id) {
		var index      = this.indexOf(id, this.available);
		var constraint = this.available[index];
		constraint = constraint.clone();

		this.current.push(constraint);
		this.notify();
	},

	copy: function(id) {
		var index         = this.indexOf(id);
		var constraint    = this.current[index];
		var newConstraint = constraint.clone();

		this.current.splice(index, 0, newConstraint);
		this.notify();
	},

	remove: function(id) {
		var index         = this.indexOf(id);
		var constraint    = this.current[index];
		var newConstraint = constraint.clone();

		this.current.splice(index, 1);
		this.notify();
	},

	update: function(id, field, value) {
		var index = this.indexOf(id);
		var constraint = this.current[index];
		constraint[field] = value;

		this.notify('updateField');
	},

	indexOf: function(id, list) {
		if (!list) {
			list = this.current;
		}

		var n = list.length;
		var i, constraint;

		for (i = 0; i < n; i++) {
			constraint = list[i];
			if (constraint.id === id) {
				return i;
			}
		}

		return -1;
	},

	on: function(event, listener) {
		this.mediator.on(event, listener);
	},

	notify: function(event) {
		if (!event) {
			event = 'change';
		}

		this.mediator.trigger(event, this);
	}

};
