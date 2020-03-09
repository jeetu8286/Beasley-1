/* eslint-disable sort-keys */
// and is used to play omnyAudio programatically
import playerjs from 'player.js';

export const ACTION_INIT_TDPLAYER = 'PLAYER_INIT_TDPLAYER';
export const ACTION_INIT_PLAYER = 'PLAYER_INIT';
export const ACTION_STATUS_CHANGE = 'PLAYER_STATUS_CHANGE';
export const ACTION_CUEPOINT_CHANGE = 'PLAYER_CUEPOINT_CHANGE';
export const ACTION_SET_VOLUME = 'PLAYER_SET_VOLUME';
export const ACTION_PLAY_AUDIO = 'PLAYER_PLAY_AUDIO';
export const ACTION_PLAY_STATION = 'PLAYER_PLAY_STATION';
export const ACTION_PLAY_OMNY = 'PLAYER_PLAY_OMNY';
export const ACTION_PAUSE = 'PLAYER_PAUSE';
export const ACTION_RESUME = 'PLAYER_RESUME';
export const ACTION_DURATION_CHANGE = 'PLAYER_DURATION_CHANGE';
export const ACTION_TIME_CHANGE = 'PLAYER_TIME_CHANGE';
export const ACTION_SEEK_POSITION = 'PLAYER_SEEK_POSITION';
export const ACTION_NOW_PLAYING_LOADED = 'PLAYER_NOW_PLAYING_LOADED';
export const ACTION_AD_PLAYBACK_START = 'PLAYER_AD_PLAYBACK_START';
export const ACTION_AD_PLAYBACK_STOP = 'PLAYER_AD_PLAYBACK_STOP';
export const ACTION_AD_PLAYBACK_COMPLETE = 'PLAYER_AD_PLAYBACK_COMPLETE';
export const ACTION_AD_PLAYBACK_ERROR = 'PLAYER_AD_PLAYBACK_ERROR';
export const ACTION_AD_BREAK_SYNCED = 'PLAYER_AD_BREAK_SYNCED';
export const ACTION_AD_BREAK_SYNCED_HIDE = 'PLAYER_AD_BREAK_SYNCED_HIDE';
export const ACTION_STREAM_START = 'PLAYER_STREAM_START';
export const ACTION_STREAM_STOP = 'PLAYER_STREAM_STOP';
export const ACTION_AUDIO_START = 'PLAYER_AUDIO_START';
export const ACTION_AUDIO_STOP = 'PLAYER_AUDIO_STOP';
export const ACTION_SET_PLAYER_TYPE = 'PLAYER_SET_TYPE';
export const STATUSES = {
	LIVE_BUFFERING: 'LIVE_BUFFERING',
	LIVE_CONNECTING: 'LIVE_CONNECTING',
	LIVE_FAILED: 'LIVE_FAILED',
	LIVE_PAUSE: 'LIVE_PAUSE',
	LIVE_PLAYING: 'LIVE_PLAYING',
	LIVE_RECONNECTING: 'LIVE_RECONNECTING',
	LIVE_STOP: 'LIVE_STOP',
	STATION_NOT_FOUND: 'STATION_NOT_FOUND',
	STREAM_GEO_BLOCKED: 'STREAM_GEO_BLOCKED',
};

/**
 * playbackStop action creator
 * @param {*} actionType
 * @returns {Object} action payload
 * TODO: Originally this was clearing the timeout that was
 * set by the adPlaybackStart action creator. I believe this was
 * explicitly called in order to clear the global timeout if
 * this was called directly.
 */
export function adPlaybackStop( actionType ) {
	console.log( 'action: adPlaybackStop' );
	return {
		type: ACTION_AD_PLAYBACK_STOP,
		payload: {
			actionType,
		},
	};
}

/**
 * playbackStart action creator
 * @returns {Object} action payload
 * TODO: Originally this was setting a timeout that would
 * dispatch the adPlaybackStop action creator after 70 seconds
 */
export function adPlaybackStart() {
	console.log( 'action: adPlaybackStart' );
	return {
		type: ACTION_AD_PLAYBACK_START,
	};
}

/**
 * adBreakSynced action creator
 */
function adBreakSynced() {
	console.log( 'action: adBreakSynced' );
	return {
		type: ACTION_AD_BREAK_SYNCED,
	};
}

/**
 * adBreakSyncedHide action creator
 */
function adBreakSyncedHide() {
	console.log( 'action: adBreakSyncedHide' );
	return {
		type: ACTION_AD_BREAK_SYNCED_HIDE,
	};
}

/**
 * statusUpdate action creator
 * @param {String} status
 */
function statusUpdate( status ) {
	console.log( 'action: statusUpdate' );
	return {
		type: ACTION_STATUS_CHANGE,
		status,
	};
}

/**
 * streamStart action creator
 * @param {Object} data - Payload from player event
 */
function streamStart( data ) {
	console.log( 'action: streamStart' );
	return {
		type: ACTION_STREAM_START,
		data,
	};
}

