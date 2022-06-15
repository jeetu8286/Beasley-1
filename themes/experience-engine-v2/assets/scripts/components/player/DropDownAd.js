import React, { useRef } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import Dfp from '../content/embeds/Dfp';
import ErrorBoundary from '../ErrorBoundary';
import {
	dropdownAdHidden,
	dropdownAdRefreshed,
} from '../../redux/actions/dropdownad';

const DropDownAd = () => {
	const dispatch = useDispatch();
	const shouldRefresh = useSelector(
		state => state.dropdownad.shouldRefreshDropdownAd,
	);
	const shouldHide = useSelector(
		state => state.dropdownad.shouldHideDropdownAd,
	);
	const initialAdWasShown = useSelector(
		state => state.dropdownad.initialDropdownAdWasShown,
	);

	const dropDropDownAdRef = useRef(null);

	const isListenLiveShowing = useSelector(
		state => state.screen.isListenLiveShowing,
	);
	if (!isListenLiveShowing) {
		console.log('NOT SHOWING DD AD');
		return false;
	}

	console.log('SHOWING DD AD');
	const [pageURL] = document.location.href;
	// this id is also compared in /assets/scripts/components/content/embeds/Dfp.js
	const id = 'div-drop-down-slot';

	const { unitId, unitName } = window.bbgiconfig.dfp.dropdown;

	if (shouldRefresh && dropDropDownAdRef.current) {
		if (initialAdWasShown) {
			dropDropDownAdRef.current.refreshSlot();
		} else {
			dropDropDownAdRef.current.showSlot();
		}
		dispatch(dropdownAdRefreshed());
	}

	if (shouldHide && dropDropDownAdRef.current) {
		dropDropDownAdRef.current.hideSlot();
		dispatch(dropdownAdHidden());
	}

	return (
		<ErrorBoundary>
			<div>
				<div
					id="drop-down-container"
					className="drop-down-container -ad -centered"
				>
					<Dfp
						key={`drop-down-ad-${pageURL}`}
						ref={dropDropDownAdRef}
						placeholder={id}
						unitId={unitId}
						unitName={unitName}
						shouldMapSizes={false}
						pageURL={pageURL}
					/>
				</div>
			</div>
		</ErrorBoundary>
	);
};

export default DropDownAd;
