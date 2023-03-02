// polyfills
import 'core-js/stable'; // used by babel-preset-env
import 'regenerator-runtime/runtime'; // used by babel-preset-env
import 'isomorphic-unfetch';
import 'intersection-observer';

import React from 'react';
import ReactDOM from 'react-dom';
import { Provider } from 'react-redux';

import './library/geotargetly';
import '../styles/main.css';
import mParticle from '@mparticle/web-sdk';
import MediaSession from '@mparticle/web-media-sdk';
import { closestPolyfill } from './library';

import createStore from './redux/store';
import App from './app';

closestPolyfill();

if (window.bbgiAnalyticsConfig?.mparticle_key) {
	console.log('Configuring mparticle in bundle');
	// Configures the SDK. Note the settings below for isDevelopmentMode
	// and logLevel.
	const mParticleConfig = {
		isDevelopmentMode: true,
		logLevel: 'verbose',
		dataPlan: {
			planId: 'beasley_web',
			planVersion: 1,
		},
	};
	mParticle.init(window.bbgiAnalyticsConfig.mparticle_key, mParticleConfig);
	window.mParticle = mParticle;
	console.log(
		'Done configuring mparticle in bundle, now initializing Beasley Analytics',
	);
	window.beasleyanalytics.initializeMParticle();
	window.mediaSession = new MediaSession(
		mParticle, // mParticle SDK Instance
		'1234567', // Custom media ID, added as content_id for media events
		'Funny Internet cat video', // Custom media Title, added as content_title for media events
		120000, // Duration in milliseconds, added as content_duration for media events
		'Audio', // Content Type (Video or Audio), added as content_type for media events
		'LiveStream', // Stream Type (OnDemand or LiveStream), added as stream_type for media events
	);

	const startOptions = {
		customAttributes: {},
		currentPlayheadPosition: 0,
	};
	window.mediaSession.logMediaSessionStart(startOptions);

	const playOptions = {
		customAttributes: {},
		currentPlayheadPosition: 0,
	};
	window.mediaSession.logPlay(playOptions);
}

const root = document.createElement('div');
document.body.appendChild(root);

ReactDOM.render(
	<Provider store={createStore()}>
		<App />
	</Provider>,
	root,
);
