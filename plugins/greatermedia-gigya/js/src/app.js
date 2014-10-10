var $ = jQuery;
var QueryBuilderApp = function() {
	$(document).ready($.proxy(this.initialize, this));
};

QueryBuilderApp.prototype = {

	initialize: function() {
		this.store        = new ConstraintStore();
		this.menuView     = new MenuView(this.store);
		this.listView     = new ConstraintListView(this.store);
		this.queryUpdater = new MemberQueryUpdater(this.store);
		this.previewView  = new PreviewView(this.queryUpdater);

		this.previewView.preview(member_query_data.query);
		this.queryUpdater.update();
	},

};

var app = new QueryBuilderApp();

