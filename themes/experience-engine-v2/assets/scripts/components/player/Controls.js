import React from 'react';
import PropTypes from 'prop-types';

function Controls({
	status,
	title,
	play,
	pause,
	resume,
	colors,
	isIos,
	progressClass,
}) {
	// TODO - IOS Special Style was removed from controls.css. Remove osClass once it is determined that we will never need OS Specific logic again.
	const osClass = isIos ? '-is-ios' : '';
	return (
		<div className={`status ${status} ${osClass}`}>
			<button
				type="button"
				className="play-btn"
				onClick={play}
				aria-label={`Play ${title}`}
			>
				<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M10,0C4.5,0,0,4.5,0,10c0,5.5,4.5,10,10,10c5.5,0,10-4.5,10-10C20,4.5,15.5,0,10,0z M14,11.3l-6.2,3.6
	c-0.8,0.5-1.5,0.1-1.5-0.9V6.9c0-1,0.7-1.4,1.5-0.9L14,9.5C14.9,10,14.9,10.8,14,11.3z"
					/>
				</svg>
			</button>

			<button
				type="button"
				className="pause-btn"
				onClick={pause}
				aria-label="Pause"
				style={colors}
			>
				<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M10,0C4.5,0,0,4.5,0,10c0,5.5,4.5,10,10,10c5.5,0,10-4.5,10-10C20,4.5,15.5,0,10,0z M8.4,15.2H6.5c-0.2,0-0.3-0.1-0.3-0.3
	V6.1c0-0.2,0.1-0.3,0.3-0.3h1.9c0.2,0,0.3,0.1,0.3,0.3v8.8C8.7,15,8.5,15.2,8.4,15.2z
	 					M13.5,15.1h-1.9c-0.2,0-0.3-0.1-0.3-0.3V6
	c0-0.2,0.1-0.3,0.3-0.3h1.9c0.2,0,0.3,0.1,0.3,0.3v8.8C13.8,15,13.7,15.1,13.5,15.1z"
					/>
				</svg>
			</button>

			<button
				type="button"
				className="resume-btn"
				onClick={resume}
				aria-label="Resume"
				style={colors}
			>
				<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
					<path d="M16.1836 12.0055L0.910156 23.124L0.910156 0.887031L16.1836 12.0055Z" />
				</svg>
			</button>

			<button
				type="button"
				className="loading-btn"
				aria-label="Loading"
				style={colors}
			>
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
	colors: PropTypes.shape({}),
	isIos: PropTypes.bool,
	progressClass: PropTypes.string,
};

Controls.defaultProps = {
	title: '',
	play: () => {},
	pause: () => {},
	resume: () => {},
	colors: {},
	isIos: false,
	progressClass: '',
};

export default Controls;
