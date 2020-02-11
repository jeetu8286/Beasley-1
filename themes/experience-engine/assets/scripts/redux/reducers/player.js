/* eslint-disable sort-keys */
import { getStorage } from '../../library/local-storage';

// Auth action imports
import {
	ACTION_SET_USER_FEEDS,
	ACTION_UPDATE_USER_FEEDS,
	ACTION_RESET_USER,
} from '../actions/auth';

// Player action imports
import {
	ACTION_INIT_TDPLAYER,
	ACTION_STATUS_CHANGE,
	ACTION_CUEPOINT_CHANGE,
	ACTION_SET_VOLUME,
	ACTION_PLAY_AUDIO,
	ACTION_PLAY_STATION,
	ACTION_PLAY_OMNY,
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
	ACTION_STREAM_START,
	ACTION_STREAM_STOP,
	ACTION_AUDIO_START,
	ACTION_AUDIO_STOP,
	ACTION_AD_PLAYBACK_STOP,
	STATUSES,
} from '../actions/player';

const localStorage = getStorage( 'liveplayer' );
const { streams } = window.bbgiconfig || {};

// Destructure players from global
let {
	tdplayer,
	mp3player,
	omnyplayer,
} = window;

/**
 * @function parseVolume
 * Returns a parsed number from 0 to 100
 *
 * @param {Number} value - default 50 //TODO See if OK to set default
 * @returns {Number} volume
 */
export function parseVolume( value = 50 ) {
	let volume = parseInt( value, 10 );
	if ( Number.isNaN( volume ) || 100 < volume ) {
		volume = 100;
	} else if ( 0 > volume ) {
		volume = 0;
	}

	return volume;
}

/**
 * @function loadNowPlaying
 * Used to load a configuration to the NowPlaying API
 *
 * @param {Object} player Player instance
 * @param {String} station Station identifier
 */
export function loadNowPlaying( station, player ) {
	if ( station && player && !omnyplayer && !mp3player ) {
		player.NowPlayingApi.load( { numberToFetch: 10, mount: station } );
	}
}

/**
 * @function fullStop
 * Stop all players (mp3Player, omnyplayer and tdplayer)
 */
export function fullStop() {
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
}

/**
 * @function getInitialStation
 * Returns a matching stream if local storage
 * station value matches the stream.stream_call_letters
 *
 * @param {Array} streamsList Array of streams
 * @returns {String|Undefined} First match || undefined
 */
function getInitialStation( streamsList ) {
	const station = localStorage.getItem( 'station' );
	return streamsList.find( stream => stream.stream_call_letters === station );
}

/**
 * @function getNewsStreamsFromFeeds
 * Helper method to return News Streams
 *
 * @param {Array} feeds An array of feeds
 * @returns {Array} An array of items that match stream type
 */
function getNewsStreamsFromFeeds( feeds = [] ) {
	return feeds.filter( item => 'stream' === item.type && 0 < ( item.content || [] ).length ).map( item => item.content[0] );
}

/**
 * @function lyticsTrack
 * Used to interact with the LyticsTrackAudio window object
 * which is provided by the GTM implementation.
 *
 * @param {String} action The action to take (ie. play, pause, end)
 * @param {Object} params Set of parameters
 */
export function lyticsTrack( action, params ) {

	// Check for googletag
	if ( window.googletag && window.googletag.cmd ) {

		// Push to the CMD queue
		window.googletag.cmd.push( () => {

			// Abandon if no LyticsTrackAudio global
			if ( 'undefined' === typeof window.LyticsTrackAudio ) {
				return;
			}

			// If action play
			if ( 'play' === action && window.LyticsTrackAudio.set_podcastPayload ) {
				window.LyticsTrackAudio.set_podcastPayload( {
					type: 'podcast',
					name: params.artistName,
					episode: params.cueTitle,
				}, () => {
					window.LyticsTrackAudio.playPodcast();
				} );
			}

			// If action pause
			if ( 'pause' === action && window.LyticsTrackAudio.pausePodcast ) {
				window.LyticsTrackAudio.pausePodcast();
			}

			// If action end
			if ( 'end' === action && window.LyticsTrackAudio.endOfPodcast ) {
				window.LyticsTrackAudio.endOfPodcast();
			}
		} );
	}
}

