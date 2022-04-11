export const ACTION_REFRESH_DROPDOWN_AD = 'REFRESH_DROPDOWN_AD';
export const ACTION_DROPDOWN_AD_REFRESHED = 'ACTION_DROPDOWN_AD_REFRESHED';

export function refreshDropdownAd() {
	return { type: ACTION_REFRESH_DROPDOWN_AD };
}

export function dropdownAdRefreshed() {
	return { type: ACTION_DROPDOWN_AD_REFRESHED };
}

export default {
	refreshDropdownAd,
	dropdownAdRefreshed,
};
