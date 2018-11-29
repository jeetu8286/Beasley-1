<?php

add_filter( 'embed_oembed_html', 'ee_update_embed_oembed_html', 10, 4 );

if ( ! function_exists( 'ee_update_embed_oembed_html' ) ) :
	function ee_update_embed_oembed_html( $html, $url, $attr, $post_ID ) {
		$data = wp_cache_get( $url, 'ee:oembed' );
		if ( empty( $data ) ) {
			$data = _wp_oembed_get_object()->get_data( $url );
			wp_cache_set( $url, $data, 'ee:oembed' );
		}

		if ( $data->provider_name == 'Facebook' && preg_match( '#[^\'"]+connect.facebook.net[^\'"]+#i', $html, $matches ) ) {
			$fb_connect = filter_var( $matches[0], FILTER_VALIDATE_URL );
			if ( $fb_connect ) {
				$html = preg_replace( '#<script.*?>.*?</script>#i', '<script src="' . esc_attr( $fb_connect ) . '"></script>', $html );
			}
		} elseif ( $data->provider_name == 'YouTube' ) {
			$html = sprintf(
				'<div class="youtube" data-title="%s" data-thumbnail="%s" data-html="%s"></div>',
				esc_attr( $data->title ),
				esc_attr( $data->thumbnail_url ),
				esc_attr( $data->html )
			);
		}

//		var_dump( $data );

		return $html;
	}
endif;
