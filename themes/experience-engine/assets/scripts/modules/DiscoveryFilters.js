import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

class DiscoveryFilters extends PureComponent {
	render() {
		return (
			<div className="filters">
				<div className="select">
					<select name="brand" id="brand">
						<option value="Brand">Brand</option>
						<option value="Brand 1">Brand 1</option>
						<option value="Brand 2">Brand 2</option>
						<option value="Brand 3">Brand 3</option>
						<option value="Brand 4">Brand 4</option>
					</select>
				</div>
				<div className="select">
					<select name="location" id="location">
						<option value="Location">Location</option>
						<option value="Location 1">Location 1</option>
						<option value="Location 2">Location 2</option>
						<option value="Location 3">Location 3</option>
						<option value="Location 4">Location 4</option>
					</select>
				</div>
				<div className="select">
					<select name="type" id="type">
						<option value="Type">Type</option>
						<option value="Type 1">Type 1</option>
						<option value="Type 2">Type 2</option>
						<option value="Type 3">Type 3</option>
						<option value="Type 4">Type 4</option>
					</select>
				</div>
				<div className="select">
					<select name="genre" id="genre">
						<option value="Genre">Genre</option>
						<option value="Genre 1">Genre 1</option>
						<option value="Genre 2">Genre 2</option>
						<option value="Genre 3">Genre 3</option>
						<option value="Genre 4">Genre 4</option>
					</select>
				</div>
				{/* This should likely be imported somehow? */}
				<form
					role="search"
					method="get"
					className="search-form"
					action="<?php echo esc_url( home_url( '/' ) ); ?>"
				>
					<label htmlFor="search-q" id="q" className="screen-reader-text">
						Search for:
					</label>
					<input
						id="search-q"
						type="search"
						className="search-field"
						name="s"
						value=""
						placeholder="Search"
					/>
					<button
						type="submit"
						className="search-submit"
						aria-label="Submit search"
					>
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="15">
							<path
								d="M10.266 9.034h-.65l-.23-.222a5.338 5.338 0 0 0 1.216-4.385C10.216 2.144 8.312.32 6.012.042a5.342 5.342 0 0 0-5.97 5.97c.279 2.3 2.102 4.204 4.385 4.59a5.338 5.338 0 0 0 4.385-1.215l.222.23v.649l3.49 3.49c.337.336.887.336 1.224 0s.336-.887 0-1.224l-3.482-3.498zm-4.928 0c-2.044 0-3.695-1.65-3.695-3.696s1.65-3.695 3.695-3.695 3.696 1.65 3.696 3.695-1.65 3.696-3.696 3.696z"
								fill="currentcolor"
							/>
						</svg>
					</button>
				</form>
			</div>
		);
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
