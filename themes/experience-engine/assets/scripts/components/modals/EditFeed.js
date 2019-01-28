import React, { PureComponent, Fragment } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import trapHOC from '@10up/react-focus-trap-hoc';

import Header from './elements/Header';

import { modifyUserFeeds, deleteUserFeed } from '../../redux/actions/auth';

class EditFeed extends PureComponent {

	static sortFeeds( a, b ) {
		if ( a.sortorder > b.sortorder ) {
			return 1;
		}

		if ( a.sortorder < b.sortorder ) {
			return -1;
		}

		return 0;
	}

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

	reorderFeeds( shift ) {
		const self = this;
		const { feed, feeds, modifyFeeds } = self.props;
		const newfeeds = [];

		for ( let i = 0, len = feeds.length; i < len; i++ ) {
			const item = feeds[i];
			newfeeds.push( {
				id: item.id,
				sortorder: i * 10 - ( item.id === feed ? shift : 0 ),
			} );
		}

		newfeeds.sort( EditFeed.sortFeeds );
		for ( let i = 0, len = newfeeds.length; i < len; i ++ ) {
			newfeeds[i].sortorder = i + 1;
		}

		modifyFeeds( newfeeds );
	}

	handleMoveToTopClick() {
		this.reorderFeeds( 1000000 );
	}

	handleMoveUpClick() {
		this.reorderFeeds( 15 );
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
		this.reorderFeeds( -15 );
	}

	handleMoveToBottomClick() {
		this.reorderFeeds( -1000000 );
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
	modifyFeeds: PropTypes.func.isRequired,
};

EditFeed.defaultProps = {
	title: '',
};

function mapStateToProps( { auth } ) {
	const { feeds } = auth;
	const items = [];
	for ( let i = 0, len = feeds.length; i < len; i++ ) {
		items.push( Object.assign( {}, feeds[i] ) );
	}

	items.sort( EditFeed.sortFeeds );

	return { feeds: items };
}

function mapDispatchToProps( dispatch ) {
	return bindActionCreators( {
		deleteFeed: deleteUserFeed,
		modifyFeeds: modifyUserFeeds,
	}, dispatch );
}

export default connect( mapStateToProps, mapDispatchToProps )( trapHOC()( EditFeed ) );
