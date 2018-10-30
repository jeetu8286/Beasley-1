import React, { PureComponent } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

import delayed from '../../library/delayed-component';

class LazyImage extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.boxRef = React.createRef();
		self.state = {
			containerWidth: 0,
			containerHeight: 0,
			image: '',
		};

		self.loadImage = self.loadImage.bind( self );
		self.onResize = self.handleResize.bind( self );
	}

	componentDidMount() {
		const self = this;
		const { placeholder } = self.props;

		self.container = document.getElementById( placeholder );

		self.loadImage();

		window.addEventListener( 'scroll', self.onResize );
		window.addEventListener( 'resize', self.onResize );
	}

	componentWillUnmount() {
		const self = this;

		window.removeEventListener( 'scroll', self.onResize );
		window.removeEventListener( 'resize', self.onResize );
	}

	loadImage() {
		const self = this;
		const { src, width, height, aspect } = self.props;

		const { offsetWidth } = self.container;

		const containerWidth = offsetWidth;
		const containerHeight = offsetWidth / aspect;
		const anchor = width > height ? 'middlecenter' : 'leftop';

		self.setState( { containerWidth, containerHeight } );

		if ( self.isInViewport( containerWidth, containerHeight ) ) {
			const imageSrc = `${src.split( '?' )[0]}?maxwidth=${containerWidth}&maxheight=${containerHeight}&anchor=${anchor}`;
			const imageLoader = new Image();

			imageLoader.src = imageSrc;
			imageLoader.onload = () => {
				if ( self.boxRef.current ) { // check if component is still mounted
					self.setState( { image: imageSrc } );
				}
			};
		}
	}

	handleResize() {
		window.requestAnimationFrame( this.loadImage );
	}

	isInViewport( containerWidth, containerHeight ) {
		const bounding = this.container.getBoundingClientRect();
		const { top, left } = bounding;

		return -containerHeight <= top && -containerWidth <= left;
	}

	render() {
		const self = this;
		const { container } = self;
		const { containerWidth, containerHeight, image } = self.state;

		if ( !container ) {
			return false;
		}

		const styles = {
			width: `${containerWidth}px`,
			height: `${containerHeight}px`,
			backgroundImage: `url(${image})`,
		};

		const loader = !image ? <div className="loading" /> : false;

		const embed = (
			<div className="lazy-image" ref={self.boxRef} style={styles}>
				{loader}
			</div>
		);

		return ReactDOM.createPortal( embed, container );
	}

}

LazyImage.propTypes = {
	placeholder: PropTypes.string.isRequired,
	src: PropTypes.string.isRequired,
	width: PropTypes.string.isRequired,
	height: PropTypes.string.isRequired,
	aspect: PropTypes.string.isRequired,
};

export default delayed( LazyImage, 50 );
