import React from 'react';
import ReactDOM from 'react-dom';
import ErrorBoundary from '../components/ErrorBoundary';

const PlayerButton = () => {
	console.log('FIRED PlayerButton');

	const container = document.getElementById('player-button-div');

	const children = (
		<ErrorBoundary>
			<button id="player-button" type="button">
				<svg
					id="player-button-svg"
					x="0px"
					y="0px"
					width="20px"
					height="20px"
					viewBox="0 0 408.221 408.221"
				>
					<g id="player-button-g">
						<path d="M204.11,0C91.388,0,0,91.388,0,204.111c0,112.725,91.388,204.11,204.11,204.11c112.729,0,204.11-91.385,204.11-204.11    C408.221,91.388,316.839,0,204.11,0z M286.547,229.971l-126.368,72.471c-17.003,9.75-30.781,1.763-30.781-17.834V140.012    c0-19.602,13.777-27.575,30.781-17.827l126.368,72.466C303.551,204.403,303.551,220.217,286.547,229.971z" />
					</g>
				</svg>
			</button>
		</ErrorBoundary>
	);

	return ReactDOM.createPortal(children, container);
};

export default PlayerButton;
