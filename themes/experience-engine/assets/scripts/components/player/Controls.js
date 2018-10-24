import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { playStation, pause, resume } from '../../redux/actions/player';

const Controls = ( props ) => (
	<div className={props.status}>
		<button type="button" className="play-btn" onClick={() => props.playStation( props.station )}>
			Play
		</button>

		<button type="button" className="pause-btn" onClick={() => props.pause()}>
			Pause
		</button>

		<button type="button" className="resume-btn" onClick={() => props.resume()}>
			Resume
		</button>

		<button type="button" className="loading-btn">
			Loading
		</button>
	</div>
);

Controls.propTypes = {
	station: PropTypes.string.isRequired,
	status: PropTypes.string.isRequired,
	playStation: PropTypes.func.isRequired,
	pause: PropTypes.func.isRequired,
	resume: PropTypes.func.isRequired,
};

const mapStateToProps = ( { player } ) => ( {
	station: player.station,
	status: player.status,
} );

const mapDispatchToProps = ( dispatch ) => bindActionCreators( {
	playStation,
	pause,
	resume,
}, dispatch );

export default connect( mapStateToProps, mapDispatchToProps )( Controls );
