var matchHeights = function( elements ) {

	if ( 0 === elements.length ) {
		return;
	}

	var height = 0;

	Array.prototype.forEach.call(elements, function(el, i) {
		if ( height < el.offsetHeight ) {
			height = el.offsetHeight;
		}
	} );

	Array.prototype.forEach.call(elements, function(el, i) {
		el.style.minHeight = height + 'px';
	} );

};