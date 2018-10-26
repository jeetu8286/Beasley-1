import React, { Fragment, Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import Stations from '../components/player/Stations';
import Controls from '../components/player/Controls';
import Info from '../components/player/Info';
import Volume from '../components/player/Volume';
import Progress from '../components/player/Progress';

import * as actions from '../redux/actions/player';

class LivePlayer extends Component {

	componentDidMount() {
		// @see: https://userguides.tritondigital.com/spc/tdplay2/
		const tdmodules = [];

		tdmodules.push( {
			id: 'MediaPlayer',
			playerId: 'td_container',
			techPriority: ['Html5'],
		} );

		tdmodules.push( { id: 'NowPlayingApi' } );
		tdmodules.push( { id: 'TargetSpot' } );

		tdmodules.push( {
			id: 'SyncBanners',
			elements: [
				{
					id: 'audio-ad-inplayer',
					width: 320,
					height: 50
				}
			]
		} );

		// TDSdk is loaded asynchronously, so we need to wait till its loaded and
		// parsed by browser, and only then start initializing the player
		const tdinterval = setInterval( () => {
			if ( window.TDSdk ) {
				this.props.initPlayer( tdmodules );
				clearInterval( tdinterval );
			}
		}, 500 );
	}

	render() {
		const self = this;
		const { station, status, play, pause, resume } = self.props;

		const container = document.getElementById( 'live-player' );
		if ( !container ) {
			return false;
		}

		const children = (
			<Fragment>
				<div id="td_container" />
				<div id="audio-ad-inplayer" />

				<div className="controls">
					<Controls status={status} play={() => play( station )} pause={pause} resume={resume} />
					<Info />
					<Progress />
					<Volume />
				</div>

				<Stations />
			</Fragment>
		);

		return ReactDOM.createPortal( children, container );
	}

}

LivePlayer.propTypes = {
	station: PropTypes.string.isRequired,
	status: PropTypes.string.isRequired,
	initPlayer: PropTypes.func.isRequired,
	play: PropTypes.func.isRequired,
	pause: PropTypes.func.isRequired,
	resume: PropTypes.func.isRequired,
};

const mapStateToProps = ( { player } ) => ( {
	station: player.station,
	status: player.status,
} );

const mapDispatchToProps = ( dispatch ) => bindActionCreators( {
	initPlayer: actions.initTdPlayer,
	play: actions.playStation,
	pause: actions.pause,
	resume: actions.resume,
}, dispatch );

export default connect( mapStateToProps, mapDispatchToProps )( LivePlayer );
