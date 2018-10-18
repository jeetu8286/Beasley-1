import React, { PureComponent, Fragment } from 'react';
import PropTypes from 'prop-types';
import firebase from 'firebase';

import Header from './modal/Header';
import Alert from './modal/Alert';
import OAuthButtons from './authentication/OAuthButtons';

class SignInModal extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			email: '',
			password: '',
			error: '',
		};

		self.onFieldChange = self.handleFieldChange.bind( self );
		self.onFormSubmit = self.handleFormSubmit.bind( self );
	}

	handleFieldChange( e ) {
		const { target } = e;
		this.setState( { [target.name]: target.value } );
	}

	handleFormSubmit( e ) {
		const self = this;
		const { email, password } = self.state;
		const auth = firebase.auth();

		e.preventDefault();

		auth.signInWithEmailAndPassword( email, password )
			.then( self.close )
			.catch( error => self.setState( { error: error.message } ) );
	}

	render() {
		const self = this;
		const { email, password, error } = self.state;

		return (
			<Fragment>
				<Header>Sign In</Header>

				<Alert message={error} />

				<form onSubmit={self.onFormSubmit}>
					<div>
						<label htmlFor="user-email">Email</label>
						<input type="email" id="user-email" name="email" value={email} onChange={self.onFieldChange} />
					</div>
					<div>
						<label htmlFor="user-password">Password</label>
						<input type="password" id="user-password" name="password" value={password} onChange={self.onFieldChange} />
					</div>
					<div>
						<button type="submit">Sign In</button>
					</div>
				</form>

				<OAuthButtons />
			</Fragment>
		);
	}

}

SignInModal.propTypes = {
	close: PropTypes.func.isRequired,
};

export default SignInModal;
