import NProgress from 'nprogress';

import * as actions from '../actions/screen';

export const DEFAULT_STATE = {
	embeds: [],
	content: '',
	error: '',
};

const reducer = ( state = {}, action = {} ) => {
	switch ( action.type ) {
		case actions.ACTION_INIT_PAGE:
			return Object.assign( {}, state, {
				embeds: action.embeds,
				content: action.content,
			} );

		case actions.ACTION_LOADING_PARTIAL:
		case actions.ACTION_LOADING_PAGE:
			NProgress.start();
			break;

		case actions.ACTION_LOADED_PAGE:
			NProgress.done();
			return Object.assign( {}, state, {
				embeds: action.embeds,
				content: action.content,
				error: action.error,
			} );

		case actions.ACTION_LOADED_PARTIAL:
			NProgress.done();
			return Object.assign( {}, state, {
				error: action.error,
				//content: state.content + action.content,
				//embeds: [...state.embeds.filter( embed => embed.params.placeholder !== action.remove ), ...action.embeds],
			} );

		default:
			// do nothing
			break;
	}

	return state;
};

export default reducer;
