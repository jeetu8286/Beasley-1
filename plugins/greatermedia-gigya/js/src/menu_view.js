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
