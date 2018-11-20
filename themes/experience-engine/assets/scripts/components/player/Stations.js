import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { playStation } from '../../redux/actions/player';

function Stations( { play, streams } ) {
	const stations = [];

	/* eslint-disable camelcase */
	streams.forEach( ( { title, subtitle, stream_call_letters } ) => stations.push(
		<div key={stream_call_letters}>
			<button type="button" onClick={() => play( stream_call_letters )}>{title}</button>
			<span>{subtitle}</span>
		</div>
	) );
	/* eslint-enable */

	return (
		<div>
			<b>Stations</b>
			{stations}
		</div>
	);
}

Stations.propTypes = {
	play: PropTypes.func.isRequired,
	streams: PropTypes.arrayOf( PropTypes.object ).isRequired,
};

function mapStateToProps( { player } ) {
	return {
		streams: player.streams,
	};
}

function mapDispatchToProps( dispatch ) {
	return bindActionCreators( { play: playStation }, dispatch );
}

export default connect( mapStateToProps, mapDispatchToProps )( Stations );
