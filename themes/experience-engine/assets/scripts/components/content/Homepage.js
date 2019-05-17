import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import HomepageOrderingContext from '../../context/homepage-ordering';
import { modifyUserFeeds, deleteUserFeed } from '../../redux/actions/auth';

class Homepage extends Component {

	static sortFeeds( a, b ) {
		if ( a.sortorder > b.sortorder ) {
			return 1;
		}

		if ( a.sortorder < b.sortorder ) {
			return -1;
		}

		return 0;
	}

	static getFeedsHash( feeds ) {
		const feedsHash = {};
		for ( let i = 0, len = feeds.length; i < len; i++ ) {
			feedsHash[feeds[i].id] = i + 1;
		}

		return feedsHash;
	}

	constructor( props ) {
		super( props );

		const self = this;
		self.childrenContext = {
			moveUp: self.reorderFeeds.bind( self, 15 ),
			moveDown: self.reorderFeeds.bind( self, -15 ),
		};
	}

	componentDidMount() {
		this.updateOrderNumbers();
	}

	updateOrderNumbers() {
		const feeds = this.shiftFeeds( 0, false );
		const feedsHash = Homepage.getFeedsHash( feeds );
		const container = document.getElementById( 'inner-content' );
		if ( container ) {
			for ( let i = 0, j = 0, index = 0; i < container.childNodes.length; i++ ) {
				const child = container.childNodes[i];
				if ( child && child.id ) {
					if ( feedsHash[child.id] ) {
						index = child.style.order = ( j + 1 ) * 10;
						j++;
					} else {
						child.style.order = index + 1;
					}
				}
			}
		}
	}

	shiftFeeds( shift, feed ) {
		const self = this;
		const { feeds } = self.props;

		const newfeeds = feeds.map( ( item, i ) => ( {
			id: item.id,
			sortorder: i * 10 - ( item.id === feed ? shift : 0 ),
		} ) );

		newfeeds.sort( Homepage.sortFeeds );
		for ( let i = 0, len = newfeeds.length; i < len; i++ ) {
			newfeeds[i].sortorder = i + 1;
		}

		return newfeeds;
	}

	reorderFeeds( shift, feed ) {
		const self = this;

		const feeds = self.shiftFeeds( shift, feed );
		self.props.modifyFeeds( feeds );

		const feedsHash = Homepage.getFeedsHash( feeds );
		const container = document.getElementById( 'inner-content' );
		if ( container ) {
			for ( let i = 0, keys = Object.keys( feedsHash ), len = keys.length; i < len; i++ ) {
				const element = document.getElementById( keys[i] );
				if ( element ) {
					element.style.order = ( feedsHash[keys[i]] + 1 ) * 10;
				}
			}
		}
	}

	render() {
		return (
			<HomepageOrderingContext.Provider value={self.childrenContext}>
				{this.props.children}
			</HomepageOrderingContext.Provider>
		);
	}

}

Homepage.propTypes = {
	children: PropTypes.node.isRequired,
	feeds: PropTypes.arrayOf( PropTypes.object ).isRequired,
	deleteFeed: PropTypes.func.isRequired,
	modifyFeeds: PropTypes.func.isRequired,
};

function mapStateToProps( { auth } ) {
	const feeds = auth.feeds.map( item => Object.assign( {}, item ) );
	feeds.sort( Homepage.sortFeeds );

	return {
		feeds,
	};
}

function mapDispatchToProps( dispatch ) {
	return bindActionCreators( {
		deleteFeed: deleteUserFeed,
		modifyFeeds: modifyUserFeeds,
	}, dispatch );
}

export default connect( mapStateToProps, mapDispatchToProps )( Homepage );
