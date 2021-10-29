

	console.log('Vimeo-preroll loading.')
	var isVideoUnitAddedToPrebid = false;

	const loadVimeoPlayers = () => {
		const iframeList = Array.from(document.querySelectorAll('iframe'));
		const filteredIframeFuncResults = iframeList.filter(iframeElement => iframeElement.src && iframeElement.src.toLowerCase().indexOf('vimeo') > -1)
				  .map(filteredEl => {
				  	return loadVimeoPlayer(filteredEl)
				  });

		return filteredIframeFuncResults.length > 0;
	}

	const loadVimeoPlayer = (iframe) => {
		const vimeoplayer = new Vimeo.Player(iframe);
		let isPlayingPreroll = false;

		const prerollCallback = async () => {
			await vimeoplayer.play();
			isPlayingPreroll = false;
			console.log('Vimeo Playing');
		}

		vimeoplayer.on('play', async function () {
			console.log('Attempting to Play the video');

			if (!isPlayingPreroll) {
				isPlayingPreroll = true;
				console.log('Played And Instantly Pausing');
				await vimeoplayer.pause();
				console.log('Paused and now Playing Preroll');
				/* PREROLL CODE HERE */
				getUrlFromPrebid(prerollCallback);
			}
		});

		vimeoplayer.on('pause', async function () {
			console.log('Paused the video');
		});

		vimeoplayer.getVideoTitle().then(function (title) {
			console.log('title:', title);
		});
	}

	const oldOnload=window.onload;
	window.onload = () => {
		oldOnload && oldOnload();
		loadVimeoPlayers();
		setTimeout( ()=>{
			setUpVimeoIMA();
		}, 2000);

		console.log('Vimeo-preroll loaded.')
	}

	const getUrlFromPrebid = (prerollCallback) => {
		const {gampreroll} = window.bbgiconfig.dfp;
		const videoAdUnit = {
			code: gampreroll.unitId,
			mediaTypes: {
				video: {
					playerSize: [[640, 360]],
					context: 'instream'
				}
			},
			bids: [{
				bidder: 'resetdigital',
				params: {
					pubId: '44',
				},
			}]
		};

		pbjs.que.push(function () {
			if (!isVideoUnitAddedToPrebid) {
				isVideoUnitAddedToPrebid = true;
				pbjs.addAdUnits(videoAdUnit);
			}

			/*
			pbjs.setConfig({
				cache: {
					url: 'https://prebid.adnxs.com/pbc/v1/cache'
				}
			});
			*/

			pbjs.requestBids({
				timeout: 2000,
				adUnitCodes: [gampreroll.unitId],
				bidsBackHandler: function (bids) {
					console.log(`Preroll Bids Returned:`);
					console.log(JSON.stringify(bids));

					if (bids = {}) {
						prerollCallback();
					} else {
						const videoUrl = pbjs.adServers.dfp.buildVideoUrl({
							adUnit: videoAdUnit,
							params: {
								iu: gampreroll.unitId
							}
						});
						console.log(videoUrl);
						if (videoUrl) {
							try {
								playVimeoIMAAds(videoUrl, prerollCallback);
							} catch {
								prerollCallback();
							}
						} else {
							prerollCallback();
						}
					}
				}
			});
		});
	}


