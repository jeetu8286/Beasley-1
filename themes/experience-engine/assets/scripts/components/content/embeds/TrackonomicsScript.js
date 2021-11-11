import { PureComponent } from 'react';
import PropTypes from 'prop-types';

class TrackonomicsScript extends PureComponent {
	componentDidMount() {
		const { postid, posttype, trackonomicsscript } = this.props;
		console.log('Post ID: ', postid);
		console.log('Current Post type: ', posttype);
		console.log('Show Trackonomics Script: ', trackonomicsscript);
		/* 
		call script in react	
		https://stackoverflow.com/questions/34424845/adding-script-tag-to-react-jsx
		if (posttype === '1') {
			<script id="funnel-relay-installer" data-property-id="PROPERTY_ID" data-customer-id="bbgi_39ea5_bbgi" src="https://cdn-magiclinks.trackonomics.net/client/static/v2/bbgi_39ea5_bbgi.js" async="async"></script>
		} */
		/* if (posttype === '0') {
			document.documentElement.style.setProperty(
				'--configurable-iframe-height',
				'0px',
			);
		} else {
			document.documentElement.style.setProperty(
				'--configurable-iframe-height',
				document.documentElement.style.getPropertyValue(
					'--default-configurable-iframe-height',
				),
			);
		} */
	}

	componentWillUnmount() {
		/* document.documentElement.style.setProperty(
			'--configurable-iframe-height',
			document.documentElement.style.getPropertyValue(
				'--default-configurable-iframe-height',
			),
		); */
	}

	render() {
		return false;
	}
}

TrackonomicsScript.propTypes = {
	postid: PropTypes.string,
	posttype: PropTypes.string,
	trackonomicsscript: PropTypes.string,
};

TrackonomicsScript.defaultProps = {
	postid: '',
	posttype: '',
	trackonomicsscript: '',
};

export default TrackonomicsScript;
