import React from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

import { hideModal, SIGNIN_MODAL, SIGNUP_MODAL } from '../redux/actions/modal';

import SignInModal from '../components/SignInModal';
import SignUpModal from '../components/SignUpModal';

const ModalDispatcher = ( { modal, payload, dispatch } ) => {
	let component = false;
	switch ( modal ) {
		case SIGNIN_MODAL:
			component = <SignInModal {...payload} />;
			break;
		case SIGNUP_MODAL:
			component = <SignUpModal {...payload} />;
			break;
		default:
			return false;
	}

	return (
		<div className="modal">
			<div className="modal-content">
				<button type="button" className="modal-close" onClick={() => dispatch( hideModal() )}>X</button>
				{component}
			</div>
		</div>
	);
};

ModalDispatcher.propTypes = {
	modal: PropTypes.string,
	payload: PropTypes.shape( {} ),
	dispatch: PropTypes.func.isRequired,
};

ModalDispatcher.defaultProps = {
	modal: '',
	payload: {},
};

const mapStateToProps = ( { modal } ) => ( { ...modal } );

export default connect( mapStateToProps )( ModalDispatcher );
