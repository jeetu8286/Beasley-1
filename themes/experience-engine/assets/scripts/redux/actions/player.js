export const ACTION_INIT_TDPLAYER = 'ACTION_INIT_TDPLAYER';
export const ACTION_STATUS_CHANGE = 'ACTION_STATUS_CHANGE';
export const ACTION_CUEPOINT_CHANGE = 'ACTION_CUEPOINT_CHANGE';
export const ACTION_SET_VOLUME = 'ACTION_SET_VOLUME';
export const ACTION_PLAY_AUDIO = 'ACTION_PLAY_AUDIO';
export const ACTION_PLAY_STATION = 'ACTION_PLAY_STATION';
export const ACTION_PLAY_OMNY = 'ACTION_PLAY_OMNY';
export const ACTION_PAUSE = 'ACTION_PAUSE';
export const ACTION_RESUME = 'ACTION_RESUME';
export const ACTION_DURATION_CHANGE = 'ACTION_DURATION_CHANGE';
export const ACTION_TIME_CHANGE = 'ACTION_TIME_CHANGE';
export const ACTION_SEEK_POSITION = 'ACTION_SEEK_POSITION';
export const ACTION_NOW_PLAYING_LOADED = 'ACTION_NOW_PLAYING_LOADED';
export const ACTION_AD_PLAYBACK_START = 'ACTION_AD_PLAYBACK_START';
export const ACTION_AD_PLAYBACK_COMPLETE = 'ACTION_AD_PLAYBACK_COMPLETE';

export const STATUSES = {
	LIVE_PAUSE: 'LIVE_PAUSE',
	LIVE_PLAYING: 'LIVE_PLAYING',
	LIVE_STOP: 'LIVE_STOP',
	LIVE_FAILED: 'LIVE_FAILED',
	LIVE_BUFFERING: 'LIVE_BUFFERING',
	LIVE_CONNECTING: 'LIVE_CONNECTING',
	LIVE_RECONNECTING: 'LIVE_RECONNECTING',
	STREAM_GEO_BLOCKED: 'STREAM_GEO_BLOCKED',
	STATION_NOT_FOUND: 'STATION_NOT_FOUND',
};

const errorCatcher = prefix => ( e ) => {
	const { data } = e;
	const { errors } = data || {};

	( errors || [] ).forEach( ( error ) => {
		// eslint-disable-next-line no-console
		console.error( `${prefix}: [${error.code}] ${error.message}` );
	} );
};

export const initTdPlayer = ( modules ) => ( dispatch ) => {
	const onStatusChange = ( { data } ) => {
		dispatch( {
			type: ACTION_STATUS_CHANGE,
			status: data.code,
		} );
	};

	const onTrackCuePoint = ( { data } ) => {
		dispatch( {
			type: ACTION_CUEPOINT_CHANGE,
			cuePoint: ( data || {} ).cuePoint || false,
		} );
	};

	const onListLoaded = ( { data } ) => {
		dispatch( {
			type: ACTION_NOW_PLAYING_LOADED,
			...data,
		} );
	};

	const onAdPlaybackStart = ( ...params ) => {
		console.log( 'ad playback start', ...params );
		dispatch( { type: ACTION_AD_PLAYBACK_START } );
	};

	const onAdPlaybackComplete = ( ...params ) => {
		console.log( 'ad playback complete', ...params );
		dispatch( { type: ACTION_AD_PLAYBACK_COMPLETE } );
	};

	const player = new window.TDSdk( {
		coreModules: modules,
		configurationError: errorCatcher( 'Configuration Error' ),
		moduleError: errorCatcher( 'Module Error' ),
		playerReady() {
			player.addEventListener( 'stream-status', onStatusChange );
			player.addEventListener( 'list-loaded', onListLoaded );
			player.addEventListener( 'track-cue-point', onTrackCuePoint );
			player.addEventListener( 'speech-cue-point', onTrackCuePoint );
			player.addEventListener( 'custom-cue-point', onTrackCuePoint );
			player.addEventListener( 'ad-break-cue-point', onTrackCuePoint );
			player.addEventListener( 'ad-break-cue-point-complete', onTrackCuePoint );

			player.addEventListener( 'ad-playback-start', onAdPlaybackStart );
			player.addEventListener( 'ad-playback-complete', onAdPlaybackComplete );

			dispatch( {
				type: ACTION_INIT_TDPLAYER,
				player,
			} );
		},
	} );
};

export const playAudio = ( audio, title = '', artist = '' ) => ( dispatch ) => {
	const bindStatusUpdate = ( status ) => () => {
		dispatch( {
			type: ACTION_STATUS_CHANGE,
			status,
		} );
	};

	const player = new Audio( audio );

	player.addEventListener( 'loadstart', bindStatusUpdate( STATUSES.LIVE_BUFFERING ) );
	player.addEventListener( 'pause', bindStatusUpdate( STATUSES.LIVE_PAUSE ) );
	player.addEventListener( 'playing', bindStatusUpdate( STATUSES.LIVE_PLAYING ) );
	player.addEventListener( 'ended', bindStatusUpdate( STATUSES.LIVE_STOP ) );

	player.addEventListener( 'loadedmetadata', () => {
		dispatch( {
			type: ACTION_DURATION_CHANGE,
			duration: player.duration,
		} );
	} );

	player.addEventListener( 'timeupdate', () => {
		dispatch( {
			type: ACTION_TIME_CHANGE,
			time: player.currentTime,
		} );
	} );

	dispatch( {
		type: ACTION_PLAY_AUDIO,
		player,
		audio,
	} );

	dispatch( {
		type: ACTION_CUEPOINT_CHANGE,
		cuePoint: {
			type: 'track',
			cueTitle: title,
			artistName: artist,
		},
	} );
};

export const playOmny = ( audio, title = '', artist = '' ) => ( dispatch ) => {
	const bindStatusUpdate = ( status ) => () => {
		dispatch( {
			type: ACTION_STATUS_CHANGE,
			status,
		} );
	};

	const { playerjs } = window;

	const iframe = document.createElement( 'iframe' );
	iframe.src = audio;
	document.body.appendChild( iframe );

	const player = new playerjs.Player( iframe );

	player.on( 'ready', bindStatusUpdate( STATUSES.LIVE_BUFFERING ) );
	player.on( 'play', bindStatusUpdate( STATUSES.LIVE_PLAYING ) );
	player.on( 'pause', bindStatusUpdate( STATUSES.LIVE_PAUSE ) );
	player.on( 'ended', bindStatusUpdate( STATUSES.LIVE_STOP ) );

	player.on( 'timeupdate', ( e ) => {
		dispatch( {
			type: ACTION_TIME_CHANGE,
			time: e.seconds,
			duration: e.duration,
		} );
	} );

	dispatch( {
		type: ACTION_PLAY_OMNY,
		player,
		audio,
	} );

	dispatch( {
		type: ACTION_CUEPOINT_CHANGE,
		cuePoint: {
			type: 'track',
			cueTitle: title,
			artistName: artist,
		},
	} );
};

export const playStation = ( station ) => ( {
	type: ACTION_PLAY_STATION,
	station,
} );

export const pause = () => ( {
	type: ACTION_PAUSE,
} );

export const resume = () => ( {
	type: ACTION_RESUME,
} );

export const setVolume = ( volume ) => ( {
	type: ACTION_SET_VOLUME,
	volume,
} );

export const seekPosition = ( position ) => ( {
	type: ACTION_SEEK_POSITION,
	position,
} );

export default {
	initTdPlayer,
	playAudio,
	playStation,
	playOmny,
	pause,
	resume,
	setVolume,
	seekPosition,
};
