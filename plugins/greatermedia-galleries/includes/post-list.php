<?php
/**
 * Handles filtering and additional columns on the post-listing screen
 *
 * static class for consistency
 */

class GreaterMediaGalleriesPostList {

	public static function init() {
		add_filter( 'manage_' . GreaterMediaGalleryCPT::GALLERY_POST_TYPE . '_posts_columns', array( __CLASS__, 'add_album_column' ) );
		add_filter( 'manage_' . GreaterMediaGalleryCPT::GALLERY_POST_TYPE . '_posts_custom_column', array( __CLASS__, 'render_custom_column' ), 10, 2 );
		add_action( 'pre_get_posts', array( __CLASS__, 'pre_get_posts' ) );
	}

	public static function add_album_column( $columns ) {
		$first = array_slice( $columns, 0, 3 );
		$first['album'] = 'Album';

		$columns = array_merge( $first, $columns );

		return $columns;
	}

	public static function render_custom_column( $column, $post_id ) {
		switch( $column ) {
			case 'album':
				$post = get_post( $post_id );
				if ( $post->post_parent > 0 ) {
					$parent = get_post( $post->post_parent );
					?>
					<a href="<?php echo esc_url( add_query_arg( 'post_parent', $parent->ID ) ); ?>"><?php echo esc_html( $parent->post_title ); ?></a>
					<?php
				} else {
					echo '&mdash;';
				}
				break;
		}
	}

	/**
	 * Filters the admin query on the post-edit screen for galleries to respect the post_parent arg if it is present in the URL
	 *
	 * @param WP_Query $query
	 */
	public static function pre_get_posts( \WP_Query $query ) {
		if ( ! is_admin() ) {
			return;
		}

		if ( $query->is_main_query() && $query->get( 'post_type' ) === GreaterMediaGalleryCPT::GALLERY_POST_TYPE && isset( $_GET['post_parent'] ) ) {
			$query->set( 'post_parent', intval( $_GET['post_parent'] ) );
		}
	}

}

GreaterMediaGalleriesPostList::init();
