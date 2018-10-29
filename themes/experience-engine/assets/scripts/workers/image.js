onmessage = function( e ) {
	var args = {
		method: 'GET',
		mode: 'no-cors',
		cache: 'default'
	};

	fetch( e.data, args )
		.then( response => response.blob() )
		.then( () => postMessage( e.data ) );
};
