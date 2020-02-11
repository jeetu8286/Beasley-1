import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';
import es6promise from 'es6-promise';
import 'isomorphic-unfetch';
import 'intersection-observer';

import createStore from './redux/store';
import App from './app';

import './library/geotargetly';
import './polyfills/closest';
import '../styles/main.css';

es6promise.polyfill();

const root = document.createElement( 'div' );
document.body.appendChild( root );

ReactDOM.render(
	<Provider store={createStore()}>
		<App />
	</Provider>,
	root,
);
