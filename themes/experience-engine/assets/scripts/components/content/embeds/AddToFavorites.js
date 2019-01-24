import React, { PureComponent } from 'react';


class AddToFavorites extends PureComponent {

	render() {
		return (
			<button className="btn -empty -nobor -icon">
				<svg width="15" height="15" xmlns="http://www.w3.org/2000/svg">
					<path fillRule="evenodd" clipRule="evenodd" d="M8.5 0h-2v6.5H0v2h6.5V15h2V8.5H15v-2H8.5V0z"/>
				</svg>
				Add to my feed
			</button>
		);
	}

}

export default AddToFavorites;
