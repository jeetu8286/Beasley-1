// All Sagas watches as Exports
// Sagas are used to avoid side effects in Redux
export { default as watchInitPage } from './yieldInitPage';
export { default as watchLoadingPage } from './yieldLoadingPage';
export { default as watchLoadedPage } from './yieldLoadedPage';
export { default as watchLoadedPartial } from './yieldLoadedPartial';
export { default as watchHideSplashScreen } from './yieldHideSplashScreen';
