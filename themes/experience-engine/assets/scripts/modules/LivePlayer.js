import React, { Fragment } from 'react';
import ReactDOM from 'react-dom';

import Player from '../components/Player';

const LivePlayer = () => {
	const container = document.getElementById( 'live-player' );
	if ( !container ) {
		return false;
	}

	const children = (
		<Fragment>
			<Player />
		</Fragment>
	);

	return ReactDOM.createPortal( children, container );
};

export default LivePlayer;
