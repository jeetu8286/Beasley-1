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
		var directQuery = $.trim($('.direct-query-input').val());

		if (directQuery !== '') {
			query = directQuery;
		}

		$('#constraints').attr('value', constraints);
		$('#query').attr('value', query);

		return query;
	}

};
