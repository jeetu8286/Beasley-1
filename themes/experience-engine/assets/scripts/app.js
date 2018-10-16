import React, { Fragment } from 'react';
import ReactDOM from 'react-dom';

import ContentDispatcher from './modules/ContentDispatcher';
import LivePlayer from './modules/LivePlayer';

import '../styles/main.css';

const root = document.createElement( 'div' );
document.body.appendChild( root );

const app = (
	<Fragment>
		<ContentDispatcher />
		<LivePlayer />
	</Fragment>
);

ReactDOM.render( app, root );
