import React, { PureComponent } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types';

import delayed from '../../library/delayed-component';
import * as actions from '../../redux/actions/player';

import Controls from '../player/Controls';

class AudioEmbed extends PureComponent {

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

		return false;
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

	render() {
		const self = this;
		const { placeholder, omny, audio, status, play, pause, resume } = self.props;

		const container = document.getElementById( placeholder );
		if ( !container ) {
			return false;
		}

		const title = !omny ? self.getTitle() : false;
		const src = self.getPlayableSource();
		const audioStatus = audio === src ? status : actions.STATUSES.LIVE_STOP;

		const embed = (
			<div>
				<Controls status={audioStatus} play={() => play( src, title )} pause={pause} resume={resume} />
				{title}
			</div>
		);

		return ReactDOM.createPortal( embed, container );
	}

}

AudioEmbed.propTypes = {
	audio: PropTypes.string.isRequired,
	status: PropTypes.string.isRequired,
	placeholder: PropTypes.string.isRequired,
	src: PropTypes.string.isRequired,
	omny: PropTypes.bool,
	sources: PropTypes.shape( {} ),
	play: PropTypes.func.isRequired,
	pause: PropTypes.func.isRequired,
	resume: PropTypes.func.isRequired,
};

AudioEmbed.defaultProps = {
	omny: false,
	sources: {},
};

const mapStateToProps = ( { player } ) => ( {
	audio: player.audio,
	status: player.status,
} );

const mapDispatchToProps = ( dispatch ) => bindActionCreators( {
	play: actions.playAudio,
	pause: actions.pause,
	resume: actions.resume,
}, dispatch );

export default delayed( connect( mapStateToProps, mapDispatchToProps )( AudioEmbed ), 50 );
