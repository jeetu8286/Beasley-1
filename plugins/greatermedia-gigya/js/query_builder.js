this["JST"] = this["JST"] || {};

this["JST"]["src/templates/constraint_item.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<li>\n\t<ul class="constraint-toolbar">\n\t\t<li>\n\t\t\t<a\n\t\t\t\tdata-id="' +
__e( constraint.id ) +
'"\n\t\t\t\talt="f105"\n\t\t\t\tclass="dashicons dashicons-admin-page copy-constraint"\n\t\t\t\thref="#"\n\t\t\t\ttitle="Duplicate"\n\t\t\t/>\n\n\t\t\t<a\n\t\t\t\tdata-id="' +
__e( constraint.id ) +
'"\n\t\t\t\talt="f105"\n\t\t\t\tclass="dashicons dashicons-trash remove-constraint"\n\t\t\t\thref="#"\n\t\t\t\ttitle="Remove"\n\t\t\t/>\n\t\t</li>\n\t</ul>\n\n\t<p class="constraint-title">\n\t\t' +
__e( constraint.title ) +
'\n\t</p>\n\n\t<select data-id="' +
__e( constraint.id ) +
'" class="constraint-operator">\n\t\t';
 _.each(view.operatorsFor(constraint.valueType), function(operator) { ;
__p += '\n\t\t<option value="' +
__e( operator ) +
'" ' +
((__t = ( constraint.operator === operator ? 'selected="selected"' : ''  )) == null ? '' : __t) +
'">\n\t\t' +
__e( view.operatorLabelFor(operator) ) +
'\n\t\t</option>\n\t\t';
 }) ;
__p += '\n\t</select>\n\n\t<input\n\t\ttype="text"\n\t\tclass="constraint-value"\n\t\tdata-id="' +
__e( constraint.id ) +
'"\n\t\tvalue="' +
__e( constraint.value ) +
'" />\n\n\t<select data-id="' +
__e( constraint.id ) +
'" class="constraint-conjunction">\n\t\t';
 _.each(view.conjunctions, function(conjunction) { ;
__p += '\n\t\t<option value="' +
__e( conjunction ) +
'" ' +
((__t = ( constraint.conjunction === conjunction ? 'selected="selected"' : ''  )) == null ? '' : __t) +
'">\n\t\t' +
__e( conjunction ) +
'\n\t\t</option>\n\t\t';
 }) ;
__p += '\n\t</select>\n</li>\n';

}
return __p
};

this["JST"]["src/templates/constraints_menu.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {

 _.each(constraints, function(constraint, index) { ;
__p += '\n\t<li>\n\t\t<a\n\t\t\tdata-id="' +
__e( index ) +
'"\n\t\t\ttitle="Click to add" href="#" >\n\t\t\t' +
__e( constraint.title ) +
'\n\t\t</a>\n\t</li>\n';
 }) ;
__p += '\n';

}
return __p
};

this["JST"]["src/templates/empty_constraints.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<li>\n\t<p class="constraint-empty">Click to add filters</p>\n</li>\n';

}
return __p
};

this["JST"]["src/templates/entry_answer.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {

 if (answerType === 'choice') { ;
__p += '\n\t<select id="constraint-value-select-' +
__e( constraint.id ) +
'" style="width: 40%">\n\t';
 _.each(choices, function(choice, index) { ;
__p += '\n\t\t<option value="' +
__e( choice.value ) +
'" ' +
((__t = ( index === currentChoice ? 'selected="selected"' : ''  )) == null ? '' : __t) +
'">\n\t\t' +
__e( choice.label ) +
'\n\t\t</option>\n\t';
 }); ;
__p += '\n\t</select>\n';
 } else { ;
__p += '\n\t<input\n\t\ttype="text"\n\t\tclass="constraint-value"\n\t\tdata-id="' +
__e( constraint.id ) +
'"\n\t\tid="constraint-value-input-' +
__e( constraint.id ) +
'"\n\t\tvalue="' +
__e( constraint.value ) +
'" />\n';
 } ;
__p += '\n\n';

}
return __p
};

