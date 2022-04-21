export default function setPlayerVisibility() {
	const { streams } = window.bbgiconfig;
	console.log('called');
	if (streams.length === 0) {
		const listenLive = document.querySelector('.listen-dropdown');
		listenLive.style.display = 'none';
		const downloadApp = document.querySelector('.download');
		downloadApp.style.display = 'none';
	}
}
