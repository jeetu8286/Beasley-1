import React, { Component, Fragment } from 'react';
import PropTypes from 'prop-types';

class CountdownTicker extends Component {
	render() {
		const { number } = this.props;

		const newNumber = ( '0' + number ).slice( -2 );

		return (
			<Fragment>
				<div className="tick">
					<div className="up">
						<div className="shadow"></div>
						<div className="inn">{newNumber[0]}</div>
					</div>
					<div className="down">
						<div className="shadow"></div>
						<div className="inn">{newNumber[0]}</div>
					</div>
				</div>
				<div className="tick">
					<div className="up">
						<div className="shadow"></div>
						<div className="inn" onChange={this.handleNumberChange}>{newNumber[1]}</div>
					</div>
					<div className="down">
						<div className="shadow"></div>
						<div className="inn">{newNumber[1]}</div>
					</div>
				</div>
			</Fragment>
		);
	}
}

CountdownTicker.propTypes = {
	number: PropTypes.number
};

export default CountdownTicker;
