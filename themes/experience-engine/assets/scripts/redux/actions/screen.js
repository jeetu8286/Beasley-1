export const ACTION_PAGE_LOADING = 'ACTION_PAGE_LOADING';
export const ACTION_PAGE_LOADED = 'ACTION_PAGE_LOADED';

export const loadPage = ( url ) => ( dispatch ) => {
	dispatch( {
		type: ACTION_PAGE_LOADING,
		url,
	} );
};

export default {
	loadPage,
};
