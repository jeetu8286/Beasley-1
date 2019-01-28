import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { showEditFeedModal } from '../../../redux/actions/modal';

function EditFeed( { showModal, feed, title } ) {
	return (
		<button onClick={() => showModal( { feed, title } )}>Edit Feed</button>
	);
}

EditFeed.propTypes = {
	feed: PropTypes.string.isRequired,
	title: PropTypes.string,
	showModal: PropTypes.func.isRequired,
};

EditFeed.defaultProps = {
	title: '',
};

function mapDispatchToProps( dispatch ) {
	return bindActionCreators( {
		showModal: showEditFeedModal,
	}, dispatch );
}

export default connect( null, mapDispatchToProps )( EditFeed );
