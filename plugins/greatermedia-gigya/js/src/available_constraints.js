var factory = ConstraintFactory.instance;
var AVAILABLE_CONSTRAINTS = [

	factory.build({
		type: 'membership_start_date',
		title: 'Membership Start Date',
		fieldPath: 'registeredTimestamp',
		value: 1347872653,
		valueType: 'number',
		operator: '>',
		conjunction: 'and'
	}),

	factory.build({
		type: 'profile_location',
		title: 'Profile Location City',
		fieldPath: 'profile.city',
		value: '',
		valueType: 'string',
		operator: 'contains',
		conjunction: 'and'
	}),

	factory.build({
		type: 'profile_location',
		title: 'Profile Location State',
		fieldPath: 'profile.state',
		value: '',
		valueType: 'string',
		operator: 'contains',
		conjunction: 'and'
	}),

	factory.build({
		type: 'listening_loyalty',
		title: 'Listening Loyalty',
		fieldPath: 'data.listeningLoyalty',
		value: 'Only this station',
		valueType: 'string',
		operator: 'contains',
		conjunction: 'and'
	}),

	factory.build({
		type: 'listening_frequency',
		title: 'Listening Frequency',
		fieldPath: 'data.listeningFrequency',
		value: 'Once per day',
		valueType: 'string',
		operator: 'contains',
		conjunction: 'and'
	}),

	factory.build({
		type           : 'entry_constraint',
		title          : 'Participation in Contest',
		fieldPath      : 'data.form_entries',
		value          : '',
		valueType      : 'string',
		operator       : 'contains',
		conjunction    : 'and',

		entryType      : 'contest',
		entryTypeID    : -1,
		entryFieldID   : -1,
		entryFieldType : 'text'
	})

];

