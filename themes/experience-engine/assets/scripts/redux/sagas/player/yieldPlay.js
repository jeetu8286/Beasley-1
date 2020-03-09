import { call, takeLatest, select, put } from 'redux-saga/effects';
import { livePlayerLocalStorage } from '../../utilities';
import { ACTION_PLAY } from '../../actions/player';

/**
 * @function getStreamByStation
 *
 * Used to return a helper function that will receive an
 * item with a stream_call_letters property that is then
 * compared against the station parameter
 *
 * @param {String} station station id value
 * @returns {Function} method that tests matching station in an object
 */
const getStreamByStation = station =>
	( { stream_call_letters } ) =>
		stream_call_letters === station;

/**
 * @function yieldPlay
 * Generator runs whenever ACTION_PLAY_AUDIO is dispatched
 */
function* yieldPlay( action ) {
	const { source } = action.payload;
	console.log( 'yieldPlay', source );

	// Player store from state
	const playerStore = yield select( ( { player } ) => player );
	const { player, streams } = playerStore;

	if ( 'tdplayer' === playerStore.playerType ) {
		// Find matching stream
		const stream = yield call( [ streams, 'find' ], getStreamByStation( source ) );
		// Destructure from window
		const {
			authwatcher, // Triton
		} = window;

		// Setup adConfig used by player and triton call
		const adConfig = {
			host: stream.stream_cmod_domain,
			type: 'preroll',
			format: 'vast',
			stationId: stream.stream_tap_id,
			trackingParameters: {
				dist: 'beasleyweb',
			},
		};

		// Call triton, must live here since it modifies the adConfig object
		// before being sent to the player API
		if ( authwatcher && authwatcher.lastLoggedInUser ) {
			if ( 'undefined' !== typeof authwatcher.lastLoggedInUser.demographicsset ) {
				if ( authwatcher.lastLoggedInUser.demographicsset ) {
					console.log( 'triton','params sent' );
					adConfig.trackingParameters = {
						...adConfig.trackingParameters,
						postalcode: authwatcher.lastLoggedInUser.zipcode,
						gender: authwatcher.lastLoggedInUser.gender,
						dob: authwatcher.lastLoggedInUser.dateofbirth,
					};
				}
			}
		}

		// Call tdplayer.playAd
		if (
			stream && 'function' === typeof player.playAd
		) {
			yield call(
				[ player, 'playAd' ],
				'tap',
				adConfig,
			);
		}

		// Call livePlayerLocalStorage
		if (
			livePlayerLocalStorage && 'function' === typeof livePlayerLocalStorage.setItem
		) {
			yield call( [ livePlayerLocalStorage, 'setItem' ], 'station', source );
		}
	} else if (
		player &&
		'function' === typeof player.play
	) {
		console.log( 'yes we hit the play method---------------' );
		yield call( [ player, 'play' ] );
	}
}

/**
 * @function watchPlay
 * Generator used to bind action and callback
 */
export default function* watchPlay() {
	yield takeLatest( [ACTION_PLAY], yieldPlay );
}
