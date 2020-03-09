// All Sagas watches as Exports
// Sagas are used to avoid side effects in Redux

// Player
export { default as watchInitTdPlayer } from './player/yieldInitTdPlayer';
export { default as watchAdPlaybackStop } from './player/yieldAdPlaybackStop';
export { default as watchPlayAudio } from './player/yieldPlayAudio';
export { default as watchPlayStation } from './player/yieldPlayStation';
export { default as watchPlayOmny } from './player/yieldPlayOmny';
export { default as watchPause } from './player/yieldPause';
export { default as watchStreamStart } from './player/yieldStreamStart';
export { default as watchStreamStop } from './player/yieldStreamStop';
export { default as watchResume } from './player/yieldResume';
export { default as watchSetVolume } from './player/yieldSetVolume';
export { default as watchCuePointChange } from './player/yieldCuePointChange';
export { default as watchSeekPosition } from './player/yieldSeekPosition';
export { default as watchAudioStart } from './player/yieldAudioStart';
export { default as watchAudioStop } from './player/yieldAudioStop';
export { default as watchAdPlaybackStart } from './player/yieldAdPlaybackStart';
export { default as watchAdPlaybackComplete } from './player/yieldAdPlaybackComplete';

// Screen
export { default as watchInitPage } from './screen/yieldInitPage';
export { default as watchLoadingPage } from './screen/yieldLoadingPage';
export { default as watchLoadedPage } from './screen/yieldLoadedPage';
export { default as watchLoadedPartial } from './screen/yieldLoadedPartial';
export { default as watchHideSplashScreen } from './screen/yieldHideSplashScreen';
