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
	<div className="share">
		<span>Share</span>
		<button className="facebook" onClick={handleFacebookClick} aria-label="Share this episode on Facebook">
			<svg width="8" height="17" viewBox="0 0 8 17" fill="none" xmlns="http://www.w3.org/2000/svg">
				<title>Facebook</title>
				<path d="M4.7791 16.2235H1.9124V8.57393H0V5.93765L1.9124 5.93679L1.9093 4.38368C1.9093 2.23294 2.49249 0.924316 5.02592 0.924316H7.13518V3.5611H5.81696C4.8304 3.5611 4.78295 3.92948 4.78295 4.61717L4.77904 5.93679H7.15L6.87049 8.57307L4.78101 8.57393L4.7791 16.2235Z" />
			</svg>
		</button>
		<button className="twitter" onClick={handleTwitterClick} aria-label="Share this episode on Twitter">
			<svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
				<title>Twitter</title>
				<path d="M15.13 2.37984C14.5733 2.6267 13.9751 2.79358 13.3472 2.86861C13.988 2.48445 14.4802 1.87615 14.712 1.15127C14.1122 1.50703 13.4479 1.76533 12.7409 1.90451C12.1747 1.30124 11.3679 0.924316 10.4751 0.924316C8.76081 0.924316 7.37101 2.31406 7.37101 4.0283C7.37101 4.27159 7.39847 4.50849 7.45139 4.73571C4.87161 4.60622 2.58436 3.37047 1.05339 1.49243C0.786197 1.95088 0.633095 2.48409 0.633095 3.05297C0.633095 4.12992 1.18108 5.08004 2.014 5.63668C1.50517 5.62055 1.02653 5.48092 0.608065 5.24842C0.60777 5.26136 0.60777 5.27436 0.60777 5.28745C0.60777 6.79141 1.67777 8.04595 3.09774 8.33118C2.83731 8.40207 2.56305 8.44013 2.27996 8.44013C2.07993 8.44013 1.88548 8.42063 1.69592 8.38434C2.09095 9.61749 3.23728 10.5151 4.59561 10.54C3.53326 11.3727 2.19482 11.8688 0.740482 11.8688C0.489921 11.8688 0.242848 11.8542 0 11.8254C1.37369 12.7062 3.00534 13.2201 4.75829 13.2201C10.4678 13.2201 13.5901 8.49013 13.5901 4.38819C13.5901 4.25362 13.587 4.11975 13.581 3.9866C14.1876 3.54898 14.7139 3.00229 15.13 2.37984Z" />
			</svg>
		</button>
	</div>
);

export default Share;
