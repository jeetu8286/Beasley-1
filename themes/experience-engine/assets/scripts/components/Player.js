import React, { Component } from 'react';

const STATUSES = {
	LIVE_PAUSE: 'Paused',
	LIVE_PLAYING: 'On Air',
	LIVE_STOP: 'Disconnected',
	LIVE_FAILED: 'Stream unavailable',
	LIVE_BUFFERING: 'Buffering...',
	LIVE_CONNECTING: 'Live stream connection in progress...',
	LIVE_RECONNECTING: 'Reconnecting live stream...',
	STREAM_GEO_BLOCKED: 'Sorry, this content is not available in your area',
	STATION_NOT_FOUND: 'Station not found',
};

class Player extends Component {

	static _errorCatcher( prefix ) {
		return ( e ) => {
			const { data } = e;
			const { errors } = data || {};

			( errors || [] ).forEach( ( error ) => {
				// eslint-disable-next-line no-console
				console.error( `${prefix}: [${error.code}] ${error.message}` );
			} );
		};
	}

	constructor( props ) {
		super( props );

		const self = this;
		const { bbgiconfig } = window;
		const { streams } = bbgiconfig.livePlayer || {};

		self.state = {
			station: Object.keys( streams || {} )[0] || '',
			status: 'LIVE_STOP',
			volume: 100,
			cuePoint: false,
		};

		self.onPlayClick = self.handlePlayClick.bind( self );
		self.onPauseClick = self.handlePauseClick.bind( self );
		self.onResumeClick = self.handleResumeClick.bind( self );
		self.onVolumeChange = self.handleVolumeChange.bind( self );
		self.onStreamStart = self.handleStreamStart.bind( self );
		self.onStreamStatus = self.handleStreamStatus.bind( self );
		self.onTrackCuePoint = self.handleTrackCuePoint.bind( self );
	}

	componentDidMount() {
		const self = this;

		// @see: https://userguides.tritondigital.com/spc/tdplay2/
		self.player = new window.TDSdk( {
			coreModules: [
				{
					id: 'MediaPlayer',
					playerId: 'td_container',
					techPriority: ['Html5'],
				},
				{
					id: 'NowPlayingApi',
				},
				{
					id: 'TargetSpot'
				},
				{
					id: 'SyncBanners',
					elements: [
						{
							id: 'audio-ad-inplayer',
							width: 320,
							height: 50
						}
					]
				},
			],
			playerReady: self.handlePlayerReady.bind( self ),
			configurationError: Player._errorCatcher( 'Configuration Error' ),
			moduleError: Player._errorCatcher( 'Module Error' ),
		} );
	}

	handlePlayerReady() {
		const self = this;
		const { player } = self;

		player.addEventListener( 'stream-start', self.onStreamStart );
		player.addEventListener( 'stream-status', self.onStreamStatus );

		player.addEventListener( 'track-cue-point', self.onTrackCuePoint );
		player.addEventListener( 'speech-cue-point', self.onTrackCuePoint );
		player.addEventListener( 'custom-cue-point', self.onTrackCuePoint );
		player.addEventListener( 'ad-break-cue-point', self.onTrackCuePoint );
		player.addEventListener( 'ad-break-cue-point-complete', self.onTrackCuePoint );
	}

	handlePlayClick() {
		const self = this;
		const { player, state } = self;
		const { station } = state;

		player.play( { station } );
	}

	handlePauseClick() {
		this.player.pause();
	}

	handleResumeClick() {
		this.player.resume();
	}

	handleVolumeChange( e ) {
		const self = this;
		const { player } = self;
		const { target } = e;

		let value = parseInt( target.value, 10 );
		if ( Number.isNaN( value ) || 100 < value ) {
			value = 100;
		} else if ( 0 > value ) {
			value = 0;
		}

		self.setState( { volume: value } );
		if ( player ) {
			player.setVolume( value / 100 );
		}
	}

	handleStreamStart() {
		const { player, state } = this;
		const { volume } = state;

		player.setVolume( volume / 100 );
	}

	handleStreamStatus( e ) {
		const { data } = e;
		this.setState( { status: data.code } );
	}

	handleTrackCuePoint( e ) {
		const { data } = e;
		const { cuePoint } = data || {};

		this.setState( { cuePoint } );
	}

	getCuePointInfo() {
		const { cuePoint } = this.state;
		if ( !cuePoint ) {
			return false;
		}

		let info = [];
		const { artistName, cueTitle, type } = cuePoint;
		if ( 'ad' === type ) {
			return false;
		}

		if ( cueTitle && cueTitle.length ) {
			info.push( <span key="cue-title" className="cue-point-title">{cueTitle}</span> );
		}

		if ( artistName && artistName.length ) {
			info.push( <span key="cue-artist" className="cue-point-artist">{artistName}</span> );
		}

		return info.length ? info : false;
	}

	render() {
		const self = this;
		const { volume, station, status } = self.state;

		let info = STATUSES[status] || '';
		if ( 'LIVE_PLAYING' === status ) {
			const cuePoint = this.getCuePointInfo();
			if ( cuePoint ) {
				info = cuePoint;
			}
		}

		return (
			<div className={`player ${status}`}>
				<div id="td_container" />
				<div id="audio-ad-inplayer" />

				<button type="button" className="play-btn" onClick={self.onPlayClick}>Play</button>
				<button type="button" className="pause-btn" onClick={self.onPauseClick}>Pause</button>
				<button type="button" className="resume-btn" onClick={self.onResumeClick}>Resume</button>
				<button type="button" className="loading-btn">Loading</button>

				<div>
					<b>{station}</b>
					<div>{info}</div>
				</div>

				<div>
					<label htmlFor="audio-volume">Volume:</label>
					<input type="range" id="audio-volume" min="0" max="100" step="1" value={volume} onChange={self.onVolumeChange} />
				</div>
			</div>
		);
	}

}

export default Player;
