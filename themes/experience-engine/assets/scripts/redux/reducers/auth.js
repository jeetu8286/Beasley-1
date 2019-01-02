import { ACTION_SET_USER, ACTION_SET_TOKEN, ACTION_RESET_USER } from '../actions/auth';

export const DEFAULT_STATE = {
	user: null,
	token: '',
};

function reducer( state = {}, action = {} ) {
	switch ( action.type ) {
		case ACTION_SET_USER:
			return {
				...state,
				user: action.user,
			};

		case ACTION_SET_TOKEN:
			return {
				...state,
				token: action.token,
			};

		case ACTION_RESET_USER:
			return {
				...DEFAULT_STATE,
			};

		default:
			// do nothing
			break;
	}

	return state;
}

export default reducer;
