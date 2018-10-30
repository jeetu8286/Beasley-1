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

const Share = () => (
	<div>
		<span>Share:</span>
		<button onClick={handleFacebookClick}>Facebook</button>
		<button onClick={handleTwitterClick}>Twitter</button>
	</div>
);

export default Share;
