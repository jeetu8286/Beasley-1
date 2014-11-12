var FavoriteConstraint = Constraint.extend({

	defaults        : {
		type        : 'profile:favorites',
		operator    : 'equals',
		conjunction : 'and',
		valueType   : 'string',
		value       : '',
		favoriteType: 'music',
		category    : 'Any Category',
	}

});
