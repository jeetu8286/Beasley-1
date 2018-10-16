import React from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

const SecondStreet = ( { placeholder, script, embed, opguid, routing } ) => ReactDOM.createPortal(
	<script src={script} data-ss-embed={embed} data-opguid={opguid} data-routing={routing} />,
	document.getElementById( placeholder )
);

SecondStreet.propTypes = {
	placeholder: PropTypes.string.isRequired,
	script: PropTypes.string,
	embed: PropTypes.string,
	opguid: PropTypes.string,
	routing: PropTypes.string,
};

SecondStreet.defaultProps = {
	script: '',
	embed: '',
	opguid: '',
	routing: '',
};

export default SecondStreet;
