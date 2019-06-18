import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

import Dfp from './Dfp';

const SECOND_IN_MILLISECONDS = 1000;
const MINUTE_IN_MILLISECONDS = SECOND_IN_MILLISECONDS * 60;
const HOUR_IN_MILLISECONDS = MINUTE_IN_MILLISECONDS * 60;
const DAY_IN_MILLISECONDS = HOUR_IN_MILLISECONDS * 24;

class Countdown extends Component {

	constructor( props ) {
		super( props );

		const self = this;

		self.state = self.getTimeRemaining( false );
		self.interval = null;

		self.getTimeRemaining = self.getTimeRemaining.bind( self );
		self.buildTicker      = self.buildTicker.bind( self );
	}

	componentDidMount() {
		const self = this;
		const { payload } = self.props;
		if ( payload && payload.countdownTime ) {
			self.interval = setInterval( self.getTimeRemaining, 1000 );
		}
	}

	componentWillUnmount() {
		this.stopInterval();
	}

	stopInterval() {
		clearInterval( this.interval );
	}

	getTimeRemaining( updateState = true ) {
		const self = this;

		const total = Date.parse( self.props.payload.countdownTime ) - Date.parse( new Date() );
		if ( 0 >= total ) {
			self.stopInterval();
		}

		const seconds = Math.floor( total / SECOND_IN_MILLISECONDS ) % 60;
		const minutes = Math.floor( total / MINUTE_IN_MILLISECONDS ) % 60;
		const hours = Math.floor( total / HOUR_IN_MILLISECONDS ) % 24;
		const days = Math.floor( total / DAY_IN_MILLISECONDS );

		const remaining = {
			days: 0 < days ? days : 0,
			hours: 0 < hours ? hours : 0,
			minutes: 0 < minutes ? minutes : 0,
			seconds: 0 < seconds ? seconds : 0,
		};

		if ( updateState ) {
			self.setState( remaining );
		}

		return remaining;
	}

	getSponsor() {
		const { unitName } = window.bbgiconfig.dfp.countdown;
		let unitId = null;

		if ( this.props && this.props.payload && this.props.payload.sponsorshipAdunit ) {
			unitId = this.props.payload.sponsorshipAdunit;
		}

		const { placeholder } = this.props;

		const params = {
			id: `${placeholder}-adunit`,
			className: 'countdown-sponsor',
		};

		// we use createElement to make sure we don't add empty spaces here, thus DFP can properly collapse it when nothing to show here
		return React.createElement( 'div', params, [
			<Dfp key="sponsor" placeholder={params.id} unitId={unitId} unitName={unitName} />,
		] );
	}

	/**
	 * Separates number and returns ticker markup
	 */
	buildTicker( string ) {
		if ( !string ) {
			return;
		}

		let newString = ( '0' + string ).slice( -2 );

		return (
			<Fragment>
				<div className="tick">
					<div className="up">
						<div className="shadow"></div>
						<div className="inn">{newString[0]}</div>
					</div>
					<div className="down">
						<div className="shadow"></div>
						<div className="inn">{newString[0]}</div>
					</div>
				</div>
				<div className="tick">
					<div className="up">
						<div className="shadow"></div>
						<div className="inn">{newString[1]}</div>
					</div>
					<div className="down">
						<div className="shadow"></div>
						<div className="inn">{newString[1]}</div>
					</div>
				</div>
			</Fragment>
		);
	}

	render() {
		const self = this;
		const { payload } = self.props;

		if ( !payload ) {
			return false;
		}

		const {
			title,
			background,
			link,
			timeColor
		} = payload;

		const { color, image } = background;
		const blockStyle = {};
		const timeStyle = {};

		if ( color ) {
			blockStyle.backgroundColor = color;
		}

		if ( image ) {
			blockStyle.backgroundImage = `url(${image})`;
		}

		if ( timeColor ) {
			timeStyle.color = timeColor;
		}

		const titleText = link
			? <a href={link}>{title}</a>
			: title;

		const { days, hours, minutes, seconds } = self.state;

		return (
			<div className="countdown" style={blockStyle}>
				<div className="countdown-content">
					<div className="countdown-wrapper">
						<h2 className="countdown-title">
							{titleText}
						</h2>

						<div className="countdown-labels" style={timeStyle}>
							<span id="countdown-label-day" className="countdown-labels day">Days</span>
							<span id="countdown-label-hour" className="countdown-labels hour">Hours</span>
							<span id="countdown-label-minute" className="countdown-labels minute">Minutes</span>
							<span id="countdown-label-second" className="countdown-labels second">Seconds</span>
						</div>

						<div className="countdown-timer">
							<div className="time" title="Days">
								{this.buildTicker( days )}
							</div>
							<div className="time" title="Hours">
								{this.buildTicker( hours )}
							</div>
							<div className="time" title="Minutes">
								{this.buildTicker( minutes )}
							</div>
							<div className="time" title="Seconds">
								{this.buildTicker( seconds )}
							</div>
						</div>
					</div>

					{self.getSponsor()}
				</div>
			</div>
		);
	}

}

Countdown.propTypes = {
	placeholder: PropTypes.string.isRequired,
	payload: PropTypes.oneOfType( [PropTypes.bool, PropTypes.object] ),
};

Countdown.defaultProps = {
	payload: false,
};

export default Countdown;
