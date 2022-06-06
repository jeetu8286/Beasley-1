import { PureComponent } from 'react';
import PropTypes from 'prop-types';
/* import React, { useEffect, useState } from 'react'; */

class MegamenuRecentPosts extends PureComponent {
	componentDidMount = async () => {
		try {
			// const { bbgiconfig } = window;
			const {
				placeholder,
				postsperpage,
				categories,
				showthumb,
				showthumbsize,
			} = this.props;

			const container = document.getElementById(placeholder);
			if (!container) {
				return;
			}

			console.log('Params: ', showthumb, showthumbsize);
			// http://wmmr.beasley.test/wp-json/megamenu_recent_posts/v1/get_posts?per_page=5&page=1&post_type=post&cat=32&show_thumb=yes&thumb_size=full
			// const endpointURL = `${bbgiconfig.eeapi}page=${postsperpage}/recommendations?categories=${categories || ''}`;
			const endpointURL = `https://wmmr.beasley.test/wp-json/megamenu_recent_posts/v1/get_posts?per_page=${postsperpage}&cat=${categories}&show_thumb=${showthumb}`;
			console.log('endpointURL: ', endpointURL);
			const result = await fetch(endpointURL).then(r => r.json());
			console.log(result);
			if (result.recent_posts && result.recent_posts.length) {
				let html = `<ul>`;

				result.recent_posts.forEach(post => {
					html += `
					  <li>
						<a href="${post.permalink}">`;
					if (post.thumbnail) {
						html += `<img width="1024" height="683" src="${post.thumbnail}" class="attachment-large size-large wp-post-image" alt="s_pW5jcElAM" loading="lazy"></img>`;
					}
					html += `<span class="rpwwt-post-title">${post.title}</span>
						</a>
					</li>
					`;
				});
				html += `</ul>`;
				console.log(html);
				container.innerHTML = html;
			}
		} catch (e) {
			console.log('failed to load api');
		}
	};

	render() {
		return false;
	}
}

/* const MegamenuRecentPosts = () => {
	return `<div>rupesh working here</div>`;
}; */

MegamenuRecentPosts.propTypes = {
	placeholder: PropTypes.string.isRequired,
	postsperpage: PropTypes.string,
	categories: PropTypes.string,
	showthumb: PropTypes.string,
	showthumbsize: PropTypes.string,
};

MegamenuRecentPosts.defaultProps = {
	postsperpage: 'all',
	categories: '',
	showthumb: '',
	showthumbsize: '/',
};

export default MegamenuRecentPosts;
