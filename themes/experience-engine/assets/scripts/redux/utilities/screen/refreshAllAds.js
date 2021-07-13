export default function refreshAllAds() {
	const { prebid_enabled } = window.bbgiconfig;

	if (!prebid_enabled) {
		const { googletag } = window;
		googletag.cmd.push(() => {
			googletag.pubads().refresh();
		});
		return; // EXIT FUNCTION
	}

	const pbjs = window.pbjs || {};
	pbjs.que = pbjs.que || [];

	pbjs.que.push(() => {
		const PREBID_TIMEOUT = 2000;
		// pbjs.addAdUnits(adUnits);
		pbjs.requestBids({
			bidsBackHandler: initAdserver,
			timeout: PREBID_TIMEOUT,
		});
	});

	function initAdserver() {
		const { googletag } = window;
		googletag.cmd.push(() => {
			pbjs.que.push(() => {
				if (!pbjs.initAdserverSet) {
					pbjs.initAdserverSet = true;
					pbjs.setTargetingForGPTAsync();
				}
				googletag.pubads().refresh();
			});
		});
	}
}
