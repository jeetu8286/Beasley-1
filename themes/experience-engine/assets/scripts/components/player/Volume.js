import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { setVolume } from '../../redux/actions/player';

const Volume = ( { volume, set } ) => (
	<div>
		<label htmlFor="audio-volume">Volume:</label>
		<input type="range" id="audio-volume" min="0" max="100" step="1" value={volume} onChange={( e ) => set( e.target.value )} />
	</div>
);

Volume.propTypes = {
	volume: PropTypes.number.isRequired,
	set: PropTypes.func.isRequired,
};

const mapStateToProps = ( { player } ) => ( { volume: player.volume } );
const mapDispatchToProps = ( dispatch ) => bindActionCreators( { set: setVolume }, dispatch );

export default connect( mapStateToProps, mapDispatchToProps )( Volume );
