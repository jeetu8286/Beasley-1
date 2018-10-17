import React, { Fragment } from 'react';
import ReactDOM from 'react-dom';

import ContentDispatcher from './modules/ContentDispatcher';
import LivePlayer from './modules/LivePlayer';
import User from './modules/User';

import '../styles/main.css';

const root = document.createElement( 'div' );
document.body.appendChild( root );

const app = (
	<Fragment>
		<ContentDispatcher />
		<LivePlayer />
		<User />
	</Fragment>
);

ReactDOM.render( app, root );
