var matchHeights = function( elements ) {

	if ( 0 === elements.length ) {
		return;
	}

	var height = 0;

	elements.forEach( function( el, i ) {
		if ( height < el.offsetHeight ) {
			height = el.offsetHeight;
		}
	} );

	elements.forEach( function( el, i ) {
		el.style.minHeight = height + 'px';
	} );

};