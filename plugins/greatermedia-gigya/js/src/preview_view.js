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
