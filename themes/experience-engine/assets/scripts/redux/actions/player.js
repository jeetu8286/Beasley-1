export const ACTION_INIT_TDPLAYER = 'ACTION_INIT_TDPLAYER';
export const ACTION_STATUS_CHANGE = 'ACTION_STATUS_CHANGE';
export const ACTION_CUEPOINT_CHANGE = 'ACTION_CUEPOINT_CHANGE';
export const ACTION_SET_VOLUME = 'ACTION_SET_VOLUME';
export const ACTION_PLAY_AUDIO = 'ACTION_PLAY_AUDIO';
export const ACTION_PLAY_STATION = 'ACTION_PLAY_STATION';
export const ACTION_PAUSE = 'ACTION_PAUSE';
export const ACTION_RESUME = 'ACTION_RESUME';

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
			//player.addEventListener( 'stream-start', self.onStreamStart );
			player.addEventListener( 'stream-status', onStatusChange );

			player.addEventListener( 'track-cue-point', onTrackCuePoint );
			player.addEventListener( 'speech-cue-point', onTrackCuePoint );
			player.addEventListener( 'custom-cue-point', onTrackCuePoint );
			player.addEventListener( 'ad-break-cue-point', onTrackCuePoint );
			player.addEventListener( 'ad-break-cue-point-complete', onTrackCuePoint );
		},
	} );

	const onStatusChange = ( e ) => {
		dispatch( { type: ACTION_STATUS_CHANGE, status: e.data.code } );
	};

	const onTrackCuePoint = ( e ) => {
		const { data } = e;
		const { cuePoint } = data || {};
		dispatch( { type: ACTION_CUEPOINT_CHANGE, cuePoint } );
	};

	dispatch( { type: ACTION_INIT_TDPLAYER, player } );
};

export const playAudio = ( audio ) => ( {
	type: ACTION_PLAY_AUDIO,
	audio,
} );

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
