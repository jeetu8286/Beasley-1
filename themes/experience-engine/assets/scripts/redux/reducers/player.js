import { ACTION_PLAY_AUDIO } from '../actions/player';

export const DEFAULT_STATE = {
	audioSrc: '',
};

const reducer = ( state = {}, action = {} ) => {
	switch ( action.type ) {
		case ACTION_PLAY_AUDIO:
			return Object.assign( {}, state, {
				audioSrc: action.src,
			} );
		default:
			// do nothing
			break;
	}

	return state;
};

export default reducer;