this["JST"]["src/templates/entry_constraint_item.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {
__p += '<li>\n\t<ul class="constraint-toolbar">\n\t\t<li>\n\t\t\t<a\n\t\t\t\tdata-id="' +
__e( constraint.id ) +
'"\n\t\t\t\talt="f105"\n\t\t\t\tclass="dashicons dashicons-admin-page copy-constraint"\n\t\t\t\thref="#"\n\t\t\t\ttitle="Duplicate"\n\t\t\t/>\n\n\t\t\t<a\n\t\t\t\tdata-id="' +
__e( constraint.id ) +
'"\n\t\t\t\talt="f105"\n\t\t\t\tclass="dashicons dashicons-trash remove-constraint"\n\t\t\t\thref="#"\n\t\t\t\ttitle="Remove"\n\t\t\t/>\n\t\t</li>\n\t</ul>\n\n\t<p class="constraint-title">\n\t\t' +
__e( constraint.title ) +
'\n\t</p>\n\n\t<div class="entry-select-group">\n\t\t<label><strong>Select Contest: </strong></label>\n\t\t<select class="entry-select-choice" id="entry-select-type-' +
__e( constraint.id ) +
'">\n\t\t</select>\n\t</div>\n\n\t<div class="entry-select-group">\n\t\t<label><strong>Select Question: </strong></label>\n\t\t<select class="entry-select-choice" id="entry-select-field-' +
__e( constraint.id ) +
'">\n\t\t</select>\n\t</div>\n\n\t<div class="entry-answer-group">\n\t\t<label><strong>Select Answer: </strong></label>\n\t\t<select data-id="' +
__e( constraint.id ) +
'" class="constraint-operator">\n\t\t\t';
 _.each(view.operatorsFor(constraint.valueType), function(operator) { ;
__p += '\n\t\t\t<option value="' +
__e( operator ) +
'" ' +
((__t = ( constraint.operator === operator ? 'selected="selected"' : ''  )) == null ? '' : __t) +
'">\n\t\t\t' +
__e( view.operatorLabelFor(operator) ) +
'\n\t\t\t</option>\n\t\t\t';
 }) ;
__p += '\n\t\t</select>\n\n\t\t<div id="entry-answer-' +
__e( constraint.id ) +
'" style="display: inline">\n\t\t</div>\n\n\t\t<select data-id="' +
__e( constraint.id ) +
'" class="constraint-conjunction">\n\t\t\t';
 _.each(view.conjunctions, function(conjunction) { ;
__p += '\n\t\t\t<option value="' +
__e( conjunction ) +
'" ' +
((__t = ( constraint.conjunction === conjunction ? 'selected="selected"' : ''  )) == null ? '' : __t) +
'">\n\t\t\t' +
__e( conjunction ) +
'\n\t\t\t</option>\n\t\t\t';
 }) ;
__p += '\n\t\t</select>\n\t</div>\n</li>\n';

}
return __p
};

this["JST"]["src/templates/entry_select.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {

 _.each(choices, function(choice, index) { ;
__p += '\n\t<option value="' +
__e( choice.value ) +
'" ' +
((__t = ( index === currentChoice ? 'selected="selected"' : ''  )) == null ? '' : __t) +
'">\n\t' +
__e( choice.label ) +
'\n\t</option>\n';
 }); ;
__p += '\n';

}
return __p
};

this["JST"]["src/templates/entry_select_type.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
with (obj) {

 _.each(choices, function(choice) { ;
__p += '\n\t<option value="' +
__e( choice ) +
'" ' +
((__t = ( choice === currentChoice ? 'selected="selected"' : ''  )) == null ? '' : __t) +
'">\n\t' +
__e( choice ) +
'\n\t</option>\n';
 }); ;
__p += '\n';

}
return __p
};

