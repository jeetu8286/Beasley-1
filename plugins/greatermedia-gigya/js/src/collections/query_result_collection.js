var QueryResultCollection = Backbone.Collection.extend({

	model: QueryResult,

	initialize: function(models, options) {
		this.activeConstraints = options.activeConstraints;
		Backbone.Collection.prototype.initialize(this, models, options);
	},

	search: function() {
		var constraints = this.activeConstraints.toJSON();
		var data        = {
			constraints: constraints
		};

		ajaxApi.request('preview_member_query', data)
			.then($.proxy(this.didPreviewSuccess, this))
			.fail($.proxy(this.didPreviewError, this));
	},

	didPreviewSuccess: function(response) {
		if (!response.success) {
			this.totalResults = 0;
			this.didPreviewError(response);
		} else {
			this.totalResults = response.data.total;
			this.reset(response.data.accounts);
			this.trigger('searchSuccess');
		}
	},

	didPreviewError: function(response) {
		this.trigger('searchError', response.data);
	},

	getTotalResults: function() {
		return this.totalResults;
	}
});
