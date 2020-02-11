import { call, takeLatest, select } from 'redux-saga/effects';
import { lyticsTrack } from '../reducers/player';
import {
	ACTION_SEEK_POSITION,
} from '../actions/player';

// TODO: Why no tdplayer here, but in others?

/**
 * @function yieldSeekPosition
 * Generator runs whenever ACTION_SEEK_POSITION is dispatched
 *
 * @param {Object} action Dispathed action
 * @param {Object} action.position Position from dispatched action
 */
function* yieldSeekPosition( { position } ) {
	console.log( 'yieldSeekPosition' );

	// Destructure from window
	const {
		mp3player,
		omnyplayer,
	} = window;

	// TODO: Look at storing UI state in Redux Store
	window.userInteraction = true;

	if ( mp3player ) {
		mp3player.currentTime = position;

	} else if ( omnyplayer ) {

		if( 'function' === typeof omnyplayer.setCurrentTime ) {
			yield call( [ omnyplayer, 'setCurrentTime' ], position );
		}
	}

}

/**
 * @function watchSeekPosition
 * Generator used to bind action and callback
 */
export default function* watchSetVolume() {
	yield takeLatest( [ACTION_SEEK_POSITION], yieldSeekPosition );
}
