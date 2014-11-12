var FavoriteConstraintView = ConstraintView.extend({

	template: getTemplate('favorite_constraint'),

	initialize: function(model, opts) {
		ConstraintView.prototype.initialize.call(this, model, opts);
	},

});
