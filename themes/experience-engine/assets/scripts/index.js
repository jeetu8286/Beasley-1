// polyfills
import 'core-js/stable'; // used by babel-preset-env
import 'regenerator-runtime/runtime'; // used by babel-preset-env
import 'isomorphic-unfetch';
import 'intersection-observer';

import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';

// playerjs gets added to the window object
// and is used to play omnyAudio programatically
import 'player.js';
import './library/geotargetly';
import '../styles/main.css';
import { closestPolyfill } from './library';

closestPolyfill();

import createStore from './redux/store';
import App from './app';


const root = document.createElement( 'div' );
document.body.appendChild( root );

ReactDOM.render(
	<Provider store={createStore()}>
		<App />
	</Provider>,
	root,
);
