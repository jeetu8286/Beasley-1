import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

class Controls extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;
		self.playing = false;

		self.onPlay = self.handlePlay.bind( self );
		self.onPause = self.handlePause.bind( self );
		self.onResume = self.handleResume.bind( self );
	}

	handlePlay() {
		const self = this;
		if ( !self.playing ) {
			self.playing = true;
			self.props.play();
		}
	}

	handlePause() {
		const self = this;
		if ( self.playing ) {
			self.playing = false;
			self.props.pause();
		}
	}

	handleResume() {
		const self = this;
		if ( !self.playing ) {
			self.playing = true;
			self.props.resume();
		}
	}

	render() {
		const self = this;

		return (
			<div className={`status ${self.props.status}`}>
				<button type="button" className="play-btn" onClick={self.onPlay}>
					<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
						<path d="M16.1836 12.0055L0.910156 23.124L0.910156 0.887031L16.1836 12.0055Z" />
					</svg>
				</button>

				<button type="button" className="pause-btn" onClick={self.onPause}>
					<svg viewBox="0 0 28 24" xmlns="http://www.w3.org/2000/svg" fillRule="evenodd" clipRule="evenodd">
						<path d="M11 22h-4v-20h4v20zm6-20h-4v20h4v-20z" />
					</svg>
				</button>

				<button type="button" className="resume-btn" onClick={self.onResume}>
					<svg viewBox="0 0 17 24" xmlns="http://www.w3.org/2000/svg">
						<path d="M16.1836 12.0055L0.910156 23.124L0.910156 0.887031L16.1836 12.0055Z" />
					</svg>
				</button>

				<button type="button" className="loading-btn">
					<div className="loading" />
				</button>
			</div>
		);
	}

}

Controls.propTypes = {
	status: PropTypes.string.isRequired,
	play: PropTypes.func,
	pause: PropTypes.func,
	resume: PropTypes.func,
};

Controls.defaultProps = {
	play: () => { },
	pause: () => { },
	resume: () => { },
};

export default Controls;
