import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import Controls from './Controls';
import Info from './Info';
import Volume from './Volume';

import { initTdPlayer } from '../../redux/actions/player';

class Player extends Component {

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
		return (
			<div className="player">
				<div id="td_container" />
				<div id="audio-ad-inplayer" />

				<div className="controls">
					<Controls />
					<Info />
					<Volume />
				</div>
			</div>
		);
	}

}

Player.propTypes = {
	initTdPlayer: PropTypes.func.isRequired,
};

const mapDispatchToProps = ( dispatch ) => bindActionCreators( { initTdPlayer }, dispatch );

export default connect( null, mapDispatchToProps )( Player );
