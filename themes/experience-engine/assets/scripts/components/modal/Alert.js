import React from 'react';
import PropTypes from 'prop-types';

const Alert = ( { message } ) => {
	if ( !message || !message.length ) {
		return false;
	}

	return (
		<div>
			<b>{message}</b>
		</div>
	);
};

Alert.propTypes = {
	message: PropTypes.string,
};

Alert.defaultProps = {
	message: '',
};

export default Alert;