/**
 * streamStop action creator
 * @param {Object} data - Payload from player event
 */
function streamStop( data ) {
	console.log( 'action: streamStop' );
	return {
		type: ACTION_STREAM_STOP,
		data,
	};
}

/**
 * cuePoint action creator
 * @param {Object} data - Payload from player event
 */
function cuePoint( data = {} ) {
	console.log( 'action: cuePoint' );
	return {
		type: ACTION_CUEPOINT_CHANGE,
		cuePoint: data.cuePoint || false,
	};
}

/**
 * nowPlayingLoaded action creator
 * @param {Object} data - Payload from player event
 */
function nowPlayingLoaded( data ) {
	console.log( 'action: nowPlayingLoaded', data );
	return {
		type: ACTION_NOW_PLAYING_LOADED,
		...data,
	};
}

/**
 * audioStart action creator
 */
function audioStart() {
	console.log( 'action: audioStart' );
	return {
		type: ACTION_AUDIO_START,
	};
}

/**
 * audioStop action creator
 */
function audioStop() {
	console.log( 'action: audioStop' );
	return {
		type: ACTION_AUDIO_STOP,
	};
}

/**
 * durationChange action creator
 * @param {String} duration - Payload from player
 */
function durationChange( duration ) {
	console.log( 'action: durationChange' );
	return {
		type: ACTION_DURATION_CHANGE,
		duration,
	};
}

/**
 * timeChange action creator
 * @param {String} time - Payload from player
 * @param {String|null} duration - Payload from player
 */
function timeChange( time, duration = null ) {
	console.log( 'action: timeChange' );
	return {
		type: ACTION_TIME_CHANGE,
		time,
		duration,
	};
}

/**
 * initializeTdPlayer action creator
 * @param {Object} player - player object reference
 */
function initializeTdPlayer( player ) {
	console.log( 'action: initializeTdPlayer' );
	return {
		type: ACTION_INIT_TDPLAYER,
		player,
	};
}

/**
 * playStation action creator
 * @param {String} station
 */
export function playStation( station ) {
	console.log( 'action: playStation' );
	return {
		type: ACTION_PLAY_STATION,
		station,
	};
}

/**
 * pause action creator
 */
export function pause() {
	console.log( 'action: pause' );
	return {
		type: ACTION_PAUSE,
	};
}

/**
 * resume action creator
 */
export function resume() {
	console.log( 'action: resume' );
	return {
		type: ACTION_RESUME,
	};
}

/**
 * setVolume action creator
 * @param {String} volume
 */
export function setVolume( volume ) {
	console.log( 'action: setVolume' );
	return {
		type: ACTION_SET_VOLUME,
		volume,
	};
}

/**
 * seekPosition action creator
 * @param {String} position
 */
export function seekPosition( position ) {
	console.log( 'action: seekPosition' );
	return {
		type: ACTION_SEEK_POSITION,
		position,
	};
}

/**
 * doPlayAudio action creator
 * @param {Object} player
 * @param {*} audio
 * @param {*} trackType
 */
function doPlayAudio( player, audio, trackType ) {
	console.log( 'action: doPlayAudio' );
	return {
		type: ACTION_PLAY_AUDIO,
		player,
		audio,
		trackType,
	};
}

/**
 * doPlayOmny action creator
 * @param {Object} player
 * @param {*} audio
 * @param {*} trackType
 */
function doPlayOmny( player, audio, trackType ) {
	return {
		type: ACTION_PLAY_OMNY,
		player,
		audio,
		trackType,
	};
}

/**
 * errorCatcher outputs helper console messages
 * @param {String} prefix
 */
function errorCatcher( prefix = '' ) {
	return e => {
		const { data } = e;
		const { errors = [] } = data;

		errors.forEach( error =>
			// eslint-disable-next-line no-console
			console.error( `${prefix}: [${error.code}] ${error.message}` ),
		);
	};
}

