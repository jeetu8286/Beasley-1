import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

class GamPreroll extends PureComponent {
	render() {
		// backward compatibility with the legacy theme to make sure that everything keeps working correctly
		// this id is also compared in /assets/scripts/components/content/embeds/Dfp.js
		const { adUnitId } = this.props;

		// we use createElement to make sure we don't add empty spaces here, thus DFP can properly collapse it when nothing to show here
		return React.createElement(
			'div',
			{ id: 'gam_preroll_background_div', className: 'preroll-player' },
			[<div id={adUnitId}>[This Is Ad]</div>],
		);
	}
}

GamPreroll.propTypes = {
	adUnitId: PropTypes.string.isRequired,
};

GamPreroll.defaultProps = {};

export default GamPreroll;
