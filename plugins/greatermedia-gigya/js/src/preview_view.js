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

		// TODO: clean this up
		var nonce = member_query_meta.preview_nonce;
		var data  = {
			'action': 'preview_member_query',
			'action_data': JSON.stringify({
				'query': query
			})
		};

		var url = member_query_meta.ajaxurl + '?' + $.param({
			'preview_member_query_nonce': nonce,
		});

		this.setStatus('Searching, Please wait ...');

		var promise = $.post(url, data);

		promise
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
		var tr = $('<tr></tr>', { 'class': index % 2 ? 'alternate': '' });
		var td = $('<td></td>');
		var link;

		link = $('<a href="#"></a>').text(account);
		link.attr({ 'class': 'open-member-page-text' });
		td.append(link);

		link = $('<a href="#"></a>');
		link.attr({
			'alt': 'f105',
			'class': 'dashicons dashicons-external open-member-page',
		});
		td.append(link);

		tr.append(td);

		return tr;
	},

	setStatus: function(message) {
		var div = $('.count-status');
		div.text(message);
	}

};
