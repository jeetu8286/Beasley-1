// All Sagas watches as Exports
// Sagas are used to avoid side effects in Redux

// Player
export { default as watchInitTdPlayer } from './yieldInitTdPlayer';
export { default as watchAdPlaybackStop } from './yieldAdPlaybackStop';
export { default as watchPlayAudio } from './yieldPlayAudio';
export { default as watchPlayStation } from './yieldPlayStation';
export { default as watchPlayOmny } from './yieldPlayOmny';
export { default as watchPause } from './yieldPause';
export { default as watchStreamStart } from './yieldStreamStart';
export { default as watchStreamStop } from './yieldStreamStop';
export { default as watchResume } from './yieldResume';
export { default as watchSetVolume } from './yieldSetVolume';
export { default as watchCuePointChange } from './yieldCuePointChange';
export { default as watchSeekPosition } from './yieldSeekPosition';
export { default as watchAudioStart } from './yieldAudioStart';
export { default as watchAudioStop } from './yieldAudioStop';
export { default as watchAdPlaybackStart } from './yieldAdPlaybackStart';
export { default as watchAdPlaybackComplete } from './yieldAdPlaybackComplete';

// Screen
export { default as watchInitPage } from './yieldInitPage';
export { default as watchLoadingPage } from './yieldLoadingPage';
export { default as watchLoadedPage } from './yieldLoadedPage';
export { default as watchLoadedPartial } from './yieldLoadedPartial';
export { default as watchHideSplashScreen } from './yieldHideSplashScreen';
