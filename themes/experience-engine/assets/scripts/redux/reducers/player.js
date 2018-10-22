import { ACTION_PLAY_AUDIO, ACTION_PLAY_STATION } from '../actions/player';

const { bbgiconfig } = window;
const { streams } = bbgiconfig.livePlayer || {};

export const DEFAULT_STATE = {
	audio: '',
	station: Object.keys( streams || {} )[0] || '', // first station by default
};

const reducer = ( state = {}, action = {} ) => {
	switch ( action.type ) {
		case ACTION_PLAY_AUDIO:
			return Object.assign( {}, state, {
				audio: action.audio,
			} );
		case ACTION_PLAY_STATION:
			return Object.assign( {}, state, {
				station: action.station,
			} );
		default:
			// do nothing
			break;
	}

	return state;
};

export default reducer;
