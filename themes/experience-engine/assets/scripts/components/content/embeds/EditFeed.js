import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { modifyUserFeeds, deleteUserFeed } from '../../../redux/actions/auth';

class EditFeed extends Component {

	constructor( props ) {
		super( props );

		const self = this;
		self.move = self.move.bind( self );
		self.onRemove = self.handleRemove.bind( self );
		self.onMoveUp = self.handleMoveUp.bind( self );
		self.onMoveDown = self.handleMoveDown.bind( self );
	}

	handleMoveDown() {
		this.move( 'down' );
	}

	handleMoveUp() {
		this.move( 'up' );
	}

	move( direction ) {
		console.log( `move ${direction}` );
	}

	handleRemove() {
		const { feed, deleteFeed } = this.props;

		deleteFeed( feed );

		const container = document.getElementById( feed );
		if ( container ) {
			container.classList.add( '-hidden' );
		}
	}

	render() {
		const self = this;
		const { loggedIn, className } = self.props;

		if ( ! loggedIn ) {
			return false;
		}

		return (
			<div className="edit-feed-controls">
				<button className={className} aria-label="Edit Feed" onClick={self.onMoveUp}>
					<svg width="14" height="9" aria-labelledby="move-down-modal-title move-down-modal-desc"  xmlns="http://www.w3.org/2000/svg">
						<title id="move-down-modal-title">Move Down</title>
						<path d="M12.88 2.275L7.276 7.88a.38.38 0 0 1-.552 0L1.12 2.275a.38.38 0 0 1 0-.554l.601-.6a.38.38 0 0 1 .554 0L7 5.846l4.726-4.727a.38.38 0 0 1 .553 0l.601.601a.38.38 0 0 1 0 .554z" fill="currentColor" stroke="currentColor" strokeWidth=".5"/>
					</svg>
				</button>

				<button className={className} aria-label="Edit Feed" onClick={self.onMoveUp}>
					<svg width="14" height="9" aria-labelledby="move-up-modal-title move-up-modal-desc"  xmlns="http://www.w3.org/2000/svg">
						<title id="move-up-modal-title">Move Up</title>
						<path d="M1.12 6.725L6.724 1.12a.38.38 0 0 1 .552 0l5.604 5.605a.38.38 0 0 1 0 .554l-.601.6a.38.38 0 0 1-.553 0L7 3.154 2.274 7.88a.38.38 0 0 1-.553 0l-.601-.601a.38.38 0 0 1 0-.554z" fill="currentColor" stroke="currentColor" strokeWidth=".5"/>
					</svg>
				</button>

				<button className={className} aria-label="Edit Feed" onClick={self.onRemove}>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 212.982 212.982" aria-labelledby="close-modal-title close-modal-desc" width="13" height="13">
						<title id="close-modal-title">Remove</title>
						<path d="M131.804 106.491l75.936-75.936c6.99-6.99 6.99-18.323 0-25.312-6.99-6.99-18.322-6.99-25.312 0L106.491 81.18 30.554 5.242c-6.99-6.99-18.322-6.99-25.312 0-6.989 6.99-6.989 18.323 0 25.312l75.937 75.936-75.937 75.937c-6.989 6.99-6.989 18.323 0 25.312 6.99 6.99 18.322 6.99 25.312 0l75.937-75.937 75.937 75.937c6.989 6.99 18.322 6.99 25.312 0 6.99-6.99 6.99-18.322 0-25.312l-75.936-75.936z" fillRule="evenodd" clipRule="evenodd" />
					</svg>
				</button>
			</div>
		);
	}
}

EditFeed.propTypes = {
	loggedIn: PropTypes.bool.isRequired,
	feed: PropTypes.string.isRequired,
	feeds: PropTypes.arrayOf( PropTypes.object ).isRequired,
	title: PropTypes.string,
	className: PropTypes.string,
	modifyFeeds: PropTypes.func.isRequired,
	deleteFeed: PropTypes.func.isRequired,
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
		modifyFeeds: modifyUserFeeds,
		deleteFeed: deleteUserFeed,
	}, dispatch );
}

export default connect( mapStateToProps, mapDispatchToProps )( EditFeed );