this["JST"]["src/templates/preview_result_row.jst"] = function(obj) {
obj || (obj = {});
var __t, __p = '', __e = _.escape;
with (obj) {
__p += '<tr class="' +
__e( index % 2 ? 'alternate' : '' ) +
'">\n\t<td>\n\t\t<a href="#" class="open-member-page-text">' +
__e( account ) +
'</a>\n\t\t<a href="#" alt="f105" class="dashicons dashicons-external open-member-page"></a>\n\t</td>\n</tr>\n';

}
return __p
};
var escapeValue = function(source) {
	if (typeof(source) === 'string') {
		source = source.replace(/"/g, 'C_DOUBLE_QUOTE');
		source = source.replace(/'/g, 'C_SINGLE_QUOTE');
		source = source.replace(/\\/g, 'C_BACKSLASH');
	}

	return source;
};

var unescapeValue = function(source) {
	if (typeof(source) === 'string') {
		source = source.replace(/C_DOUBLE_QUOTE/g, '"');
		source = source.replace(/C_SINGLE_QUOTE/g, "'");
		source = source.replace(/C_BACKSLASH/g, "\\");
	}

	return source;
};

var getTemplate = function(name) {
	return window.JST['src/templates/' + name + '.jst'];
};

var renderTemplate = function(name, data, settings) {
	if (!settings) {
		settings = {};
	}

	var template = getTemplate(name);
	var html     = template(data);

	return $(html);
};

if (typeof Object.create != 'function') {
	Object.create = (function() {
		var Object = function() {};
		return function (prototype) {
			if (arguments.length > 1) {
				throw Error('Second argument not supported');
			}
			if (typeof prototype != 'object') {
				throw TypeError('Argument must be an object');
			}
			Object.prototype = prototype;
			var result = new Object();
			Object.prototype = null;
			return result;
		};
	})();
}

var WpAjaxApi = function(config) {
	this.config = config;
};

WpAjaxApi.prototype = {

	nonceFor: function(action) {
		return this.config[action + '_nonce'];
	},

	urlFor: function(action) {
		var queryParams = {};
		queryParams[action + '_nonce'] = this.nonceFor(action);

		var url = this.config.ajax_url;
		url += url.indexOf('?') === -1 ? '?' : '&';
		url += $.param(queryParams);

		return url;
	},

	request: function(action, data) {
		if (!data) {
			data = {};
		}

		var url         = this.urlFor(action);
		var requestData = {
			'action': action,
			'action_data': JSON.stringify(data)
		};

		return $.post(url, requestData);
	},

};


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

var MemberQueryUpdater = function(store) {
	this.store = store;
	this.store.on('change', $.proxy(this.didStoreChange, this));
	this.store.on('inplaceChange', $.proxy(this.didStoreChange, this));
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
		var directQuery = $.trim($('.direct-query-input').val());

		if (directQuery !== '') {
			query = directQuery;
		}

		$('#constraints').attr('value', constraints);
		$('#query').attr('value', query);

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
		var templateData = {
			view: this,
			constraints: this.store.available,
		};

		var listItems = renderTemplate('constraints_menu', templateData);
		this.container.html(listItems);
	}

};

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

var EntryConstraintItemView = function(container, constraint) {
	ConstraintItemView.call(this, container, constraint);
	this.entryTypes = [];
	this.entryFields = [];
};

EntryConstraintItemView.prototype = Object.create(ConstraintItemView.prototype);
EntryConstraintItemView.prototype.constructor = EntryConstraintItemView;

var p = EntryConstraintItemView.prototype;
p.render = function() {
	var templateData = {
		view: this,
		constraint: this.constraint
	};

	var html = renderTemplate('entry_constraint_item', templateData);
	this.container.append(html);
	this.loadEntryTypes();
};

p.loadEntryTypes = function() {
	var params = { entryType: 'contest' };
	ajaxApi.request('list_entry_types', params)
		.then($.proxy(this.didLoadEntryTypes, this))
		.fail($.proxy(this.didLoadEntryTypesError, this));
};

p.didLoadEntryTypes = function(response) {
	this.entryTypes = response.data;

	var select = $('#entry-select-type-' + this.constraint.id);
	var html = this.renderSelect(this.entryTypes, this.constraint.entryTypeID);

	select.html(html);
	select.on('change', $.proxy(this.didEntryTypeChange, this));
	select.trigger('change');
};

p.didLoadEntryTypesError = function(response) {
	console.log('didLoadEntryTypesError', response);
};

p.didEntryTypeChange = function(event) {
	var choice = $(event.target).val();
	this.constraint.entryTypeID = choice;
	this.loadEntryFields(choice);
};

p.loadEntryFields = function(entryTypeID) {
	var params = { entryTypeID: entryTypeID };
	ajaxApi.request('list_entry_fields', params)
		.then($.proxy(this.didLoadEntryFields, this))
		.fail($.proxy(this.didLoadEntryFieldsError, this));
};

p.didLoadEntryFields = function(response) {
	this.entryFields = response.data;

	var select = $('#entry-select-field-' + this.constraint.id);
	var html   = this.renderSelect(this.entryFields, this.constraint.entryFieldID);

	select.html(html);
	select.on('change', $.proxy(this.didEntryFieldChange, this));
	select.trigger('change');
};

p.didLoadEntryFieldsError = function(response) {
	console.log('didLoadEntryFieldsError', response);
};

p.didEntryFieldChange   = function(event) {
	var choice          = $(event.target).val();
	var field           = this.getFieldByID(choice);
	var type            = field.type;
	var answerType = field.type === 'select' || field.type === 'checkbox' ? 'choice' : '';
	var templateData = {
		view: this,
		choices: field.choices,
		currentChoice: this.getChoiceIndex(field.choices, this.constraint.value),
		answerType: answerType,
		constraint: this.constraint
	};

	this.constraint.entryFieldID = field.value;
	this.constraint.entryFieldType = field.type;

	var html = renderTemplate('entry_answer', templateData);
	var div = $('#entry-answer-' + this.constraint.id);
	div.html(html);

	this.constraint.value = '';

	if (answerType === 'choice') {
		div.on('change', $.proxy(this.didEntryAnswerChange, this));
		var select = $('#constraint-value-select-' + this.constraint.id);
		select.trigger('change');
	} else {
		var input = $('#constraint-value-input-' + this.constraint.id);
		input.trigger('change');
	}
};

p.didEntryAnswerChange = function(event) {
	var target = $(event.target);
	var value = target.val();

	this.constraint.value = value;
	store.notify('inplaceChange');
	return false;
};

p.getFieldByID = function(id) {
	return _.find(this.entryFields, function(field) {
		return field.value == id;
	});
};

p.getChoiceIndex = function(choices, choiceID) {
	var n = choices.length;
	for (var i = 0; i < n; i++) {
		choice = choices[i];
		if (choice.value == choiceID) {
			return i;
		}
	}

	return -1;
};

p.renderSelect = function(choices, choiceID) {
	var templateData = {
		view: this,
		id: this.constraint.id,
		choices: choices,
		currentChoice: this.getChoiceIndex(choices, choiceID)
	};

	var html = renderTemplate('entry_select', templateData);
	return html;
};

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
		if (query === '') {
			this.setStatus('Nothing to Preview, please add some filters.');
			return;
		}

		this.setStatus('Searching, Please wait ...');

		ajaxApi.request('preview_member_query', { query: query })
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
		var templateData = {
			view: this,
			index: index,
			account: account
		};

		return renderTemplate('preview_result_row', templateData);
	},

	setStatus: function(message) {
		var div = $('.count-status');
		div.text(message);
	}

};

var $ = jQuery;
var QueryBuilderApp = function() {
	$(document).ready($.proxy(this.initialize, this));
};

QueryBuilderApp.prototype = {

	initialize: function() {
		// TODO: IMPORTANT, should not be global
		window.ajaxApi    = new WpAjaxApi(member_query_meta);

		this.store        = window.store = new ConstraintStore();
		this.menuView     = new MenuView(this.store);
		this.listView     = new ConstraintListView(this.store);
		this.queryUpdater = new MemberQueryUpdater(this.store);
		this.previewView  = new PreviewView(this.queryUpdater);

		this.previewView.preview(member_query_data.query);
		this.queryUpdater.update();
	},

};

var app = new QueryBuilderApp();

