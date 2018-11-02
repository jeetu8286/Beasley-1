import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

class LoadMore extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;
		self.onLoadClick = self.handleLoadClick.bind( self );
	}

	handleLoadClick() {
		const { link, placeholder } = this.props;
		console.log( link, placeholder );
	}

	render() {
		const self = this;

		return (
			<button className="load-more" onClick={self.onLoadClick}>
				Load More
			</button>
		);
	}

}

LoadMore.propTypes = {
	placeholder: PropTypes.string.isRequired,
	link: PropTypes.string.isRequired,
};

const mapDispatchToProps = ( dispatch ) => bindActionCreators( {}, dispatch );

export default connect( null, mapDispatchToProps )( LoadMore );
