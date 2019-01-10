import React, { PureComponent, Fragment } from 'react';
import PropTypes from 'prop-types';
import trapHOC from '@10up/react-focus-trap-hoc';

import Header from './elements/Header';
import Alert from './elements/Alert';
import CloseButton from './elements/Close';

import FeedItem from './discovery/Feed';
import DiscoveryFilters from './discovery/Filters';

import { discovery, getFeeds, modifyFeeds } from '../../library/experience-engine';

class Discover extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.scrollYPos = 0;
		self.feeds = new Set();

		self.state = {
			loading: true,
			error: '',
			feeds: [],
		};

		self.onFilterChange = self.handleFilterChange.bind( self );
		self.onAdd = self.handleAdd.bind( self );
	}

	componentDidMount() {
		const self = this;

		self.props.activateTrap();
		self.handleFilterChange();

		getFeeds().then( ( feeds ) => {
			feeds.forEach( feed => self.feeds.add( feed.id ) );
		} );

		self.scrollYPos = window.pageYOffset;
		window.scroll( 0, 0 );
	}

	componentWillUnmount() {
		const self = this;

		self.props.deactivateTrap();
		window.scroll( 0, self.scrollYPos );
	}

	handleFilterChange( filters = {} ) {
		discovery( filters )
			.then( response => response.json() )
			.then( feeds => this.setState( { feeds, loading: false } ) );
	}

	handleAdd( id ) {
		const self = this;

		if ( self.feeds.has( id ) ) {
			return;
		}

		self.feeds.add( id );

		const feedsArray = [];
		self.feeds.forEach( ( feed ) => {
			feedsArray.push( {
				id: feed,
				sortorder: feedsArray.length + 1,
			} );
		} );

		modifyFeeds( feedsArray );
	}

	render() {
		const self = this;
		const { error, feeds, loading } = self.state;
		const { close } = self.props;

		let items = <div className="loading" />;
		if ( !loading ) {
			if ( 0 < feeds.length ) {
				items = feeds.map( item => (
					<FeedItem key={item.id} id={item.id} title={item.title} picture={item.picture} type={item.type} onAdd={self.onAdd}>
						{item.title}
					</FeedItem>
				) );
			} else {
				items = <i>No feeds found...</i>;
			}
		}

		return (
			<Fragment>
				<CloseButton close={close} />
				<DiscoveryFilters onChange={self.onFilterChange} />

				<div className="content-wrap">
					<Header>
						<h2>Discover</h2>
					</Header>

					<Alert message={error} />

					<div className="archive-tiles -small -grid">
						{items}
					</div>
				</div>
			</Fragment>
		);
	}

}

Discover.propTypes = {
	activateTrap: PropTypes.func.isRequired,
	deactivateTrap: PropTypes.func.isRequired,
	close: PropTypes.func.isRequired,
};

export default trapHOC()( Discover );
