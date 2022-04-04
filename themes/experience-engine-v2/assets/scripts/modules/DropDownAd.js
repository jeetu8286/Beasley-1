import React, { useRef } from 'react';
import ReactDOM from 'react-dom';
import Dfp from '../components/content/embeds/Dfp';
import ErrorBoundary from '../components/ErrorBoundary';

const DropDownAd = () => {
	console.log('FIRED DropDownAd');

	const dropDropDownAdRef = useRef(null);
	const container = document.getElementById('drop-down-container');
	const [pageURL] = document.location.href;
	// this id is also compared in /assets/scripts/components/content/embeds/Dfp.js
	const id = 'div-drop-down-slot';

	// const { unitId, unitName } = window.bbgiconfig.dfp.headerad;
	const unitId = '/26918149/TEST_RedZoneBanner';
	const unitName = 'drop-down';

	window.refreshDropdownAd = () => {
		if (dropDropDownAdRef.current) {
			dropDropDownAdRef.current.refreshSlot();
		}
	};

	const children = (
		<ErrorBoundary>
			<Dfp
				key={`drop-down-ad-${pageURL}`}
				ref={dropDropDownAdRef}
				placeholder={id}
				unitId={unitId}
				unitName={unitName}
				shouldMapSizes={false}
				pageURL={pageURL}
			/>
		</ErrorBoundary>
	);

	return ReactDOM.createPortal(children, container);
};

export default DropDownAd;
