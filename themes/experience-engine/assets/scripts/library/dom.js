export const removeChildren = ( element ) => {
	while ( element && element.firstChild ) {
		element.removeChild( element.firstChild );
	}
};

export const dispatchEvent = ( type ) => {
	let event = false;

	if ( 'function' === typeof( Event ) ) {
		event = new Event( type );
	} else {
		// ie11 compatibility
		event = document.createEvent( 'Event' );
		event.initEvent( type, true, true );
	}

	if ( window.dispatchEvent ) {
		window.dispatchEvent( event );
	} else if ( window.fireEvent ) {
		window.fireEvent( event );
	}
};

export default {
	removeChildren,
	dispatchEvent,
};
