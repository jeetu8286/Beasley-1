import React, { Component } from 'react';
import PropTypes from 'prop-types';

import LazyImage from '../../content/embeds/LazyImage';

class Feed extends Component {

	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			loading: false,
		};

		self.onAdd = self.handleAdd.bind( self );
	}

	handleAdd() {
		const self = this;
		const { id } = self.props;

		self.setState( { loading: true } );
	}

	render() {
		const self = this;
		const { loading } = self.state;
		const { id, title, picture, type } = self.props;

		const placholder = `${id}-thumbnail`;
		const image = ( picture.original || picture.large || {} ).url;
		const lazyImage = image
			? <LazyImage placeholder={placholder} src={image} width="300" height="300" alt={title} />
			: false;

		const button = loading
			? <div className="loading" />
			: <button onClick={self.onAdd}>Add Feed</button>;

		return (
			<div className={`${type} post-tile`}>
				<div className="post-thumbnail">
					<div id={placholder} className="placeholder placeholder-lazyimage">
						{lazyImage}
					</div>
				</div>

				<div className="post-title">
					<h3>{title}</h3>
				</div>

				{button}
			</div>
		);
	}

}

Feed.propTypes = {
	id: PropTypes.string.isRequired,
	title: PropTypes.string.isRequired,
	picture: PropTypes.shape( {} ),
	type: PropTypes.string.isRequired,
};

Feed.defaultProps = {
	picture: {},
};

export default Feed;
