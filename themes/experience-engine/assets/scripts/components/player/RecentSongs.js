import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

import { formatDate } from '../../library/time';

const RecentSongs = ( { songs } ) => {
	if ( !Array.isArray( songs ) || !songs.length ) {
		return false;
	}

	const items = songs.map( ( song ) => (
		<li key={song.cueTimeStart}>
			<span className="cue-point-title">{song.cueTitle}</span>
			<span className="cue-point-artist">{song.artistName}</span>
			{0 < song.cueTimeStart && <span>{formatDate( +song.cueTimeStart )}</span>}
		</li>
	) );

	return (
		<div>
			<div>Recent Songs:</div>
			<ul>
				{items}
			</ul>
		</div>
	);
};

RecentSongs.propTypes = {
	songs: PropTypes.arrayOf( PropTypes.object ).isRequired,
};

const mapStateToProps = ( { player } ) => ( { songs: player.songs } );

export default connect( mapStateToProps )( RecentSongs );