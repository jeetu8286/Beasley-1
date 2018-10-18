export const ACTION_SHOW_MODAL = 'ACTION_SHOW_MODAL';
export const ACTION_HIDE_MODAL = 'ACTION_HIDE_MODAL';

export const SIGNIN_MODAL = 'SIGNIN_MODAL';
export const SIGNUP_MODAL = 'SIGNUP_MODAL';
export const RESTORE_MODAL = 'RESTORE_MODAL';

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