const adReset = {
	adPlayback: false,
	adSynced: false,
};

const stateReset = {
	audio: '',
	station: '',
	trackType: '',
	cuePoint: false,
	time: 0,
	duration: 0,
	...adReset,
};

let initialStation = getInitialStation( streams );

export const DEFAULT_STATE = {
	...stateReset,
	status: STATUSES.LIVE_STOP,
	station: ( initialStation || streams[0] || {} ).stream_call_letters,
	volume: parseVolume( localStorage.getItem( 'volume' ) || 100 ),
	streams,
};

function reducer( state = {}, action = {} ) {

	switch ( action.type ) {

		// Catches in Saga Middleware
		// Returns unaffected state
		case ACTION_INIT_TDPLAYER:
			console.log( 'reducer: init tdplayer' );
			return state;

		// Catches in Saga Middleware
		case ACTION_PLAY_AUDIO:
			console.log( 'reducer: play audio' );
			return {
				...state,
				...stateReset,
				audio: action.audio,
				trackType: action.trackType,
			};

		// Catches in Saga Middleware
		case ACTION_PLAY_STATION:
			console.log( 'reducer: play station' );
			return {
				...state,
				...stateReset,
				station: action.station,
			};

		// Catches in Saga Middleware
		// Returns unaffected state
		case ACTION_AD_PLAYBACK_STOP:
			console.log( 'reducer: playback stop' );
			return state;

		// Catches in Saga Middleware
		case ACTION_PLAY_OMNY:
			console.log( 'reducer: play omny' );
			return {
				...state,
				...stateReset,
				audio: action.audio,
				trackType: action.trackType,
			};

		// Catches in Saga Middleware
		case ACTION_PAUSE:
			console.log( 'reducer: pause' );
			return {
				...state,
				...adReset,
			};

		// Catches in Saga Middleware
		// TODO: Where is this called from
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
			console.log( 'reducer: time change' );
			// Initialize override
			let override = null;

			// If time
			if( action.time ) {
				// +converts to number unary plus
				override.time = +action.time;
			}

			// If duration
			if( action.duration ) {
				// +converts to number unary plus
				override.duration = +action.duration;
			}

			// If override is defined
			if( override ) {
				return {
					...state,
					...override,
				};
			}

			// Otherwise, return default state
			return state;
		}

		// Catches in Saga Middleware
		case ACTION_SEEK_POSITION:
			console.log( 'reducer: seek position' );

			// If mp3player or omnyplayer defined
			if ( mp3player || omnyplayer ) {
				return {
					...state,
					time: +action.position,
				};
			}
			return state;

		case ACTION_NOW_PLAYING_LOADED:
			console.log( 'reducer: now playing loaded' );
			return {
				...state,
				songs: action.list,
			};

		//Catches in Saga Middleware
		case ACTION_STREAM_START:
			console.log( 'reducer: stream start' );
			return state;

		//Catches in Saga Middleware
		case ACTION_STREAM_STOP:
			console.log( 'reducer: stream stop' );
			return state;

		//Catches in Saga Middleware
		case ACTION_AUDIO_START:
			console.log( 'reducer: audio start' );
			return state;

		//Catches in Saga Middleware
		case ACTION_AUDIO_STOP:
			console.log( 'reducer: audio stop' );
			return state;

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
			console.log( 'reducer: ad playback complete (or ad playback error)' );
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

		//TODO: should both catch the same?
		case ACTION_UPDATE_USER_FEEDS:
		case ACTION_SET_USER_FEEDS: {

			// Set newstreams from action.feeds
			const newstreams = getNewsStreamsFromFeeds( action.feeds );

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

		default:
			return state;
	}
}

export default reducer;
