import React from 'react';
import PropTypes from 'prop-types';

const Controls = ( { status, play, pause, resume } ) => (
	<div className={status}>
		<button type="button" className="play-btn" onClick={play}>
			Play
		</button>

		<button type="button" className="pause-btn" onClick={pause}>
			Pause
		</button>

		<button type="button" className="resume-btn" onClick={resume}>
			Resume
		</button>

		<button type="button" className="loading-btn">
			Loading
		</button>
	</div>
);

Controls.propTypes = {
	status: PropTypes.string.isRequired,
	play: PropTypes.func,
	pause: PropTypes.func,
	resume: PropTypes.func,
};

Controls.defaultProps = {
	play: () => {},
	pause: () => {},
	resume: () => {},
};

export default Controls;
