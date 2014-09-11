var $ = jQuery;
var constraints = [
	{
		'type': 'influence_rank',
		'title': 'Influence Rank',
		'condition': 'greater_than',
		'value': '1',
		'join_as': 'and'
	},
	{
		'type': 'facebook_page_likes',
		'title': 'Facebook Page Likes',
		'condition': 'contains',
		'value': 'your text',
		'join_as': 'or'
	},
	{
		'type': 'communication_preferences',
		'title': 'Communication Preferences',
		'condition': 'subscribed',
		'value': 'foo',
		'join_as': 'and'
	}
];

var active_constraints = member_query_data['constraints'];
if (!active_constraints instanceof Array) {
	active_constraints = [];
}

var build_segment_filters_menu = function() {
	var container = $('.segment-filters');
	var n = constraints.length;
	var constraint;

	for (var i = 0; i < n; i++) {
		constraint = constraints[i];
		item = $('<li><a href="#" data-id="' + i + '"' + '>' + constraint.title + '</a></li> ');
		container.append(item);
	}

};

var build_active_segments = function() {
	var container = $('.segment-filters-active');
	container.empty();

	var n = active_constraints.length;
	var constraint;

	for (var i = 0; i < n; i++) {
		constraint = active_constraints[i];
		html = '<li>' +
			'<ul class="segment-filter-menu">' +
				'<li>' +
					'<a data-id="' + i + '" alt="f105" class="dashicons dashicons-admin-page copy-filter" href="#"></a>' +
				'</li>' +
				'<li>' +
					'<a data-id="' + i + '" alt="f105" class="dashicons dashicons-trash remove-filter" href="#"></a>' +
				'</li>' +
			'</ul>' +
			'<p>' + constraint.title + '</p>' +
			'<select>' +
				'<option>' + constraint.condition + '</option>' +
			'</select>' +
			'<input data-id="' + i + '" type="text" value="' + constraint.value + '" />' +
			'<select>' +
				'<option>' + constraint.join_as + '</option>' +
			'</select>' +
		'</li>';
		item = $(html);
		container.append(item);
	}

	if (n === 0) {
		item = $('<li><p>Click on filters to add</p></li>');
		container.append(item);
		container.append($('<br><br><br><br><br>'));
	}

	bind_events();
	update_json();
};

var segment_filter_item_click = function(event) {
	var id = $(event.target).attr('data-id');
	var constraint = constraints[id];
	var new_constraint = $.extend(true, {}, constraint);
	active_constraints.push(new_constraint);

	build_active_segments();
};

var segment_filter_active_item_click = function(event) {
	var id = $(event.target).attr('data-id');
	var constraint = constraints[id];
	var new_constraint = $.extend(true, {}, constraint);
	active_constraints.push(new_constraint);

	build_active_segments();
};

var copy_item_click = function(event) {
	var id = $(event.target).attr('data-id');
	var constraint = active_constraints[id];
	var new_constraint = $.extend(true, {}, constraint);
	active_constraints.splice(id, 0, new_constraint);

	build_active_segments();
};

var delete_item_click = function(event) {
	var id = $(event.target).attr('data-id');
	active_constraints.splice(id, 1);

	build_active_segments();
};

var constraint_value_input = function(event) {
	var id = $(event.target).attr('data-id');
	var value = $(event.target).attr('value');
	var constraint = active_constraints[id];
	constraint.value = value;
};

var bind_events = function() {
	$('.segment-filters-active .copy-filter').on('click', copy_item_click);
	$('.segment-filters-active .remove-filter').on('click', delete_item_click);
	$('.segment-filters-active input').on('input', constraint_value_input);
	$('.segment-filters-active input').on('keyup', update_json);
};

var update_json = function() {
	var json = JSON.stringify(active_constraints);
	$('#constraints').attr('value', json);
};

$(document).ready(function() {
	build_segment_filters_menu();
	build_active_segments();

	$('.segment-filters a').on('click', segment_filter_item_click);
});


