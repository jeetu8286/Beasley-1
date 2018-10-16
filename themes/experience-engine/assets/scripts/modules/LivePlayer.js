import React from 'react';
import ReactDOM from 'react-dom';

const LivePlayer = () => {
	const container = document.getElementById( 'live-player' );
	if ( !container ) {
		return false;
	}

	return ReactDOM.createPortal( <div />, container );
};

export default LivePlayer;
