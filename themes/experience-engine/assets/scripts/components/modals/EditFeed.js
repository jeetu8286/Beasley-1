import React, { PureComponent, Fragment } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import trapHOC from '@10up/react-focus-trap-hoc';

import Header from './elements/Header';

import { deleteUserFeed } from '../../redux/actions/auth';

class EditFeed extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.onMoveToTopClick = self.handleMoveToTopClick.bind( self );
		self.onMoveUpClick = self.handleMoveUpClick.bind( self );
		self.onDeleteClick = self.handleDeleteClick.bind( self );
		self.onMoveDownClick = self.handleMoveDownClick.bind( self );
		self.onMoveToBottomClick = self.handleMoveToBottomClick.bind( self );
	}

	componentDidMount() {
		this.props.activateTrap();
	}

	componentWillUnmount() {
		this.props.deactivateTrap();
	}

	handleMoveToTopClick() {
		console.log( 'move-to-top' );
	}

	handleMoveUpClick() {
		console.log( 'move-up' );
	}

	handleDeleteClick() {
		const self = this;
		const { close, deleteFeed, feed } = self.props;

		deleteFeed( feed );

		const container = document.getElementById( `${feed}-feed` );
		if ( container ) {
			container.classList.add( '-hidden' );
		}

		close();
	}

	handleMoveDownClick() {
		console.log( 'move-down' );
	}

	handleMoveToBottomClick() {
		console.log( 'move-to-bottom' );
	}

	render() {
		const self = this;
		const { title, feed } = self.props;

		return (
			<Fragment>
				<Header>{title || feed || 'Feed'}</Header>

				<div>
					<button onClick={self.onMoveToTopClick}>Move To Top</button>
				</div>
				<div>
					<button onClick={self.onMoveUpClick}>Move Up</button>
				</div>
				<div>
					<button onClick={self.onMoveDownClick}>Move Down</button>
				</div>
				<div>
					<button onClick={self.onMoveToBottomClick}>Move To Bottom</button>
				</div>

				<hr />

				<div>
					<button onClick={self.onDeleteClick}>Delete</button>
				</div>
			</Fragment>
		);
	}

}

EditFeed.propTypes = {
	feed: PropTypes.string.isRequired,
	title: PropTypes.string,
	close: PropTypes.func.isRequired,
	activateTrap: PropTypes.func.isRequired,
	deactivateTrap: PropTypes.func.isRequired,
	deleteFeed: PropTypes.func.isRequired,
};

EditFeed.defaultProps = {
	title: '',
};

function mapStateToProps() {
	return {};
}

function mapDispatchToProps( dispatch ) {
	return bindActionCreators( {
		deleteFeed: deleteUserFeed,
	}, dispatch );
}

export default connect( mapStateToProps, mapDispatchToProps )( trapHOC()( EditFeed ) );
