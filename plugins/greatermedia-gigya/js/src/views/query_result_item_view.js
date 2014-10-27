var QueryResultItemView = Backbone.CollectionView.extend({

	template: getTemplate('query_result_item'),

	render: function() {
		var data = this.model.toJSON();
		var html = this.template(data);

		this.$el.html(html);
	}
});
