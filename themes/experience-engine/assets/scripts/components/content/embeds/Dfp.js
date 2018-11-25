import { PureComponent } from 'react';
import PropTypes from 'prop-types';

class Dfp extends PureComponent {

	componentDidMount() {
		const self = this;
		const { placeholder, network, unitId, unitName, targeting } = self.props;
		const { googletag } = window;

		googletag.cmd.push( () => {
			const size = window.bbgiconfig.dfp.sizes[unitName];
			const slot = googletag.defineSlot( `/${network}/${unitId}`, size, placeholder );

			let sizeMapping = false;
			if ( 'dfp_ad_leaderboard_pos1' === unitName || 'dfp_ad_inlist_infinite' === unitName ) {
				sizeMapping = googletag.sizeMapping()
					.addSize( [970, 200], ['fluid', [970, 250], [970, 90], [728, 90]] )
					.addSize( [729, 200], ['fluid', [728, 90]] )
					.addSize( [0, 0], ['fluid', [320, 100], [320, 50]] )
					.build();
			} else if ( 'dfp_ad_leaderboard_pos2' === unitName ) {
				sizeMapping = googletag.sizeMapping()
					.addSize( [970, 200], [[970, 250], [970, 90], [728, 90]] )
					.addSize( [729, 200], [728, 90] )
					.addSize( [0, 0], [[320, 100], [320, 50]] )
					.build();
			}

			if ( sizeMapping ) {
				slot.defineSizeMapping( sizeMapping );
			}

			for ( let i = 0; i < targeting.length; i++ ) {
				slot.setTargeting( targeting[i][0], targeting[i][1] );
			}

			slot.addService( googletag.pubads() );
			googletag.display( slot );

			self.slot = slot;
		} );
	}

	componentWillUnmount() {
		const { slot } = this;
		if ( slot ) {
			const { googletag } = window;
			googletag.destroySlots( [slot] );
		}
	}

	render() {
		return false;
	}

}

Dfp.propTypes = {
	placeholder: PropTypes.string.isRequired,
	network: PropTypes.string.isRequired,
	unitId: PropTypes.string.isRequired,
	unitName: PropTypes.string.isRequired,
	targeting: PropTypes.arrayOf( PropTypes.array ),
};

Dfp.defaultProps = {
	targeting: [],
};

export default Dfp;
