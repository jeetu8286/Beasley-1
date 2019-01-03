import React, { PureComponent, Fragment } from 'react';
import PropTypes from 'prop-types';

import Header from './elements/Header';
import Alert from './elements/Alert';
import trapHOC from '@10up/react-focus-trap-hoc';
import DiscoveryFilters from '../../modules/DiscoveryFilters';

class Discover extends PureComponent {
	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			filters: '',
			error: '',
			scrollYPos: '',
		};
	}

	componentDidMount() {
		const self = this;
		const scrollYPos = window.pageYOffset;
		this.props.activateTrap();

		self.setState( {
			scrollYPos: scrollYPos,
		} );

		window.scroll( 0, 0 );
	}

	componentWillUnmount() {
		const self = this;
		const { scrollYPos } = self.state;
		this.props.deactivateTrap();

		window.scroll( 0, scrollYPos );
	}

	render() {
		const self = this;
		const { error } = self.state;
		return (
			<Fragment>
				<DiscoveryFilters />
				<Header>
					<h2>Discover</h2>
				</Header>
				<Alert message={error} />
			</Fragment>
		);
	}
}

Discover.propTypes = {
	activateTrap: PropTypes.func.isRequired,
	deactivateTrap: PropTypes.func.isRequired,
};

export default trapHOC()( Discover );
