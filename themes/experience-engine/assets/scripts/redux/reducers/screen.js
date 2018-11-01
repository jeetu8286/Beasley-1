import NProgress from 'nprogress';

import * as actions from '../actions/screen';

export const DEFAULT_STATE = {
};

const reducer = ( state = {}, action = {} ) => {
	switch ( action.type ) {
		case actions.ACTION_PAGE_LOADING:
			NProgress.start();
			break;

		case actions.ACTION_PAGE_LOADED:
			NProgress.done();
			break;

		default:
			// do nothing
			break;
	}

	return state;
};

export default reducer;
