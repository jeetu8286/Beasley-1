import React, { Fragment, Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

import { isIOS } from '../library/browser';
import { isAudioAdOnly } from '../library/strings';

import Stations from '../components/player/Stations';
import Controls from '../components/player/Controls';
import Info from '../components/player/Info';
import Volume from '../components/player/Volume';
import Rewind from '../components/player/Rewind';

import Progress from '../components/player/Progress';
import RecentSongs from '../components/player/RecentSongs';
import Offline from '../components/player/Offline';
import Contacts from '../components/player/Contacts';
import Sponsor from '../components/player/Sponsor';

import ErrorBoundary from '../components/ErrorBoundary';

import * as actions from '../redux/actions/player';

class LivePlayer extends Component {

	constructor( props ) {
		super( props );

		this.container = document.getElementById( 'live-player' );
		this.state = { online: window.navigator.onLine };

		this.onOnline = this.handleOnline.bind( this );
		this.onOffline = this.handleOffline.bind( this );
		this.handlePlay = this.handlePlay.bind( this );
	}

	componentDidMount() {
		// TDSdk is loaded asynchronously, so we need to wait till its loaded and
		// parsed by browser, and only then start initializing the player
		const tdinterval = setInterval( () => {
			if ( window.TDSdk ) {
				// this.props.initPlayer( tdmodules );
				this.setUpPlayer();
				clearInterval( tdinterval );
			}
		}, 500 );


		window.addEventListener( 'online',  this.onOnline );
		window.addEventListener( 'offline', this.onOffline );
	}

	componentWillUnmount() {
		window.removeEventListener( 'online',  this.onOnline );
		window.removeEventListener( 'offline', this.onOffline );
	}

	/**
	 * Sets up the TdPlayer
	 */
	setUpPlayer() {
		const {
			dispatchStatusUpdate,
			dispatchNowPlayingLoaded,
			dispatchCuePoint ,
			dispatchAdPlaybackStart,
			dispatchAdPlaybackStop,
			dispatchStreamStart,
			dispatchStreamStop,
			dispatchLiveStreamSyncedStart,
			setPlayer,
		} = this.props;

		// @see: https://userguides.tritondigital.com/spc/tdplay2/
		const tdmodules = [];

		tdmodules.push( {
			id: 'MediaPlayer',
			playerId: 'td_container',
			techPriority: ['Html5'],
			idSync: {
				station: this.props.station,
			},
			geoTargeting: {
				desktop: { isActive: false },
				iOS: { isActive: false },
				android: { isActive: false },
			},
		} );

		tdmodules.push( {
			id: 'NowPlayingApi',
		} );

		tdmodules.push( {
			id: 'TargetSpot',
		} );

		tdmodules.push( {
			id: 'SyncBanners',
			elements: [{ id: 'sync-banner', width: 320, height: 50 }],
		} );

		this.livePlayer = new window.TDSdk( {
			configurationError: actions.errorCatcher( 'Configuration Error' ),
			coreModules: tdmodules,
			moduleError: actions.errorCatcher( 'Module Error' ),
		} );

		this.livePlayer.addEventListener( 'stream-status', ( { data } ) => dispatchStatusUpdate( data.code ) );
		this.livePlayer.addEventListener( 'list-loaded', ( { data } ) => dispatchNowPlayingLoaded( data ) );
		this.livePlayer.addEventListener( 'track-cue-point', ( { data } ) => dispatchCuePoint( data ) );
		this.livePlayer.addEventListener( 'speech-cue-point', ( { data } ) => dispatchCuePoint( data ) );
		this.livePlayer.addEventListener( 'custom-cue-point', ( { data } ) => dispatchCuePoint( data ) );
		this.livePlayer.addEventListener( 'ad-break-cue-point', ( { data } ) => dispatchCuePoint( data ) );
		this.livePlayer.addEventListener( 'ad-break-cue-point-complete', ( { data } ) => dispatchCuePoint( data ) );
		this.livePlayer.addEventListener( 'ad-break-synced-element', dispatchLiveStreamSyncedStart );
		this.livePlayer.addEventListener( 'ad-playback-start', () => dispatchAdPlaybackStart() ); // used to dispatchPlaybackStart
		this.livePlayer.addEventListener( 'ad-playback-complete', () => dispatchAdPlaybackStop( actions.ACTION_AD_PLAYBACK_COMPLETE ) );
		this.livePlayer.addEventListener( 'stream-start', ( { data } ) => dispatchStreamStart( data ) );
		this.livePlayer.addEventListener( 'stream-stop', ( { data } ) => dispatchStreamStop( data ) );
		this.livePlayer.addEventListener(
			'ad-playback-error',
			() => {
				/*
				 * the beforeStreamStart function may be injected onto the window
				 * object from google tag manager. This function provides a callback
				 * when it is completed. Currently we are using it to play a preroll
				 * from kubient when there is no preroll provided by triton. To ensure
				 * that we do not introduce unforeseen issues we return the original
				 * ACTION_AD_PLAYBACK_ERROR type.
				 * */
				if ( window.beforeStreamStart ) {
					window.beforeStreamStart( () => dispatchAdPlaybackStop( actions.ACTION_AD_PLAYBACK_ERROR ) );
				} else {
					dispatchAdPlaybackStop( actions.ACTION_AD_PLAYBACK_ERROR ); // used to dispatch( adPlaybackStop( ACTION_AD_PLAYBACK_ERROR ) );
				}
			},
		);

		setPlayer( this.livePlayer, 'tdplayer' );
	}

	handleOnline() {
		this.setState( { online: true } );
	}

	handleOffline() {
		this.setState( { online: false } );
	}

	handlePlay() {
		const { station, play, setPlayer, playerType } = this.props;

		// Live Streams are played with tdplayer
		if ( 'tdplayer' !== playerType ) {
			setPlayer( this.livePlayer, 'tdplayer' );
		}

		play( station );
	}

	render() {
		if ( !this.container ) {
			return false;
		}

		const { online } = this.state;

		const {
			status,
			adPlayback,
			adSynced,
			pause,
			resume,
			duration,
			player,
			playerType,
		} = this.props;

		let notification = false;
		if ( ! online ) {
			notification = <Offline />;
		}

		const progressClass = ! duration ? '-live' : '-podcast';
		let { customColors } = this.container.dataset;
		const controlsStyle = {};
		const buttonsBackgroundStyle = {};
		const buttonsFillStyle = {};
		const textStyle = {};

		customColors = JSON.parse( customColors );
		controlsStyle.backgroundColor = customColors['--brand-background-color'] || customColors['--global-theme-secondary'];
		buttonsBackgroundStyle.backgroundColor = customColors['--brand-button-color'] || customColors['--global-theme-secondary'];
		buttonsFillStyle.fill = customColors['--brand-button-color'] || customColors['--global-theme-secondary'];
		buttonsFillStyle.stroke = customColors['--brand-button-color'] || customColors['--global-theme-secondary'];
		textStyle.color = customColors['--brand-text-color'] || customColors['--global-theme-secondary'];

		const isIos = isIOS();

		const children = (
			<Fragment>
				{notification}

				<div className={`preroll-wrapper${adPlayback && !isAudioAdOnly( { player, playerType } ) ? ' -active' : ''}`}>
					<div className="preroll-container">
						<div id="td_container" className="preroll-player"></div>
						<div className="preroll-notification">Live stream will be available after this brief ad from our sponsors</div>
					</div>
				</div>

				<div id="sync-banner" className={adSynced ? '' : '-hidden'} />

				<ErrorBoundary>
					<Progress className="-mobile" colors={textStyle} />
				</ErrorBoundary>

				<div className="controls" style={ controlsStyle }>
					<div className="control-section">
						<ErrorBoundary>
							<Info colors={textStyle} />
						</ErrorBoundary>
					</div>
					<div className="control-section -centered">
						<div className={`controls-wrapper -centered ${progressClass}`}>
							<ErrorBoundary>
								<RecentSongs colors={customColors} />
							</ErrorBoundary>
							<ErrorBoundary>
								<Controls
									status={status}
									play={this.handlePlay}
									pause={pause}
									resume={resume}
									colors={buttonsBackgroundStyle}
									isIos={isIos}
									progressClass={progressClass}
								/>
							</ErrorBoundary>
							<ErrorBoundary>
								<Volume colors={buttonsFillStyle} />
							</ErrorBoundary>
						</div>
						<ErrorBoundary>
							<Progress className="-desktop" colors={textStyle} />
						</ErrorBoundary>
					</div>
					<div className="control-section">
						<ErrorBoundary>
							<Rewind progressClass={progressClass} />
						</ErrorBoundary>
						<ErrorBoundary>
							<Sponsor className="controls-sponsor" minWidth={1060} />
						</ErrorBoundary>
						<ErrorBoundary>
							<Stations colors={customColors} />
						</ErrorBoundary>
						<ErrorBoundary>
							<Contacts colors={customColors} />
						</ErrorBoundary>
					</div>
				</div>

				<ErrorBoundary>
					<Sponsor className="sponsor-mobile" maxWidth="1059" style={ controlsStyle } />
				</ErrorBoundary>
			</Fragment>
		);

		return ReactDOM.createPortal( children, this.container );
	}

}

LivePlayer.propTypes = {
	station: PropTypes.string.isRequired,
	status: PropTypes.string.isRequired,
	adPlayback: PropTypes.bool.isRequired,
	adSynced: PropTypes.bool.isRequired,
	setPlayer: PropTypes.func.isRequired,
	play: PropTypes.func.isRequired,
	pause: PropTypes.func.isRequired,
	resume: PropTypes.func.isRequired,
	duration: PropTypes.number.isRequired,
	player: PropTypes.object,
	playerType: PropTypes.string,
	dispatchStatusUpdate: PropTypes.func.isRequired,
	dispatchNowPlayingLoaded: PropTypes.func.isRequired,
	dispatchCuePoint: PropTypes.func.isRequired,
	dispatchAdPlaybackStart: PropTypes.func.isRequired,
	dispatchAdPlaybackStop: PropTypes.func.isRequired,
	dispatchStreamStart: PropTypes.func.isRequired,
	dispatchStreamStop: PropTypes.func.isRequired,
	dispatchLiveStreamSyncedStart: PropTypes.func.isRequired,
};


export default connect(
	( {player} ) => ( {
		player: player.player,
		playerType: player.playerType,
		station: player.station,
		status: player.status,
		adPlayback: player.adPlayback,
		adSynced: player.adSynced,
		duration: player.duration,
	} ), {
		setPlayer: actions.setPlayer,
		dispatchStatusUpdate: actions.statusUpdate,
		dispatchNowPlayingLoaded: actions.nowPlayingLoaded,
		dispatchCuePoint: actions.cuePoint,
		dispatchAdPlaybackStart: actions.adPlaybackStart,
		dispatchAdPlaybackStop: actions.adPlaybackStop,
		dispatchStreamStart: actions.streamStart,
		dispatchStreamStop: actions.streamStop,
		dispatchLiveStreamSyncedStart: actions.liveStreamSyncedStart,
		play: actions.playStation,
		pause: actions.pause,
		resume: actions.resume,
	},
)( LivePlayer );
