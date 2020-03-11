import { dispatchEvent } from './dom';

history.pushState = (f =>
	function pushState() {
		const ret = f.apply(this, arguments);
		dispatchEvent('pushState');
		dispatchEvent('locationchange');
		return ret;
	})(history.pushState);

history.replaceState = (f =>
	function replaceState() {
		const ret = f.apply(this, arguments);
		dispatchEvent('replaceState');
		return ret;
	})(history.replaceState);

window.addEventListener('popstate', () => {
	dispatchEvent('locationchange');
});

window.addEventListener('locationchange', function() {
	if (window.bbgiconfig.geotargetly && window.geotargetly) {
		try {
			window.geotargetly(document, 'script', 'style', 'head');
		} catch (e) {
			// no-op
		}
	}
});
