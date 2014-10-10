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
