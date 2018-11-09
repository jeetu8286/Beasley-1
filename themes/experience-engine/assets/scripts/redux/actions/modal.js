/**
 * We use this approach to create actions to minify its names in the production bundle
 * and have human friendly actions in dev bundle. Use "m{x}" format to create new actions.
 */

export const ACTION_SHOW_MODAL = 'production' === process.env.NODE_ENV ? 'm0' : 'MODAL_SHOW';
export const ACTION_HIDE_MODAL = 'production' === process.env.NODE_ENV ? 'm1' : 'MODAL_HIDE';

export const SIGNIN_MODAL = 'SIGNIN-MODAL';
export const SIGNUP_MODAL = 'SIGNUP-MODAL';
export const RESTORE_MODAL = 'RESTORE-MODAL';

const showModal = ( modal, payload ) => ( {
	type: ACTION_SHOW_MODAL,
	modal,
	payload,
} );

export const hideModal = () => ( { type: ACTION_HIDE_MODAL } );

export const showSignInModal = ( payload = {} ) => showModal( SIGNIN_MODAL, payload );

export const showSignUpModal = ( payload = {} ) => showModal( SIGNUP_MODAL, payload );

export const showRestoreModal = ( payload = {} ) => showModal( RESTORE_MODAL, payload );

export default {
	hideModal,
	showSignInModal,
	showSignUpModal,
	showRestoreModal,
};
