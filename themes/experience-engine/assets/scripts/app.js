import React, { Fragment } from 'react';
import ReactDOM from 'react-dom';

import ContentDispatcher from './modules/ContentDispatcher';
import ModalDispatcher from './modules/ModalDispatcher';
import LivePlayer from './modules/LivePlayer';
import UserNav from './modules/UserNav';

import '../styles/main.css';

const root = document.createElement( 'div' );
document.body.appendChild( root );

const app = (
	<Fragment>
		<ContentDispatcher />
		<ModalDispatcher />
		<LivePlayer />
		<UserNav />
	</Fragment>
);

ReactDOM.render( app, root );
