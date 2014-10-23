var ConstraintListView = function(store) {
	this.store         = store;
	this.container     = $('.current-constraints');

	this.store.on('change', $.proxy(this.didStoreChange, this));
	this.container.on('click', $.proxy(this.didItemClick, this));
	this.container.on('change', $.proxy(this.didItemChange, this));

	this.itemViews = [];
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
		this.itemViews = [];

		var constraints = this.store.current;
		var n           = constraints.length;
		var i, constraint, itemView;

		for (i = 0; i < n; i++) {
			constraint = constraints[i];
			itemView   = this.listItemForConstraint(constraint);
			itemView.render();

			this.itemViews.push(itemView);
		}

		if (n === 0) {
			this.container.append(this.emptyListItem());
		}
	},

	listItemForConstraint: function(constraint) {
		if (constraint instanceof EntryConstraint) {
			return new EntryConstraintItemView(this.container, constraint);
		} else {
			return new ConstraintItemView(this.container, constraint);
		}
	},

	emptyListItem: function() {
		return renderTemplate('empty_constraints');
	}

};

