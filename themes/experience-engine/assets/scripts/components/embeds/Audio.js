import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

import delayed from '../../library/delayed-component';
import { playAudio } from '../../redux/actions/player';

class AudioEmbed extends PureComponent {

	constructor( props ) {
		super( props );

		const self = this;
		self.onPlayClick = self.handlePlayClick.bind( self );
	}

	getTitle() {
		const { src, sources } = this.props;
		const extractTitle = url => url
			.split( '/' ).pop() // take file name from URL
			.split( '?' ).shift() // drop query parameters
			.split( '.' ).shift() // drop file extension
			.split( '_' ).join( ' ' ); // replace underscores with white spaces

		if ( src ) {
			return extractTitle( src );
		}

		if ( sources ) {
			const keys = Object.keys( sources );
			if ( keys && keys.length ) {
				return extractTitle( keys.shift() );
			}
		}

		return '(no title)';
	}

	getPlayableSource() {
		const { src, sources } = this.props;
		const urls = Object.keys( sources );
		const audio = document.createElement( 'audio' );

		let maybe = false;
		for ( let i = 0, len = urls.length; i < len; i++ ) {
			const url = urls[i];
			const playable = audio.canPlayType( sources[url] );
			if ( 'probably' === playable ) {
				return url;
			}

			if ( 'maybe' === playable && false === maybe ) {
				maybe = url;
			}
		}

		if ( maybe ) {
			return maybe;
		}

		return src;
	}

	handlePlayClick() {
		const self = this;
		const src = self.getPlayableSource();
		if ( src ) {
			self.props.playAudio( src );
		}
	}

	render() {
		const self = this;
		const { placeholder } = self.props;

		return ReactDOM.createPortal(
			<div>
				<button type="button" onClick={self.onPlayClick}>Play</button>
				{self.getTitle()}
			</div>,
			document.getElementById( placeholder ),
		);
	}

}

AudioEmbed.propTypes = {
	placeholder: PropTypes.string.isRequired,
	src: PropTypes.string.isRequired,
	sources: PropTypes.shape( {} ).isRequired,
	playAudio: PropTypes.func.isRequired,
};

const mapDispatchToProps = ( dispatch ) => bindActionCreators( { playAudio }, dispatch );

export default delayed( connect( null, mapDispatchToProps )( AudioEmbed ) );
