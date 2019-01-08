import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import trapHOC from '@10up/react-focus-trap-hoc';

import Header from './elements/Header';
import Alert from './elements/Alert';
import CloseButton from './elements/Close';

import DiscoveryFilters from '../../modules/DiscoveryFilters';

class Discover extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.scrollYPos = 0;
		self.state = {
			filters: '',
			error: '',
		};
	}

	componentDidMount() {
		const self = this;

		self.props.activateTrap();
		self.scrollYPos = window.pageYOffset;

		window.scroll( 0, 0 );
	}

	componentWillUnmount() {
		const self = this;

		self.props.deactivateTrap();

		window.scroll( 0, self.scrollYPos );
	}

	render() {
		const self = this;
		const { error } = self.state;
		const { close } = self.props;

		return (
			<div className="discover-modal">
				<CloseButton close={close} />
				<DiscoveryFilters />

				<Header>
					<h2>Discover</h2>
				</Header>

				<Alert message={error} />
			</div>
		);
	}

}

Discover.propTypes = {
	activateTrap: PropTypes.func.isRequired,
	deactivateTrap: PropTypes.func.isRequired,
	close: PropTypes.func.isRequired,
};

export default trapHOC()( Discover );
