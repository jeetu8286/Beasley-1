export const ACTION_PLAY_AUDIO = 'ACTION_PLAY_AUDIO';
export const ACTION_PLAY_STATION = 'ACTION_PLAY_STATION';

export const playAudio = ( audio ) => ( {
	type: ACTION_PLAY_AUDIO,
	audio,
} );

export const playStation = ( station ) => ( {
	type: ACTION_PLAY_STATION,
	station,
} );

export default {
	playAudio,
	playStation,
};
