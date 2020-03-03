import {
	ACTION_INIT_PAGE,
	ACTION_LOADING_PARTIAL,
	ACTION_LOADING_PAGE,
	ACTION_LOADED_PAGE,
	ACTION_LOADED_PARTIAL,
	ACTION_LOAD_ERROR,
	ACTION_HIDE_SPLASH_SCREEN,
	ACTION_UPDATE_NOTICE,
	ACTION_HISTORY_HTML_SNAPSHOT,
} from '../actions/screen';

export const DEFAULT_STATE = {
	content: '',
	embeds: [],
	error: '',
	history: {},
	isHome: false,
	partials: {},
	scripts: {},
	splashScreen: true,
	url: false,
	notice: {
		isOpen: false,
		message: '',
	},
};

/**
 * @function reducer
 * Screen Reducer
 *
 * @param {Object} state State object
 * @param {Object} action Dispatched action
 */
function reducer( state = {}, action = {} ) {
	switch ( action.type ) {

		// Catch in Sagas
		case ACTION_INIT_PAGE:
			console.log( 'reducer: init page' );

			return {
				...state,
				content: action.content,
				embeds: action.embeds,
				scripts: action.scripts,
			};

		case ACTION_LOADING_PARTIAL:
		case ACTION_LOADING_PAGE:
			console.log( 'reducer: loading page' );

			return {
				...state,
				url: action.url,
			};

		case ACTION_LOADED_PAGE: {

			console.log( 'reducer: loaded page' );
			// do not accept action if user goes to another page before current page is loaded
			if ( state.url !== action.url && !action.force ) {
				return state;
			}

			return {
				...state,
				content: action.content,
				isHome: action.isHome,
				embeds: action.embeds,
				error: '',
				partials: {},
				scripts: action.scripts,
			};
		}

		case ACTION_LOADED_PARTIAL: {
			console.log( 'reducer: loaded partial' );

			// do not accept action if user goes to another page before current page is loaded
			if ( state.url !== action.url ) {
				return state;
			}

			return {
				...state,
				error: '',
				partials: {
					...state.partials,
					[action.placeholder]: {
						content: action.content,
						embeds: action.embeds,
					},
				},
			};
		}

		case ACTION_LOAD_ERROR:
			return {
				...state,
				error: action.error,
			};

		case ACTION_HIDE_SPLASH_SCREEN:
			console.log( 'reducer: hide splash screen' );

			return {
				...state,
				splashScreen: false,
			};

		case ACTION_UPDATE_NOTICE: {
			return {
				...state,
				notice: {
					isOpen: action.isOpen,
					message: action.message,
				},
			};
		}

		case ACTION_HISTORY_HTML_SNAPSHOT:
			return {
				...state,
				history: {
					...state.history,
					[action.uuid]: {
						id: action.uuid,
						data: action.data,
					},
				},
			};

		default:
			// do nothing
			break;
	}

	return state;
}

export default reducer;
