import React, { Fragment, Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import Stations from '../components/player/Stations';
import Controls from '../components/player/Controls';
import Info from '../components/player/Info';
import Volume from '../components/player/Volume';

import { initTdPlayer } from '../redux/actions/player';

class LivePlayer extends Component {

	componentDidMount() {
		// @see: https://userguides.tritondigital.com/spc/tdplay2/
		this.props.initTdPlayer( [
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
		] );
	}

	render() {
		const container = document.getElementById( 'live-player' );
		if ( !container ) {
			return false;
		}

		const children = (
			<Fragment>
				<div id="td_container" />
				<div id="audio-ad-inplayer" />

				<div className="controls">
					<Controls />
					<Info />
					<Volume />
				</div>

				<Stations />
			</Fragment>
		);

		return ReactDOM.createPortal( children, container );
	}

}

LivePlayer.propTypes = {
	initTdPlayer: PropTypes.func.isRequired,
};

const mapDispatchToProps = ( dispatch ) => bindActionCreators( { initTdPlayer }, dispatch );

export default connect( null, mapDispatchToProps )( LivePlayer );
