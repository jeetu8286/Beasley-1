import React from 'react';
import PropTypes from 'prop-types';

function Controls({
	status,
	title,
	play,
	pause,
	resume,
	initRadioMode,
	colors,
	isIos,
	progressClass,
}) {
	const osClass = isIos ? '-is-ios' : '';
	const shouldShowInitRadio = progressClass === '-podcast';
	return (
		<div className={`status ${status} ${osClass} ${progressClass}`}>
			<button
				type="button"
				className="play-btn"
				onClick={play}
				aria-label={`Play ${title}`}
				style={colors}
			>
				<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
					<path d="M16.1836 12.0055L0.910156 23.124L0.910156 0.887031L16.1836 12.0055Z" />
				</svg>
			</button>

			<button
				type="button"
				className="pause-btn"
				onClick={pause}
				aria-label="Pause"
				style={colors}
			>
				<svg
					width="13"
					height="23"
					fill="none"
					xmlns="http://www.w3.org/2000/svg"
				>
					<rect width="4" height="23" rx="1" fill="#fff" />
					<rect x="9" width="4" height="23" rx="1" fill="#fff" />
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

			<button
				type="button"
				style={{ display: `${shouldShowInitRadio ? 'initial' : 'none'}` }}
				onClick={initRadioMode}
			>
				<svg
					viewBox="0 0 20 20"
					className={`${status} radio-mode-btn`}
					xmlns="http://www.w3.org/2000/svg"
				>
					<path d="M10,2.172c-4.324,0-7.828,3.504-7.828,7.828S5.676,17.828,10,17.828c4.324,0,7.828-3.504,7.828-7.828S14.324,2.172,10,2.172M10,17.004c-3.863,0-7.004-3.141-7.004-7.003S6.137,2.997,10,2.997c3.862,0,7.004,3.141,7.004,7.004S13.862,17.004,10,17.004M10,8.559c-0.795,0-1.442,0.646-1.442,1.442S9.205,11.443,10,11.443s1.441-0.647,1.441-1.443S10.795,8.559,10,8.559 M10,10.619c-0.34,0-0.618-0.278-0.618-0.618S9.66,9.382,10,9.382S10.618,9.661,10.618,10S10.34,10.619,10,10.619 M14.12,8.559c-0.795,0-1.442,0.646-1.442,1.442s0.647,1.443,1.442,1.443s1.442-0.647,1.442-1.443S14.915,8.559,14.12,8.559 M14.12,10.619c-0.34,0-0.618-0.278-0.618-0.618s0.278-0.618,0.618-0.618S14.738,9.661,14.738,10S14.46,10.619,14.12,10.619 M5.88,8.559c-0.795,0-1.442,0.646-1.442,1.442s0.646,1.443,1.442,1.443S7.322,10.796,7.322,10S6.675,8.559,5.88,8.559 M5.88,10.619c-0.34,0-0.618-0.278-0.618-0.618S5.54,9.382,5.88,9.382S6.498,9.661,6.498,10S6.22,10.619,5.88,10.619" />
				</svg>
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
	initRadioMode: PropTypes.func,
	colors: PropTypes.shape({}),
	isIos: PropTypes.bool,
	progressClass: PropTypes.string,
};

Controls.defaultProps = {
	title: '',
	play: () => {},
	pause: () => {},
	resume: () => {},
	initRadioMode: () => {},
	colors: {},
	isIos: false,
	progressClass: '',
};

export default Controls;
