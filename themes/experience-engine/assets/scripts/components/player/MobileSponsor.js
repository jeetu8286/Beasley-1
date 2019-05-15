import React from 'react';
import Dfp from '../content/embeds/Dfp';

function MobileSponsor() {
	const { UnitId, UnitName } = window.bbgiconfig.dfp.player.mobile;

	const params = {
		className: 'sponsor-mobile',
	};

	// we use createElement to make sure we don't add empty spaces here, thus DFP can properly collapse it when nothing to show here
	return React.createElement( 'div', params, [
		<Dfp key="sponsor-mobile" unitId={UnitId} unitName={UnitName} />,
	] );
}

export default MobileSponsor;
