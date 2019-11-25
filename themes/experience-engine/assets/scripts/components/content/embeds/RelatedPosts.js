import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';
import LazyImage from './LazyImage';

const RelatedPost = ( { id, url, title, primary_image, published } ) => (
	<div id={`post-${id}`} className={['post-tile post'].join( ' ' )}>
		<div className="post-thumbnail">
			<a href={`https://${url}`} id={`thumbnail-${id}`}>
				<LazyImage
					crop={ false }
					placeholder={`thumbnail-${id}`}
					src={primary_image}
					width={310}
					height={205}
					alt={title || ''} />
			</a>
		</div>
		<div className="post-details">
			<div className="post-date">
				{published}
			</div>
			<div className="post-title">
				<h3>
					<a href={`https://${url}`}>
						{title}
					</a>
				</h3>
			</div>
		</div>
	</div>
);

RelatedPost.propTypes = {
	id: PropTypes.string.isRequired,
	url: PropTypes.string.isRequired,
	title: PropTypes.string.isRequired,
	primary_image: PropTypes.string.isRequired,
	published: PropTypes.string.isRequired,
};

const RelatedPosts = () => {
	const [postsEndpointURL, setPostsEndpointURL] = useState( '' );
	const [relatedPosts, setRelatedPosts] = useState( [] );
	const [loading, setLoading] = useState( false );
	const { bbgiconfig } = window;

	const endpointURL = `${bbgiconfig.eeapi}publishers/${bbgiconfig.publisher.id}/recommendations?categories=sport&posttype=post&url=/`;

	useEffect( () => {
		async function fetchPostsEndpoint() {
			setLoading( true );
			const result = await fetch( endpointURL ).then( r => r.json() );
			let transformedURL = result.url;

			if ( 'undefined' !== typeof window.jstag ) {
				const seerid = window.jstag.ckieGet( 'seerid' );
				if ( seerid ) {
					transformedURL = transformedURL.replace( '{userid}', seerid );
				}
			}

			setPostsEndpointURL( transformedURL );
		}

		fetchPostsEndpoint();
	}, [] );

	useEffect( () => {
		async function fetchPosts() {
			if ( postsEndpointURL ) {
				const result = await fetch( postsEndpointURL ).then( r => r.json() );
				setRelatedPosts( result.data );
				setLoading( false );
			}
		}

		fetchPosts();
	}, [ postsEndpointURL] );

	if ( loading ) {
		return <div>Loading...</div>;
	}

	if ( relatedPosts && 0 < relatedPosts.length ) {
		return (
			<div className="related-articles content-wrap">
				<h2 className="section-head"><span>You Might Also Like</span></h2>

				<div className="archive-tiles -list">
					{relatedPosts.map( relatedPost => <RelatedPost key={relatedPost.id} {...relatedPost} /> )}
				</div>
			</div>
		);
	}

	return null;
};

export default RelatedPosts;
