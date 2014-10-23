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
