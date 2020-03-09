/* eslint-disable sort-keys */
import {
	livePlayerLocalStorage,
	getInitialStation,
	parseVolume,
	getNewsStreamsFromFeeds,
} from '../utilities/';

// Auth action imports
import {
	ACTION_SET_USER_FEEDS,
	ACTION_UPDATE_USER_FEEDS,
	ACTION_RESET_USER,
} from '../actions/auth';

// Player action imports
import {
	ACTION_SET_PLAYER,
	ACTION_STATUS_CHANGE,
	ACTION_CUEPOINT_CHANGE,
	ACTION_SET_VOLUME,
	ACTION_PAUSE,
	ACTION_RESUME,
	ACTION_DURATION_CHANGE,
	ACTION_TIME_CHANGE,
	ACTION_SEEK_POSITION,
	ACTION_NOW_PLAYING_LOADED,
	ACTION_AD_PLAYBACK_START,
	ACTION_AD_PLAYBACK_COMPLETE,
	ACTION_AD_PLAYBACK_ERROR,
	ACTION_AD_BREAK_SYNCED,
	ACTION_AD_BREAK_SYNCED_HIDE,
	ACTION_SET_PLAYER_TYPE,
	STATUSES,
	ACTION_PLAY,
} from '../actions/player';

// Destructure streams from window global
const { streams } = window.bbgiconfig || {};

// Helper object to reset some state
// Good for re-use in the reducers
const adReset = {
	adPlayback: false,
	adSynced: false,
};

// Default state object
export const DEFAULT_STATE = {
	audio: '',
	trackType: '',
	cuePoint: false,
	time: 0,
	duration: 0,
	playerType: null, // Store player type (omny, mp3, td)
	userInteraction: false, // Store userInteraction state
	status: STATUSES.LIVE_STOP,
	station: ( getInitialStation( streams ) || streams[0] || {} ).stream_call_letters,
	volume: parseVolume( livePlayerLocalStorage.getItem( 'volume' ) || 100 ),
	streams,
	...adReset,
};

// Reducer
function reducer( state = {}, action = {} ) {

	switch ( action.type ) {

		// Catches in Saga Middleware
		case ACTION_SET_PLAYER: {
			console.log( 'reducer: set player', state );

			const { playerType, player } = action.payload;

			return {
				...state,
				playerType,
				player,
			};
		}
		case ACTION_PLAY: {
			const { source, trackType } = action.payload;
			const newState = { ...state };

			if ( 'tdplayer' === state.playerType ) {
				newState.station = source;
			} else if ( ['mp3player', 'omnyplayer'].includes( state.playerType ) ) {
				newState.audio = source;
				newState.trackType = trackType;
			}

			return newState;
		}
		// Catches in Saga Middleware
		case ACTION_PAUSE:
			console.log( 'reducer: pause' );
			return {
				...state,
				...adReset,
			};

		// Catches in Saga Middleware
		case ACTION_RESUME:
			console.log( 'reducer: resume' );
			return {
				...state,
				...adReset,
			};

		// NOTE: Nothing mod'd here
		// adding console for logging purposes
		case ACTION_STATUS_CHANGE:
			console.log( 'reducer: status change' );
			return {
				...state,
				status: action.status,
			};

		// Catches in Saga Middleware
		case ACTION_SET_VOLUME: {
			console.log( 'reducer: set volume' );
			const volume = parseVolume( action.volume );
			return {
				...state,
				volume,
			};
		}

		// Catches in Saga Middleware
		case ACTION_CUEPOINT_CHANGE:
			console.log( 'reducer: cuepoint change' );
			return {
				...state,
				...adReset,
				cuePoint: action.cuePoint,
				userInteraction: false,
			};

		// NOTE: Nothing mod'd here
		// adding console for logging purposes
		case ACTION_DURATION_CHANGE:
			console.log( 'reducer: duration change' );
			return {
				...state,
				duration: +action.duration, // +converts to number unary plus
			};

		// Cleaned up checks
		// adding console for logging purposes
		case ACTION_TIME_CHANGE: {
			console.log( 'reducer: timeChange' );
			// Initialize override
			let override = {};

			// Destructure from action
			const {
				time,
				duration,
			} = action;

			// If time
			if( action.time ) {
				// +converts to number unary plus
				override.time = +time;
			}

			// If duration
			if( action.duration ) {
				// +converts to number unary plus
				override.duration = +duration;
			}

			return {
				...state,
				...override,
			};
		}

		// Catches in Saga Middleware
		case ACTION_SEEK_POSITION: {
			console.log( 'reducer: seek position' );

			// Destructure for playerType check
			const { playerType } = state;

			// Set initialUpdate userInteraction to true
			// This will always happen here
			let stateUpdate = {
				userIneraction: true,
			};

			// If mp3player or omnyplayer defined
			if (
				'mp3player' === playerType ||
				'omnyplayer' === playerType
			) {
				stateUpdate.time = +action.position;
			}
			return {
				...state,
				...stateUpdate,
			};
		}

		case ACTION_NOW_PLAYING_LOADED:
			console.log( 'reducer: now playing loaded' );
			return {
				...state,
				songs: action.list,
			};

		//Catches in Saga Middleware
		case ACTION_AD_PLAYBACK_START:
			console.log( 'reducer: ad playback start' );
			return {
				...state,
				adPlayback: true,
			};

		//Catches in Saga Middleware
		case ACTION_AD_PLAYBACK_ERROR:
		case ACTION_AD_PLAYBACK_COMPLETE: {
			console.log( 'reducer: ad playback complete or error' );
			return {
				...state,
				adPlayback: false,
			};
		}

		case ACTION_AD_BREAK_SYNCED:
			console.log( 'reducer: ad break synced' );
			return {
				...state,
				...adReset,
				adSynced: true,
			};

		case ACTION_AD_BREAK_SYNCED_HIDE:
			console.log( 'reducer: ad break synced hide' );
			return {
				...state,
				...adReset,
			};

		case ACTION_UPDATE_USER_FEEDS:
		case ACTION_SET_USER_FEEDS: {
			console.log( 'reducer: update or set user feeds' );

			// Set newstreams from action.feeds
			const newstreams = getNewsStreamsFromFeeds( action.feeds );
			let initialStation = getInitialStation( streams );

			// Create new state object
			const newstate = {
				...state,
				streams: newstreams.length ? newstreams : DEFAULT_STATE.streams,
			};

			// If no initialStation, define one
			if ( !initialStation ) {

				// Set initialStation
				initialStation = getInitialStation( newstate.streams );

				// If one returned and has stream_call_letters
				if (
					initialStation &&
					initialStation.stream_call_letters
				) {

					// Update newState object
					newstate.station = initialStation.stream_call_letters;
				}
			}
			return newstate;
		}

		case ACTION_RESET_USER:
			return {
				...state,
				station: DEFAULT_STATE.station,
				streams: DEFAULT_STATE.streams,
			};

		case ACTION_SET_PLAYER_TYPE:
			return {
				...state,
				playerType: action.payload,
			};

		default:
			return state;
	}
}

export default reducer;
