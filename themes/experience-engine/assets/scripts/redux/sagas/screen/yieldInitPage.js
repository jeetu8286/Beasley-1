import { call, takeLatest, select } from 'redux-saga/effects';
import {
	manageScripts,
} from '../../utilities';
import {
	ACTION_INIT_PAGE,
} from '../../actions/screen';

/**
 * @function yieldInitPage
 * Generator runs whenever [ ACTION_INIT_PAGE ]
 * is dispatched
 *
 * @param { Object } action Dispatched action
 * @param { Object } action.scripts Scripts from action
 */
function* yieldInitPage( { scripts } ) {
	console.log( 'yieldInitPage' );

	// Screen store from state
	const screenStore = yield select( ( { screen } ) => screen );

	// Call manageScripts
	yield call( manageScripts, scripts, screenStore.scripts );

}

/**
 * @function watchInitPage
 * Generator used to bind action and callback
 */
export default function* watchInitPage() {
	yield takeLatest( [ ACTION_INIT_PAGE ], yieldInitPage );
}
