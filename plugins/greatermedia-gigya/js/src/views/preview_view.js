var PreviewView = Backbone.View.extend({

	events: {
		'click .preview-member-query-button': 'didPreviewClick'
	},

	initialize: function(options) {
		this.collection = options.collection;
		this.listenTo(this.collection, 'searchError', this.didSearchError);
		this.listenTo(this.collection, 'searchSuccess', this.didSearchSuccess);

		Backbone.View.prototype.initialize.call(this, options);
		this.search();
	},

	didPreviewClick: function(event) {
		this.search();
		return false;
	},

	search: function() {
		this.setStatus('Searching, Please wait ...');
		this.collection.search();
	},

	didSearchSuccess: function() {
		var total    = this.collection.getTotalResults();
		var message = total + ' records found';

		if (total > 0) {
			message += ', showing the first 5';
		} else {
			message += '.';
		}

		this.setStatus(message);
	},

	didSearchError: function(message) {
		this.setStatus("Error: " + message);
	},

	setStatus: function(message) {
		var div = $('.count-status');
		div.text(message);
	}

});