export function initTdPlayer( modules ) {
	console.log( 'initTdPlayer initialized' );
	return ( dispatch ) => {
		let adSyncedTimeout = false;

		function dispatchSyncedStart() {
			// hide after 35 seconds if it hasn't been hidden yet
			clearTimeout( adSyncedTimeout );
			adSyncedTimeout = setTimeout(
				() => dispatch( adBreakSyncedHide() ),
				35000,
			);
			dispatch( adBreakSynced() );
		}

		const player = new window.TDSdk( {
			configurationError: errorCatcher( 'Configuration Error' ),
			coreModules: modules,
			moduleError: errorCatcher( 'Module Error' ),
			playerReady() {
				console.log( 'player is ready, assigning listeners' );
				player.addEventListener( 'stream-status', ( { data } ) => dispatch( statusUpdate( data.code ) ) );
				player.addEventListener( 'list-loaded', ( { data } ) => dispatch( nowPlayingLoaded( data ) ) );
				player.addEventListener( 'track-cue-point', ( { data } ) => dispatch( cuePoint( data ) ) );
				player.addEventListener( 'speech-cue-point', ( { data } ) => dispatch( cuePoint( data ) ) );
				player.addEventListener( 'custom-cue-point', ( { data } ) => dispatch( cuePoint( data ) ) );
				player.addEventListener( 'ad-break-cue-point', ( { data } ) => dispatch( cuePoint( data ) ) );
				player.addEventListener( 'ad-break-cue-point-complete', ( { data } ) => dispatch( cuePoint( data ) ) );
				player.addEventListener( 'ad-break-synced-element', dispatchSyncedStart );
				player.addEventListener( 'ad-playback-start', () => dispatch( adPlaybackStart() ) ); // used to dispatchPlaybackStart
				player.addEventListener( 'ad-playback-complete', () => dispatch( adPlaybackStop( ACTION_AD_PLAYBACK_COMPLETE ) ) ); // used to dispatchPlaybackStop( ACTION_AD_PLAYBACK_COMPLETE )
				player.addEventListener( 'stream-start', ( { data } ) => dispatch( streamStart( data ) ) );
				player.addEventListener( 'stream-stop', ( { data } ) => dispatch( streamStop( data ) ) );
				player.addEventListener(
					'ad-playback-error',
					() => {
						/*
						* the beforeStreamStart function may be injected onto the window
						* object from google tag manager. This function provides a callback
						* when it is completed. Currently we are using it to play a preroll
						* from kubient when there is no preroll provided by triton. To ensure
						* that we do not introduce unforeseen issues we return the original
						* ACTION_AD_PLAYBACK_ERROR type.
						* */
						if ( window.beforeStreamStart ) {
							window.beforeStreamStart( () => dispatch( adPlaybackStop( ACTION_AD_PLAYBACK_ERROR ) ) ); // used to dispatchPlaybackStop( ACTION_AD_PLAYBACK_ERROR )( );
						} else {
							dispatch( adPlaybackStop( ACTION_AD_PLAYBACK_ERROR ) ); // used to dispatch( adPlaybackStop( ACTION_AD_PLAYBACK_ERROR ) );
						}
					},
				);
				dispatch( initializeTdPlayer( player ) );
			},
		} );
	};
}

export function playAudio( audio, cueTitle = '', artistName = '', trackType = 'live' ) {
	return ( dispatch ) => {
		const player = new Audio( audio );
		console.log( 'timeChange can activate here', player );
		player.addEventListener( 'loadstart', () => dispatch( statusUpdate( STATUSES.LIVE_BUFFERING ) ) );
		player.addEventListener( 'pause', () => dispatch( statusUpdate( STATUSES.LIVE_PAUSE ) ) );
		player.addEventListener( 'playing', () => dispatch( statusUpdate( STATUSES.LIVE_PLAYING ) ) );
		player.addEventListener( 'ended', () => dispatch( statusUpdate( STATUSES.LIVE_STOP ) ) );
		player.addEventListener( 'play', () => dispatch( audioStart() ) );
		player.addEventListener( 'pause', dispatch( audioStop() ) );
		player.addEventListener( 'ended', dispatch( audioStop() ) );
		player.addEventListener( 'abort', dispatch( audioStop() ) );
		player.addEventListener( 'loadedmetadata', () => dispatch( durationChange( player.duration ) ) );
		player.addEventListener( 'timeupdate', () => { console.log( 'timeChange dispatch' ); return dispatch( timeChange( player.currentTime ) ); } );
		dispatch( doPlayAudio( player, audio, trackType ) );
		dispatch( cuePoint( { cuePoint: { type: 'track', cueTitle, artistName } } ) );
	};
}

export function playOmny( audio, cueTitle = '', artistName = '', trackType = 'live'  ) {
	return ( dispatch ) => {
		const id = audio.replace( /\W+/g, '' );
		if ( document.getElementById( id ) ) {
			return;
		}

		const iframe = document.createElement( 'iframe' );
		iframe.id = id;
		iframe.src = audio;
		document.body.appendChild( iframe );

		const player = new playerjs.Player( iframe );

		player.on( 'ready', () => {
			dispatch( doPlayOmny( player, audio, trackType ) );
			dispatch( cuePoint( { cuePoint: { type: 'track', cueTitle, artistName } } ) );
			dispatch( statusUpdate( STATUSES.LIVE_BUFFERING ) );
		} );

		player.on( 'play', () => dispatch( statusUpdate( STATUSES.LIVE_PLAYING ) ) );
		player.on( 'pause', () => dispatch( statusUpdate( STATUSES.LIVE_PAUSE ) ) );
		player.on( 'ended', () => dispatch( statusUpdate( STATUSES.LIVE_STOP ) ) );
		player.on( 'error', ()=> errorCatcher( 'Omny Error' ) );
		player.on( 'timeupdate', ( { seconds: time, duration } ) => dispatch( timeChange( time, duration ) ) );
	};
}

export default {
	initTdPlayer,
	initializeTdPlayer,
	pause,
	playAudio,
	playOmny,
	playStation,
	resume,
	seekPosition,
	setVolume,
};
