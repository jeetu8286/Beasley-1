import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { showEditFeedModal } from '../../../redux/actions/modal';
//import Notification from '../../Notification';

//function EditFeed( { loggedIn, showModal, feed, title, feeds, className } ) {
/** For some reason feeds are not being initializaed, leaving for context */
//const hasFeed = feeds.find( item => item.id === feed );
//if ( !hasFeed ) {
//	return false;
//}
//}

class EditFeed extends PureComponent {
	constructor() {
		super();
		const self = this;

		self.state = {
			showNotification: true
		};

		self.handleRemove = self.handleRemove.bind( self );
	}

	handleRemove() {

	}

	render() {
		const { loggedIn, className } = this.props;

		return loggedIn
			? (
				<button className={className} aria-label='Edit Feed' onClick={this.handleRemove}>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982" aria-labelledby="close-modal-title close-modal-desc" width="13" height="13">
						<title id="close-modal-title">Remove</title>
						<path
							d="M131.804 106.491l75.936-75.936c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.491 81.18 30.554 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.989 6.99-6.989 18.323 0 25.312l75.937 75.936-75.937 75.937c-6.989 6.99-6.989 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0l75.937-75.937 75.937 75.937c6.989 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.322 0-25.312l-75.936-75.936z"
							fillRule="evenodd"
							clipRule="evenodd"
						/>
					</svg>
				</button>
			)
			//? <button className={className} onClick={() => showModal( { feed, title } )} aria-label={`Edit ${title} Feed`}>Edit</button>
			: false;
	}
}

EditFeed.propTypes = {
	loggedIn: PropTypes.bool.isRequired,
	feed: PropTypes.string.isRequired,
	feeds: PropTypes.arrayOf( PropTypes.object ).isRequired,
	title: PropTypes.string,
	className: PropTypes.string,
	showModal: PropTypes.func.isRequired,
};

EditFeed.defaultProps = {
	title: '',
	className: '',
};

function mapStateToProps( { auth } ) {
	return {
		loggedIn: !!auth.user,
		feeds: auth.feeds,
	};
}

function mapDispatchToProps( dispatch ) {
	return bindActionCreators( {
		showModal: showEditFeedModal,
	}, dispatch );
}

export default connect( mapStateToProps, mapDispatchToProps )( EditFeed );
