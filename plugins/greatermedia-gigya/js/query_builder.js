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

			/*
			constraints.push(
				new Constraint({
					type: 'profile_likes',
					title: 'Profile Likes',
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
			*/

			/* constraints from Jira */
			constraints.push(
				new Constraint({
					type: 'membership_start_date',
					title: 'Membership Start Date',
					fieldPath: 'registeredTimestamp',
					value: 1347872653,
					valueType: 'number',
					operator: '>',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'profile_location',
					title: 'Profile Location City',
					fieldPath: 'profile.city',
					value: '',
					valueType: 'string',
					operator: 'contains',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'profile_location',
					title: 'Profile Location State',
					fieldPath: 'profile.state',
					value: '',
					valueType: 'string',
					operator: 'contains',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'listening_loyalty',
					title: 'Listening Loyalty',
					fieldPath: 'data.listeningLoyalty',
					value: 'Only this station',
					valueType: 'string',
					operator: 'contains',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'listening_frequency',
					title: 'Listening Frequency',
					fieldPath: 'data.listeningFrequency',
					value: 'Once per day',
					valueType: 'string',
					operator: 'contains',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'participation_in_survey',
					title: 'Participation in Survey',
					fieldPath: 'data.surveys.name',
					value: '',
					valueType: 'string',
					operator: 'contains',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'survey_question',
					title: 'Survey Question',
					fieldPath: 'data.surveys.entries.questions.question',
					value: '',
					valueType: 'string',
					operator: 'contains',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'survey_question_response',
					title: 'Survey Question Response',
					fieldPath: 'data.surveys.entries.questions.answers',
					value: '',
					valueType: 'string',
					operator: 'contains',
					conjunction: 'and'
				})
			);

			constraints.push(
				new Constraint({
					type: 'contest_name',
					title: 'Participation in Contest',
					fieldPath: 'data.contests.name',
					value: 'Favorite Band',
					valueType: 'string',
					operator: 'contains',
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

			console.log(query);
			return query;
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
			var i, constraint, link, span;

			for (i = 0; i < n; i++) {
				constraint = constraints[i];
				item = $('<li></li>');
				link = $('<a href="#"/>')
					.attr('data-id', i)
					.attr('title', 'Click to add')
					.text(constraint.title);

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

	var PreviewView = function(queryUpdater) {
		this.queryUpdater  = queryUpdater;
		this.container     = $('.member-query-results');
		this.previewButton = $('.preview-member-query-button');
		this.previewButton.on('click', $.proxy(this.didPreviewButtonClick, this));
	};

	PreviewView.prototype = {

		didPreviewButtonClick: function(event) {
			var query = this.queryUpdater.update();
			this.preview(query);
			event.preventDefault();
		},

		preview: function(query) {
			// TODO: clean this up
			var nonce = member_query_meta.preview_nonce;
			var data  = {
				'action': 'preview_member_query',
				'data': {
					'query': query
				}
			};

			var url = member_query_meta.ajaxurl + '?' + $.param({
				'preview_member_query_nonce': nonce,
			});

			this.setStatus('Searching, Please wait ...');

			var promise = $.post(url, data);

			promise
				.then($.proxy(this.didPreviewSuccess, this))
				.fail($.proxy(this.didPreviewError, this));
		},

		didPreviewSuccess: function(response) {
			var accounts = response.data.accounts;
			var total    = response.data.total;
			var message = total + ' records found';

			if (total > 0) {
				message += ', showing the first 5';
			} else {
				message += '.';
			}

			this.setStatus(message);
			this.render(accounts);
		},

		didPreviewError: function(response) {
			this.setStatus('Failed to query records: ' + response.responseJSON.data);
		},

		render: function(accounts) {
			this.container.empty();

			var n = accounts.length;
			var i, account;

			for (i = 0; i < n; i++) {
				account = accounts[i];
				this.container.append(this.rowForAccount(account, i));
			}
		},

		rowForAccount: function(account, index) {
			var tr = $('<tr></tr>', { 'class': index % 2 ? 'alternate': '' });
			var td = $('<td></td>');
			var link;

			link = $('<a href="#"></a>').text(account);
			link.attr({ 'class': 'open-member-page-text' });
			td.append(link);

			link = $('<a href="#"></a>');
			link.attr({
				'alt': 'f105',
				'class': 'dashicons dashicons-external open-member-page',
			});
			td.append(link);

			tr.append(td);

			return tr;
		},

		setStatus: function(message) {
			var div = $('.count-status');
			div.text(message);
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
			this.previewView  = new PreviewView(this.queryUpdater);

			this.previewView.preview(member_query_data.query);
		},

	};

	var app = new QueryBuilderApp();

}(jQuery));
