	console.log('Vimeo-preroll loading.')

	const loadVimeoPlayers = () => {
		const iframeList = Array.from(document.querySelectorAll('iframe'));
		iframeList.filter(iframeElement => iframeElement.src && iframeElement.src.toLowerCase().indexOf('vimeo') > -1)
				  .map(filteredEl => {
				  	return loadVimeoPlayer(filteredEl)
				  });
	}

	const loadVimeoPlayer = (iframe) => {
		const vimeoplayer = new Vimeo.Player(iframe);
		let isPlayingPreroll = false;
		let isVideoUnitAddedToPrebid = false;

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
				// For POC, fake preroll with a 5 second delay...

				/*
				setTimeout(async () => {
					await vimeoplayer.play();
					isPlayingPreroll = false;
					console.log('Vimeo Playing');
				}, 5000);
				*/
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
		console.log('Vimeo-preroll loaded.')
	}

	const getUrlFromPrebid = (prerollCallback, isVideoUnitAddedToPrebid) => {
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
				// adUnitCodes: [gampreroll.unitId],
				bidsBackHandler: function (bids) {
					console.log(`Preroll Bids Returned:`);
					console.log(JSON.stringify(bids));

					const videoUrl = pbjs.adServers.dfp.buildVideoUrl({
						adUnit: videoAdUnit,
						params: {
							iu: gampreroll.unitId
						}
					});
					console.log(videoUrl);
					prerollCallback();
				}
			});
		});
	}


