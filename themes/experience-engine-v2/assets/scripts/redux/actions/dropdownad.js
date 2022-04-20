export const ACTION_DROPDOWN_AD_REFRESH = 'ACTION_DROPDOWN_AD_REFRESH';
export const ACTION_DROPDOWN_AD_REFRESHED = 'ACTION_DROPDOWN_AD_REFRESHED';

export function refreshDropdownAd() {
	return { type: ACTION_DROPDOWN_AD_REFRESH };
}

export function dropdownAdRefreshed() {
	return { type: ACTION_DROPDOWN_AD_REFRESHED };
}

export default {
	refreshDropdownAd,
	dropdownAdRefreshed,
};
