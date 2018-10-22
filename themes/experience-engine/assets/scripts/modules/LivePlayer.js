import React, { Fragment } from 'react';
import ReactDOM from 'react-dom';

import Player from '../components/player/Player';
import Stations from '../components/player/Stations';

const LivePlayer = () => {
	const container = document.getElementById( 'live-player' );
	if ( !container ) {
		return false;
	}

	const children = (
		<Fragment>
			<Player />
			<Stations />
		</Fragment>
	);

	return ReactDOM.createPortal( children, container );
};

export default LivePlayer;
