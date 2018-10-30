import React from 'react';

const getUrl = () => encodeURIComponent( window.location.href );
const getTitle = () => encodeURIComponent( document.title );

const handleFacebookClick = () => {
	const url = `https://www.facebook.com/sharer/sharer.php?u=${getUrl()}&t=${getTitle()}`;
	window.open( url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600' );
};

const handleTwitterClick = () => {
	const url = `https://twitter.com/share?url=${getUrl()}&text=${getTitle()}`;
	window.open( url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600' );
};

//const handleGoogleClick = () => {
//	const url = `https://plus.google.com/share?url=${getUrl()}`;
//	window.open( url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=350,width=480' );
//};

const Share = () => (
	<div>
		Share:

		<button type="button" onClick={handleFacebookClick}>
			Facebook
		</button>

		<button type="button" onClick={handleTwitterClick}>
			Twitter
		</button>
	</div>
);

export default Share;
