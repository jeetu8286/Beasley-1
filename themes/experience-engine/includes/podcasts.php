<?php

if ( ! function_exists( 'ee_get_episodes_query' ) ) :
	function ee_get_episodes_query( $podcast = null, $args = array() ) {
		$podcast = get_post( $podcast );

		return new \WP_Query( array_merge( $args, array(
			'post_type'   => 'episode',
			'post_parent' => $podcast->ID,
		) ) );
	}
endif;

if ( ! function_exists( 'ee_get_episodes_count' ) ) :
	function ee_get_episodes_count( $podcast = null ) {
		$podcast = get_post( $podcast );
		$key = 'podcast-episodes-count-' . $podcast->ID;

		$count = wp_cache_get( $key, 'experience-engine' );
		if ( ! $count ) {
			$query = ee_get_episodes_query( $podcast, array(
				'posts_per_page' => 1,
				'fields'         => 'ids',
			) );

			$count = $query->found_posts;
			wp_cache_set( $key, $count, 'experience-engine', 15 * MINUTE_IN_SECONDS );
		}

		return $count;
	}
endif;

if ( ! function_exists( 'ee_get_episode_player' ) ) :
	function ee_get_episode_player( $episode = null ) {
		$matches = array();
		$episode = get_post( $episode );

		if (
			is_a( $episode, '\WP_Post' )
			&& preg_match( '#\[(embed|audio).*?\].*?\[\/(embed|audio)\]#i', $episode->post_content, $matches )
			&& $matches[1] == $matches[2]
		) {
			remove_filter( 'the_content', 'wpautop' );
			$content = apply_filters( 'the_content', $matches[0] );
			add_filter( 'the_content', 'wpautop' );

			return $content;
		}

		return null;
	}
endif;

if ( ! function_exists( 'ee_the_episode_player' ) ) :
	function ee_the_episode_player( $episode = null ) {
		echo ee_get_episode_player( $episode );
	}
endif;

if ( ! function_exists( 'ee_the_latest_episode' ) ) :
	function ee_the_latest_episode( $podcast = null ) {
		$podcast = get_post( $podcast );
		$key = 'podcast-latest-episodes-' . $podcast->ID;

		$episode = wp_cache_get( $key, 'experience-engine' );
		if ( ! $episode ) {
			$query = ee_get_episodes_query( $podcast, array(
				'posts_per_page' => 1,
				'fields'         => 'ids',
			) );

			$episode = $query->next_post();
			wp_cache_set( $key, $episode, 'experience-engine', 15 * MINUTE_IN_SECONDS );
		}

		if ( $episode ) {
			$episode = get_post( $episode );
			$player = ee_get_episode_player( $episode );
			if ( $player ) {
				?><div>
					<div><?php echo $player; ?></div>
					<div>Play Latest: <?php ee_the_date( $episode ); ?></div>
				</div><?php
			}
		}
	}
endif;
