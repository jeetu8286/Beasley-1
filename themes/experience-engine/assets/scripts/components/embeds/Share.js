import React, { PureComponent } from 'react';

class Share extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;
		self.onFacebookClick = self.handleFacebookClick.bind( self );
		self.onTwitterClick = self.handleTwitterClick.bind( self );
	}

	handleFacebookClick() {
		console.log( 'Facebook' );
	}

	handleTwitterClick() {
		console.log( 'Twitter' );
	}

	render() {
		const self = this;

		return (
			<div>
				Share:

				<button type="button" onClick={self.onFacebookClick}>
					Facebook
				</button>

				<button type="button" onClick={self.onTwitterClick}>
					Twitter
				</button>
			</div>
		);
	}

}

export default Share;
