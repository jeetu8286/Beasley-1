import React from 'react';
import PropTypes from 'prop-types';

function Controls( { status, title, play, pause, resume } ) {
	return (
		<div className={`status ${status}`}>
			<button type="button" className="play-btn" onClick={play} aria-label={`Play ${title}`}>
				<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
					<path d="M16.1836 12.0055L0.910156 23.124L0.910156 0.887031L16.1836 12.0055Z" />
				</svg>
			</button>

			<button type="button" className="pause-btn" onClick={pause} aria-label="Pause">
				<svg width="13" height="23" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect width="4" height="23" rx="1" fill="#fff"/>
					<rect x="9" width="4" height="23" rx="1" fill="#fff"/>
				</svg>
			</button>

			<button type="button" className="resume-btn" onClick={resume} aria-label="Resume">
				<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
					<path d="M16.1836 12.0055L0.910156 23.124L0.910156 0.887031L16.1836 12.0055Z" />
				</svg>
			</button>

			<button type="button" className="loading-btn" aria-label="Loading">
				<div className="loading" />
			</button>
		</div>
	);
}

Controls.propTypes = {
	status: PropTypes.string.isRequired,
	title: PropTypes.string,
	play: PropTypes.func,
	pause: PropTypes.func,
	resume: PropTypes.func,
};

Controls.defaultProps = {
	title: '',
	play: () => { },
	pause: () => { },
	resume: () => { },
};

export default Controls;
