import { call, select, takeLatest } from 'redux-saga/effects';
import { ACTION_PLAYER_STOP } from '../../actions/player';
import { fullStop } from '../../utilities';

function* yieldStop( ) {
	console.log( 'yieldStop' );
	const playerStore = yield select( ( { player } ) => player );

	yield call( fullStop, playerStore );
}

export default function* watchStop() {
	yield takeLatest( [ACTION_PLAYER_STOP], yieldStop );
}
