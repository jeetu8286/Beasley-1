var QueryResultCollection = Backbone.Collection.extend({

	model: QueryResult,

	initialize: function(models, options) {
		this.activeConstraints = options.activeConstraints;
		this.pollDelay         = 5; // seconds
		this.maxQueryTime      = 60;
		this.maxRetries        = Math.floor( this.maxQueryTime / this.pollDelay );
		this.retries           = 0;
		this.fetchStatusProxy  = $.proxy(this.fetchStatus, this);

		Backbone.Collection.prototype.initialize(this, models, options);
	},

	search: function() {
		this.start();
	},

	start: function() {
		this.retries = 0;

		var constraints = this.activeConstraints.toJSON();
		var data        = {
			constraints: constraints,
			mode: 'start'
		};

		ajaxApi.request('preview_member_query', data)
			.then($.proxy(this.didStartSuccess, this))
			.fail($.proxy(this.didStartError, this));
	},

	didStartSuccess: function(response) {
		if (response.success) {
			this.memberQueryID = response.data.member_query_id;
			this.trigger('searchStart', this.memberQueryID);
			this.startPolling();
		} else {
			this.didStartError(response);
		}
	},

	didStartError: function(response) {
		this.trigger('searchError', response.data);
	},

	onPoll: function() {
		this.fetchStatus();
	},

	fetchStatus: function() {
		var data = {
			mode: 'status',
			member_query_id: this.memberQueryID
		};

		this.retries++;
		ajaxApi.request('preview_member_query', data)
			.then($.proxy(this.didFetchStatusSuccess, this))
			.fail($.proxy(this.didFetchStatusError, this));
	},

	didFetchStatusSuccess: function(response) {
		if (response.success) {
			if (response.data.complete) {
				this.totalResults = response.data.total;
				this.reset(response.data.users);
				this.trigger('searchSuccess');
			} else {
				this.trigger('searchProgress', response.data.progress);
				this.startPolling();
			}
		} else {
			this.didFetchStatusError(response);
		}
	},

	didFetchStatusError: function(response) {
		this.startPolling();
	},

	getTotalResults: function() {
		return this.totalResults;
	},

	startPolling: function() {
		if (this.retries < this.maxRetries) {
			setTimeout(this.fetchStatusProxy, this.pollDelay * 1000);
		} else {
			this.trigger('searchTimeout');
		}
	}
});
