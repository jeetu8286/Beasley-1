export function untrailingslashit( url ) {
	let newurl = url;
	while ( newurl.length && '/' === newurl[newurl.length - 1] ) {
		newurl = newurl.substring( 0, newurl.length - 1 );
	}

	return newurl;
}

export function trailingslashit( url ) {
	return untrailingslashit( url ) + '/';
}
/**
 * Checks if a URL is absolute or not.
 *
 * @param {string} url
 */
export function isAbsoluteUrl( url ) {
	if ( 'string' !== typeof url ) {
		throw new TypeError( `Expected a \`string\`, got \`${typeof url}\`` );
	}

	// Don't match Windows paths `c:\`
	if ( /^[a-zA-Z]:\\/.test( url ) ) {
		return false;
	}

	// Scheme: https://tools.ietf.org/html/rfc3986#section-3.1
	// Absolute URL: https://tools.ietf.org/html/rfc3986#section-4.3
	return /^[a-zA-Z][a-zA-Z\d+\-.]*:/.test( url );
}

export function isAudioAdOnly() {
	const { currentAdModule } = window.tdplayer.MediaPlayer.adManager || false;

	// Look for ad, if MP3, don't display it.
	if ( currentAdModule && currentAdModule.hasOwnProperty( 'html5Node' ) ) {
		const regEx = new RegExp( /\.mp3$/ );
		let adUrl = currentAdModule.html5Node.currentSrc || false;

		return regEx.test( adUrl );
	}

	return false;
}

export default {
	untrailingslashit,
	trailingslashit,
	isAudioAdOnly,
};
