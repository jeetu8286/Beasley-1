(function($) {

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
			this.value       = json.value;
			this.valueType   = json.valueType;
			this.operator    = json.operator;
			this.conjunction = json.conjunction;
		},

		toJSON: function() {
			var json         = {};
			json.type        = this.type;
			json.title       = this.title;
			json.value       = this.value;
			json.valueType   = this.valueType;
			json.conjunction = this.conjunction;
			json.fieldPath   = this.fieldPath;
			json.operator    = this.operator;

			return json;
		},

		toGQL: function() {
			var gql = this.fieldPath + ' ' + this.operator + ' ';
			if (this.valueType === 'string') {
				gql += "'" + this.value + "'";
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
		}

	};

	var ConstraintStore = function() {
		this.mediator   = $({});
		this.available  = this.getAvailableConstraints();
		this.current    = this.getCurrentConstraints();
	};

	ConstraintStore.prototype = {

		getAvailableConstraints: function() {
			var constraints = [];

			constraints.push(
				new Constraint({
					type: 'influence_rank',
					title: 'Influence Rank',
					fieldPath: 'iRank',
					value: 1,
					valueType: 'number',
					operator: '>',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'facebook_likes',
					title: 'Facebook Likes',
					fieldPath: 'profile.likes.name',
					value: '',
					valueType: 'string',
					operator: '=',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'communication_preferences',
					title: 'Communication Preferences',
					fieldPath: 'data.groups.subscribed',
					value: true,
					valueType: 'string',
					operator: '=',
					conjunction: 'and'
				})
			);

			return constraints;
		},

		getCurrentConstraints: function() {
			var items = member_query_data.constraints || [];
			var constraints = [];
			var n = items.length;
			var i, item, constraint;

			for (i = 0; i < n; i++) {
				item = items[i];
				constraint = new Constraint();
				constraint.fromJSON(item);

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
				constraint = new Constraint();
				constraint.fromJSON(item);

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
			var gql = 'select * from accounts where ';
			var n   = this.current.length;
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

	var MemberQueryUpdater = function(store) {
		this.store = store;
		this.store.on('change', $.proxy(this.didStoreChange, this));
		this.store.on('updateField', $.proxy(this.didUpdateField, this));
	};

	MemberQueryUpdater.prototype = {

		didStoreChange: function(event, store) {
			this.update();
		},

		didUpdateField: function(event, store) {
			this.update();
		},

		update: function() {
			var gql         = {};
			var constraints = JSON.stringify(this.store.toJSON());
			var query       = this.store.toGQL();

			$('#constraints').attr('value', constraints);
			$('#query').attr('value', query);
		}

	};

	var MenuView = function(store) {
		this.store     = store;
		this.container = $('.constraints-menu');
		this.container.on('click', $.proxy(this.didItemClick, this));

		this.render();
	};

	MenuView.prototype = {

		didItemClick: function(event) {
			var id = $(event.target).attr('data-id');
			if (id) {
				this.addConstraint(parseInt(id, 10));
			}
			event.preventDefault();
		},

		addConstraint: function(id) {
			this.store.add(id);
		},

		render: function() {
			var constraints = this.store.available;
			var n = constraints.length;
			var i, constraint, link;

			for (i = 0; i < n; i++) {
				constraint = constraints[i];
				item = $('<li></li>');
				link = $('<a href="#"/>').attr('data-id', i).html(constraint.title);

				link.appendTo(item);
				this.container.append(item);
			}
		}

	};

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
				.append($('<p></p>').text(constraint.title))
				.append(this.selectForOperator(constraint))
				.append(this.inputForConstraint(constraint))
				.append(this.selectForConjunction(constraint));

			return li;
		},

		emptyListItem: function() {
			var li = $('<li></li>').text('Click to add filters');
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
				'href': '#'
			});
			item.append(link);
			list.append(item);

			item = $('<li></li>');
			link = $('<a></a>', {
				'data-id': constraint.id,
				'alt': 'f105',
				'class': 'dashicons dashicons-trash remove-constraint',
				'href': '#'
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

	var QueryBuilderApp = function() {
		$(document).ready($.proxy(this.initialize, this));
	};

	QueryBuilderApp.prototype = {

		initialize: function() {
			this.store        = new ConstraintStore();
			this.menuView     = new MenuView(this.store);
			this.listView     = new ConstraintListView(this.store);
			this.queryUpdater = new MemberQueryUpdater(this.store);
		},

	};

	var app = new QueryBuilderApp();

}(jQuery));
