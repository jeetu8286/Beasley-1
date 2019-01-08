import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

class DiscoveryFilters extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			brand: '',
			location: '',
			genre: '',
			type: '',
		};

		self.onChange = self.handleChange.bind( self );
	}

	handleChange( e ) {
		const { target } = e;
		this.setState( { [target.name]: target.value } );
	}

	render() {
		const self = this;
		const { brand, location, genre, type } = self.state;

		const { bbgiconfig } = window;
		const { locations, genres, publishers } = bbgiconfig || {};

		/* eslint-disable jsx-a11y/no-onchange */
		return (
			<div className="filters">
				<div className="select">
					<select name="brand" value={brand} onChange={self.onChange}>
						<option value="">Brand</option>
						{Object.keys( publishers ).map( ( item, i ) => <option key={`brand-${i}`} value={item}>{publishers[item]}</option> )}
					</select>
				</div>
				<div className="select">
					<select name="location" value={location} onChange={self.onChange}>
						<option value="">Location</option>
						{locations.map( ( item, i ) => <option key={`location-${i}`}>{item}</option> )}
					</select>
				</div>
				<div className="select">
					<select name="type" value={type} onChange={self.onChange}>
						<option>Type</option>
						<option value="events">Events</option>
						<option value="podcast">Podcasts</option>
						<option value="news">News</option>
						<option value="video">Video</option>
						<option value="contests">Contests</option>
					</select>
				</div>
				<div className="select">
					<select name="genre" value={genre} onChange={self.onChange}>
						<option value="">Genre</option>
						{genres.map( ( item, i ) => <option key={`genre-${i}`}>{item}</option> )}
					</select>
				</div>
			</div>
		);
		/* eslint-enable */
	}
}

DiscoveryFilters.propTypes = {
	station: PropTypes.string.isRequired,
	streams: PropTypes.arrayOf( PropTypes.object ).isRequired,
};

function mapStateToProps( { player } ) {
	return {
		station: player.station,
		streams: player.streams,
	};
}

export default connect( mapStateToProps )( DiscoveryFilters );
