import {
	ACTION_REFRESH_DROPDOWN_AD,
	ACTION_DROPDOWN_AD_REFRESHED,
} from '../actions/dropdownad';

export const DEFAULT_STATE = {
	shouldRefreshDropdownAd: false,
};

function reducer(state = {}, action = {}) {
	switch (action.type) {
		case ACTION_REFRESH_DROPDOWN_AD:
			return { ...state, shouldRefreshDropdownAd: true };
		case ACTION_DROPDOWN_AD_REFRESHED:
			return { ...state, shouldRefreshDropdownAd: false };
		default:
			// do nothing
			break;
	}

	return state;
}

export default reducer;
