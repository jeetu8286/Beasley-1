import React, { PureComponent, Fragment } from 'react';

import Header from './modal/Header';
import OAuthButtons from './authentication/OAuthButtons';

class SignInModal extends PureComponent {

	render() {
		return (
			<Fragment>
				<Header>Sign In</Header>
				<OAuthButtons />
			</Fragment>
		);
	}

}

export default SignInModal;
