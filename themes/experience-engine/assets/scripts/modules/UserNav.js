import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';

import { showSignInModal, showSignUpModal } from '../redux/actions/modal';

class UserNav extends Component {

	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			loading: true,
			user: null,
		};

		self.onAuthStateChanged = self.handleAuthStateChanged.bind( self );
		self.onSignIn = self.handleSignIn.bind( self );
		self.onSignUp = self.handleSignUp.bind( self );
		self.onSignOut = self.handleSignOut.bind( self );
	}

	componentDidMount() {
		const { firebase, bbgiconfig } = window;
		firebase.initializeApp( bbgiconfig.firebase );

		const auth = firebase.auth();
		auth.onAuthStateChanged( this.onAuthStateChanged );
	}

	handleAuthStateChanged( user ) {
		this.setState( { loading: false, user } );
	}

	handleSignIn() {
		this.props.dispatch( showSignInModal() );
	}

	handleSignUp() {
		this.props.dispatch( showSignUpModal() );
	}

	handleSignOut() {
		const { firebase } = window;
		firebase.auth().signOut();
	}

	renderLoadingState() {
		return (
			<div>Loading...</div>
		);
	}

	renderSignedInState() {
		return (
			<div>
				<button type="button" onClick={this.onSignOut}>Logout</button>
			</div>
		);
	}

	renderSignedOutState() {
		const self = this;

		return (
			<div>
				<button type="button" onClick={self.onSignIn}>Login</button>
				<button type="button" onClick={self.onSignUp}>Register</button>
			</div>
		);
	}

	render() {
		const self = this;
		const { loading, user } = self.state;
		const container = document.getElementById( 'user-nav' );

		let component = false;
		if ( loading ) {
			component = self.renderLoadingState();
		} else if ( user ) {
			component = self.renderSignedInState();
		} else {
			component = self.renderSignedOutState();
		}

		return ReactDOM.createPortal( component, container );
	}

}

UserNav.propTypes = {
	dispatch: PropTypes.func.isRequired,
};

export default connect()( UserNav );
