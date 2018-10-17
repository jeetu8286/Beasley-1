import React, { Component } from 'react';
import ReactDOM from 'react-dom';

class User extends Component {

	render() {
		return ReactDOM.createPortal(
			<div />,
			document.getElementById( 'user-nav' )
		);
	}

}

export default User;
