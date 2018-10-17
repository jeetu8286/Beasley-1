import React, { PureComponent, Fragment } from 'react';

import Header from './modal/Header';
import OAuthButtons from './authentication/OAuthButtons';

class SignUpModal extends PureComponent {

	render() {
		return (
			<Fragment>
				<Header>Sign Up</Header>
				<OAuthButtons />
			</Fragment>
		);
	}

}

export default SignUpModal;
