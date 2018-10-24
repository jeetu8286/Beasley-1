import * as actions from '../actions/player';
import { getStorage } from '../../library/local-storage';

const localStorage = getStorage( 'liveplayer' );
const { bbgiconfig } = window;
const { streams } = bbgiconfig.livePlayer || {};

let tdplayer = null;
let mp3player = null;

const parseVolume = ( value ) => {
	let volume = parseInt( value, 10 );
	if ( Number.isNaN( volume ) || 100 < volume ) {
		volume = 100;
	} else if ( 0 > volume ) {
		volume = 0;
	}

	return volume;
};

export const DEFAULT_STATE = {
	status: 'LIVE_STOP',
	audio: '',
	station: localStorage.getItem( 'station' ) || Object.keys( streams || {} )[0] || '', // first station by default
	volume: parseVolume( localStorage.getItem( 'volume' ) || 100 ),
	cuePoint: false,
};

const reducer = ( state = {}, action = {} ) => {
	switch ( action.type ) {
		case actions.ACTION_INIT_TDPLAYER:
			tdplayer = action.player;
			break;

		case actions.ACTION_PLAY_AUDIO:
			if ( tdplayer ) {
				tdplayer.stop();
			}

			mp3player = new Audio( action.audio );
			mp3player.play();

			return Object.assign( {}, state, { audio: action.audio } );

		case actions.ACTION_PLAY_STATION: {
			const { station } = action;

			if ( mp3player ) {
				mp3player.pause();
			}

			if ( tdplayer ) {
				tdplayer.stop();
				tdplayer.play( { station } );
			}

			localStorage.setItem( 'station', station );

			return Object.assign( {}, state, { station } );
		}

		case actions.ACTION_PAUSE:
			if ( mp3player ) {
				mp3player.pause();
			} else if ( tdplayer ) {
				tdplayer.pause();
			}
			break;

		case actions.ACTION_RESUME:
			if ( mp3player ) {
				mp3player.play();
			} else if ( tdplayer ) {
				tdplayer.resume();
			}
			break;

		case actions.ACTION_STATUS_CHANGE:
			return Object.assign( {}, state, { status: action.status } );

		case actions.ACTION_SET_VOLUME: {
			const volume = parseVolume( action.volume );
			localStorage.setItem( 'volume', volume );

			if ( mp3player ) {
				mp3player.volume = volume;
			} else if ( tdplayer ) {
				tdplayer.setVolume( volume / 100 );
			}

			return Object.assign( {}, state, { volume } );
		}

		case actions.ACTION_CUEPOINT_CHANGE:
			return Object.assign( {}, state, { cuePoint: action.cuePoint } );

		default:
			// do nothing
			break;
	}

	return state;
};

export default reducer;
