import { createStore, compose, applyMiddleware, combineReducers } from 'redux';

import modalReducer, { DEFAULT_STATE as MODAL_DEFAULT_STATE } from './reducers/modal';

export default function() {
	const middleware = [];

	let composeEnhancers = compose;
	if ( 'production' !== process.env.NODE_ENV ) {
		composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || composeEnhancers;
	}

	const rootReducer = combineReducers( {
		modal: modalReducer,
	} );

	const defaultState = {
		modal: MODAL_DEFAULT_STATE,
	};

	return createStore( rootReducer, defaultState, composeEnhancers( applyMiddleware( ...middleware ) ) );
}
