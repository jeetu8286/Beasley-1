export const ACTION_PLAY_AUDIO = 'ACTION_PLAY_AUDIO';

export const playAudio = ( src ) => ( {
	type: ACTION_PLAY_AUDIO,
	src,
} );

export default {
	playAudio,
};
