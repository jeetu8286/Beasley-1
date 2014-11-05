var ConstraintCollection = Backbone.Collection.extend({

	model: function(attr, options) {
		var kind  = ConstraintCollection.kindForType(attr.type);
		var klass = ConstraintCollection.typesMap[kind] || Constraint;

		return new klass(attr, options);
	},

	initialize: function(models, options) {
		this.on('change add remove reset', this.save, this);
		Backbone.Collection.prototype.initialize.call(this, models, options);
	},

	save: function() {
		var json         = JSON.stringify(this.toJSON());
		var $constraints = jQuery('#constraints');

		$constraints.attr('value', json);
		console.log(json);
	}
}, {

	kindForType: function(type) {
		var typeList = type.split(':');

		if (typeList.length > 0) {
			return typeList[0];
		} else {
			return type;
		}
	},

	typesMap: {
		'system': Constraint,
		'profile': ProfileConstraint,
		'record': EntryConstraint
	}

});
