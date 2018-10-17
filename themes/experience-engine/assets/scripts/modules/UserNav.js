import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class UserNav extends Component {

	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			loading: true,
			user: null,
		};

		self.onAuthStateChanged = self.handleAuthStateChanged.bind( self );
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

	renderLoadingState() {
		return (
			<div>Loading...</div>
		);
	}

	renderSignedInState() {
		const { user } = this.state;

		return (
			<div>user...</div>
		);
	}

	renderSignedOutState() {
		return (
			<div>
				<button type="button">Login</button>
				<button type="button">Register</button>
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

export default User;
