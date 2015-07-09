var ZipCodeConstraintView = ConstraintView.extend({

	template: getTemplate('zip_code_constraint'),

	initialize: function(model, opts) {
		ConstraintView.prototype.initialize.call(this, model, opts);
	},

});
