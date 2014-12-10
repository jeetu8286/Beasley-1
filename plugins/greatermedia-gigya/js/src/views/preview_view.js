var PreviewView = Backbone.View.extend({

	events: {
		'click .preview-member-query-button': 'didPreviewClick'
	},

	initialize: function(options) {
		this.collection = options.collection;
		this.listenTo(this.collection, 'searchError', this.didSearchError);
		this.listenTo(this.collection, 'searchStart', this.didSearchStart);
		this.listenTo(this.collection, 'searchProgress', this.didSearchProgress);
		this.listenTo(this.collection, 'searchSuccess', this.didSearchSuccess);
		this.listenTo(this.collection, 'searchTimeout', this.didSearchTimeout);

		Backbone.View.prototype.initialize.call(this, options);
		this.search();
		this.previewEnabled = true;
	},

	didPreviewClick: function(event) {
		if (this.previewEnabled) {
			this.search();
		}

		return false;
	},

	search: function() {
		this.setStatus('Searching, Please wait ...');
		this.collection.search();
	},

	didSearchStart: function() {
		this.setPreviewEnabled(false);
	},

	didSearchProgress: function(progress) {
		this.setStatus('Searching, Please wait ... ' + progress + '%');
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
		this.setPreviewEnabled(true);
	},

	didSearchError: function(message) {
		this.setStatus("Error: " + message);
	},

	didSearchTimeout: function() {
		this.setStatus('Error: Query timed out, please try again.');
		this.setPreviewEnabled(true);
	},

	setStatus: function(message) {
		var div = $('.count-status');
		div.text(message);
	},

	setPreviewEnabled: function(enabled) {
		var previewButton = $('.preview-member-query-button', this.el);
		previewButton.toggleClass('disabled', !enabled);

		this.previewEnabled = enabled;
	},

});
