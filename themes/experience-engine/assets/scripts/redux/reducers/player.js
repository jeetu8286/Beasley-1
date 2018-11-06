import * as actions from '../actions/player';
import { getStorage } from '../../library/local-storage';

const localStorage = getStorage( 'liveplayer' );
const { bbgiconfig } = window;
const { streams } = bbgiconfig.livePlayer || {};

let tdplayer = null;
let mp3player = null;
let omnyplayer = null;

const parseVolume = ( value ) => {
	let volume = parseInt( value, 10 );
	if ( Number.isNaN( volume ) || 100 < volume ) {
		volume = 100;
	} else if ( 0 > volume ) {
		volume = 0;
	}

	return volume;
};

const loadNowPlaying = ( station ) => {
	if ( station && tdplayer && !omnyplayer && !mp3player ) {
		tdplayer.NowPlayingApi.load( { numberToFetch: 10, mount: station } );
	}
};

const fullStop = () => {
	if ( mp3player ) {
		mp3player.pause();
		mp3player = null;
	}

	if ( omnyplayer ) {
		omnyplayer.off( 'ready' );
		omnyplayer.off( 'play' );
		omnyplayer.off( 'pause' );
		omnyplayer.off( 'ended' );
		omnyplayer.off( 'timeupdate' );

		omnyplayer.pause();
		omnyplayer.elem.parentNode.removeChild( omnyplayer.elem );
		omnyplayer = null;
	}

	if ( tdplayer ) {
		tdplayer.stop();
		tdplayer.skipAd();
	}
};

const resetState = {
	audio: '',
	station: '',
	cuePoint: false,
	time: 0,
	duration: 0,
	songs: [],
	adPlayback: false,
};

export const DEFAULT_STATE = {
	...resetState,
	status: actions.STATUSES.LIVE_STOP,
	station: localStorage.getItem( 'station' ) || Object.keys( streams || {} )[0] || '', // first station by default
	volume: parseVolume( localStorage.getItem( 'volume' ) || 100 ),
};

const reducer = ( state = {}, action = {} ) => {
	switch ( action.type ) {
		case actions.ACTION_INIT_TDPLAYER:
			tdplayer = action.player;
			tdplayer.setVolume( state.volume / 100 );
			break;

		case actions.ACTION_PLAY_AUDIO:
			fullStop();

			mp3player = action.player;
			mp3player.volume = state.volume / 100;
			mp3player.play();

			return Object.assign( {}, state, resetState, { audio: action.audio } );

		case actions.ACTION_PLAY_STATION: {
			const { station } = action;

			fullStop();

			tdplayer.playAd( 'tap', {
				host: 'cmod.live.streamtheworld.com',
				type: 'preroll',
				format: 'vast',
				stationId: streams[station].station_id,
			} );

			localStorage.setItem( 'station', station );

			return Object.assign( {}, state, resetState, { station } );
		}

		case actions.ACTION_PLAY_OMNY:
			fullStop();

			omnyplayer = action.player;
			omnyplayer.play();
			// Omny doesn't support sound provider, thus we can't change/control volume :(
			// omnyplayer.setVolume( state.volume );

			return Object.assign( {}, state, resetState, { audio: action.audio } );

		case actions.ACTION_PAUSE:
			if ( mp3player ) {
				mp3player.pause();
			} else if ( omnyplayer ) {
				omnyplayer.pause();
			} else if ( tdplayer ) {
				tdplayer.pause();
			}
			break;

		case actions.ACTION_RESUME:
			if ( mp3player ) {
				mp3player.play();
			} else if ( omnyplayer ) {
				omnyplayer.play();
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
			} else if ( omnyplayer ) {
				// omnyplayer.setVolume( volume );
			} else if ( tdplayer ) {
				tdplayer.setVolume( value );
			}

			return Object.assign( {}, state, { volume } );
		}

		case actions.ACTION_CUEPOINT_CHANGE:
			loadNowPlaying( state.station );
			return Object.assign( {}, state, { cuePoint: action.cuePoint } );

		case actions.ACTION_DURATION_CHANGE:
			return Object.assign( {}, state, { duration: +action.duration } );

		case actions.ACTION_TIME_CHANGE: {
			const override = { time: +action.time };
			if ( action.duration ) {
				override.duration = +action.duration;
			}

			return Object.assign( {}, state, override );
		}

		case actions.ACTION_SEEK_POSITION: {
			const { position } = action;

			if ( mp3player ) {
				mp3player.currentTime = position;
				return Object.assign( {}, state, { time: +position } );
			} else if ( omnyplayer ) {
				omnyplayer.setCurrentTime( position );
				return Object.assign( {}, state, { time: +position } );
			}
			break;
		}

		case actions.ACTION_NOW_PLAYING_LOADED:
			return Object.assign( {}, state, { songs: action.list } );

		case actions.ACTION_AD_PLAYBACK_START:
			document.body.classList.add( 'locked' );
			return Object.assign( {}, state, { adPlayback: true } );

		case actions.ACTION_AD_PLAYBACK_ERROR:
		case actions.ACTION_AD_PLAYBACK_COMPLETE:
			document.body.classList.remove( 'locked' );

			tdplayer.play( { station: state.station } );
			loadNowPlaying( state.station );

			return Object.assign( {}, state, { adPlayback: false } );

		default:
			// do nothing
			break;
	}

	return state;
};

export default reducer;
