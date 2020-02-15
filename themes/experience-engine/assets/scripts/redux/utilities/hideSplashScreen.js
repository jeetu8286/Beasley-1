/**
 * @function hideSplashScreen
 */
export default function hideSplashScreen() {
	setTimeout( () => {
		const splashScreen = document.getElementById( 'splash-screen' );
		if ( splashScreen ) {
			splashScreen.parentNode.removeChild( splashScreen );
		}

		if ( 'function' === typeof window['cssVars'] ) {
			window['cssVars']( window.bbgiconfig.cssvars );
		}
	}, 2000 );
}
