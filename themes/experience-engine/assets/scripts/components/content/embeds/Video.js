import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

import { loadAssets } from '../../../library/dom';

class Video extends PureComponent {

	constructor( props ) {
		super( props );
		this.videoRef = React.createRef();
	}

	componentDidMount() {
		const self = this;

		const masterScripts = [
			'//imasdk.googleapis.com/js/sdkloader/ima3.js',
			'/wp-content/themes/experience-engine/bundle/video.min.js',
		];

		const slaveScripts = [
			'/wp-content/themes/experience-engine/bundle/videojs-contrib-ads.min.js',
			'/wp-content/themes/experience-engine/bundle/videojs.ima.min.js',
			'/wp-content/themes/experience-engine/bundle/videojs-contrib-quality-levels.min.js',
			'/wp-content/themes/experience-engine/bundle/videojs-hls-quality-selector.min.js',
		];

		const styles = [
			'/wp-content/themes/experience-engine/bundle/video-js.min.css',
			'/wp-content/themes/experience-engine/bundle/videojs-contrib-ads.css',
			'/wp-content/themes/experience-engine/bundle/videojs.ima.css',
		];

		loadAssets( masterScripts )
			.then( () => loadAssets( slaveScripts, styles ) )
			.then( self.loadVideoJs.bind( self ) )
			.catch( error => console.error( error ) ); // eslint-disable-line no-console
	}

	loadVideoJs() {
		const self = this;
		const { current: videoRef } = self.videoRef;
		if ( !videoRef ) {
			return;
		}

		const { id, src, adTagUrl } = self.props;

		const player = window.videojs( videoRef );
		const videoArgs = {
			src,
			type: 'application/x-mpegURL',
			withCredentials: true
		};

		player.src( videoArgs );
		player.hlsQualitySelector();

		if ( adTagUrl ) {
			player.ima( { id, adTagUrl } );

			let startEvent = 'click';
			if ( navigator.userAgent.match( /(iPhone|iPad|Android)/i ) ) {
				startEvent = 'touchend';
			}

			// Initialize the ad container when the video player is clicked, but only the
			// first time it's clicked.
			var initAdDisplayContainer = function() {
				player.ima.initializeAdDisplayContainer();
				videoRef.removeEventListener( startEvent, initAdDisplayContainer );
			};

			videoRef.addEventListener( startEvent, initAdDisplayContainer );
		}
	}

	render() {
		const self = this;
		const { id, poster } = self.props;

		return (
			<video id={id} ref={self.videoRef} className="video-js vjs-default-skin" controls poster={poster} />
		);
	}

}

Video.propTypes = {
	id: PropTypes.string.isRequired,
	adTagUrl: PropTypes.string.isRequired,
	poster: PropTypes.string.isRequired,
	src: PropTypes.string.isRequired,
};

export default Video;
