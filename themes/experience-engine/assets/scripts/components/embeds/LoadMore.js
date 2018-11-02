import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';

import { loadPartialPage } from '../../redux/actions/screen';

class LoadMore extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;
		self.state = { loading: false };
		self.onLoadClick = self.handleLoadClick.bind( self );
	}

	handleLoadClick() {
		const self = this;
		const { loading } = self.state;
		const { link, placeholder, load } = self.props;

		// prevent double clicking
		if ( !loading ) {
			self.setState( { loading: true } );
			load( link, placeholder );
		}
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
	load: PropTypes.func.isRequired,
};

const mapDispatchToProps = ( dispatch ) => bindActionCreators( { load: loadPartialPage }, dispatch );

export default connect( null, mapDispatchToProps )( LoadMore );
