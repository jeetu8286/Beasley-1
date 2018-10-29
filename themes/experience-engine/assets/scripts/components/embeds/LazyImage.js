import React, { PureComponent } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

import delayed from '../../library/delayed-component';

class LazyImage extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;

		self.state = {
			containerWidth: 0,
			containerHeight: 0,
			image: '',
		};

		self.loadImage = self.loadImage.bind( self );
		self.onImageLoaded = self.handleImageLoaded.bind( self );
		self.onResize = self.handleResize.bind( self );
	}

	componentDidMount() {
		const { bbgiconfig } = window;

		const self = this;
		const { placeholder } = self.props;

		self.container = document.getElementById( placeholder );

		self.worker = new Worker( bbgiconfig.workers.image );
		self.worker.onmessage = self.onImageLoaded;

		self.loadImage();

		window.addEventListener( 'resize', self.onResize );
	}

	componentWillUnmount() {
		const self = this;

		self.worker.terminate();
		window.removeEventListener( 'resize', self.onResize );
	}

	loadImage() {
		const self = this;
		const { src, width, height, aspect } = self.props;

		const { offsetWidth } = self.container;

		const containerWidth = offsetWidth;
		const containerHeight = offsetWidth / aspect;
		const anchor = width > height ? 'middlecenter' : 'leftop';
		const image = `${src.split( '?' )[0]}?maxwidth=${containerWidth}&maxheight=${containerHeight}&anchor=${anchor}`;

		self.setState( { containerWidth, containerHeight } );

		if ( self.isInViewport( containerWidth, containerHeight ) ) {
			self.worker.postMessage( image );
		}
	}

	handleImageLoaded( e ) {
		this.setState( { image: e.data } );
	}

	handleResize() {
		window.requestAnimationFrame( this.loadImage );
	}

	isInViewport( newWidth, newHeight ) {
		const bounding = this.container.getBoundingClientRect();
		const { top, left } = bounding;

		const { documentElement } = document;
		const innerHeight = window.innerHeight || documentElement.clientHeight;
		const innerWidth = window.innerWidth || documentElement.clientWidth;

		return 0 <= top && 0 <= left && ( top + newHeight ) <= innerHeight && ( left + newWidth ) <= innerWidth;
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
			<div className="lazy-image" style={styles}>
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
