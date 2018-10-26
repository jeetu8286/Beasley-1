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
	status: actions.STATUSES.LIVE_STOP,
	audio: '',
	station: localStorage.getItem( 'station' ) || Object.keys( streams || {} )[0] || '', // first station by default
	volume: parseVolume( localStorage.getItem( 'volume' ) || 100 ),
	cuePoint: false,
};

const reducer = ( state = {}, action = {} ) => {
	switch ( action.type ) {
		case actions.ACTION_INIT_TDPLAYER:
			tdplayer = action.player;
			tdplayer.setVolume( state.volume / 100 );
			break;

		case actions.ACTION_PLAY_AUDIO:
			if ( tdplayer ) {
				tdplayer.stop();
			}

			if ( mp3player ) {
				mp3player.pause();
			}

			mp3player = action.player;
			mp3player.volume = state.volume / 100;
			mp3player.play();

			return Object.assign( {}, state, {
				audio: action.audio,
				station: '',
			} );

		case actions.ACTION_PLAY_STATION: {
			const { station } = action;

			if ( mp3player ) {
				mp3player.pause();
				mp3player = null;
			}

			if ( tdplayer ) {
				tdplayer.stop();
				tdplayer.play( { station } );
			}

			localStorage.setItem( 'station', station );

			return Object.assign( {}, state, {
				audio: '',
				station,
			} );
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

			const value = volume / 100;
			if ( mp3player ) {
				mp3player.volume = value;
			} else if ( tdplayer ) {
				tdplayer.setVolume( value );
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
