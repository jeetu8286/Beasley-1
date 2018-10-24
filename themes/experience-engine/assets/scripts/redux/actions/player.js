export const ACTION_INIT_TDPLAYER = 'ACTION_INIT_TDPLAYER';
export const ACTION_STATUS_CHANGE = 'ACTION_STATUS_CHANGE';
export const ACTION_CUEPOINT_CHANGE = 'ACTION_CUEPOINT_CHANGE';
export const ACTION_SET_VOLUME = 'ACTION_SET_VOLUME';
export const ACTION_PLAY_AUDIO = 'ACTION_PLAY_AUDIO';
export const ACTION_PLAY_STATION = 'ACTION_PLAY_STATION';
export const ACTION_PAUSE = 'ACTION_PAUSE';
export const ACTION_RESUME = 'ACTION_RESUME';

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
	const player = new window.TDSdk( {
		coreModules: modules,
		configurationError: errorCatcher( 'Configuration Error' ),
		moduleError: errorCatcher( 'Module Error' ),
		playerReady() {
			player.addEventListener( 'stream-status', onStatusChange );

			player.addEventListener( 'track-cue-point', onTrackCuePoint );
			player.addEventListener( 'speech-cue-point', onTrackCuePoint );
			player.addEventListener( 'custom-cue-point', onTrackCuePoint );
			player.addEventListener( 'ad-break-cue-point', onTrackCuePoint );
			player.addEventListener( 'ad-break-cue-point-complete', onTrackCuePoint );

			dispatch( {
				type: ACTION_INIT_TDPLAYER,
				player,
			} );
		},
	} );

	const onStatusChange = ( e ) => {
		dispatch( {
			type: ACTION_STATUS_CHANGE,
			status: e.data.code,
		} );
	};

	const onTrackCuePoint = ( e ) => {
		const { data } = e;
		const { cuePoint } = data || {};

		dispatch( {
			type: ACTION_CUEPOINT_CHANGE,
			cuePoint: cuePoint || false,
		} );
	};
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

export default {
	initTdPlayer,
	playAudio,
	playStation,
	pause,
	resume,
	setVolume,
};
