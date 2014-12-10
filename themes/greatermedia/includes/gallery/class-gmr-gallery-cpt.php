<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

/**
 * Class GreaterMediaGalleryCPT
 */
class GreaterMediaGalleryCPT {

	const GALLERY_POST_TYPE = 'gallery';

	const ALBUM_POST_TYPE = 'album';

	/**
	 * Hook into the appropriate actions when the class is initiated.
	 */
	public static function init() {

		add_action( 'init', array( __CLASS__, 'gallery_cpt' ), 0 );
		add_action( 'init', array( __CLASS__, 'album_cpt' ), 0 );
		add_action( 'init', array( __CLASS__, 'register_shadow_taxonomy' ) );
		add_action( 'save_post', array( __CLASS__, 'update_shadow_taxonomy' ) );
		add_action( 'before_delete_post', array( __CLASS__, 'delete_shadow_tax_term' ) );
	}

	/**
	 * Add the Gallery Content Type
	 */
	public static function gallery_cpt() {

		$labels = array(
			'name'                => _x( 'Galleries', 'Post Type General Name', 'greatermedia' ),
			'singular_name'       => _x( 'Gallery', 'Post Type Singular Name', 'greatermedia' ),
			'menu_name'           => __( 'Galleries', 'greatermedia' ),
			'parent_item_colon'   => __( 'Parent Item:', 'greatermedia' ),
			'all_items'           => __( 'All Galleries', 'greatermedia' ),
			'view_item'           => __( 'View Gallery', 'greatermedia' ),
			'add_new_item'        => __( 'Add New Gallery', 'greatermedia' ),
			'add_new'             => __( 'Add New', 'greatermedia' ),
			'edit_item'           => __( 'Edit Gallery', 'greatermedia' ),
			'update_item'         => __( 'Update Gallery', 'greatermedia' ),
			'search_items'        => __( 'Search Galleries', 'greatermedia' ),
			'not_found'           => __( 'Not found', 'greatermedia' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'greatermedia' ),
		);
		$rewrite = array(
			'slug'                => self::GALLERY_POST_TYPE,
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'gallery', 'greatermedia' ),
			'description'         => __( 'A post type for Galleries', 'greatermedia' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', ),
			'taxonomies'          => array( 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-gallery',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		);
		register_post_type( self::GALLERY_POST_TYPE, $args );

	}

	/**
	 * Add the Albums Content Type
	 */
	public static function album_cpt() {

		$labels = array(
			'name'                => _x( 'Albums', 'Post Type General Name', 'greatermedia' ),
			'singular_name'       => _x( 'Album', 'Post Type Singular Name', 'greatermedia' ),
			'menu_name'           => __( 'Albums', 'greatermedia' ),
			'parent_item_colon'   => __( 'Parent Item:', 'greatermedia' ),
			'all_items'           => __( 'Albums', 'greatermedia' ),
			'view_item'           => __( 'View Album', 'greatermedia' ),
			'add_new_item'        => __( 'Add New Albim', 'greatermedia' ),
			'add_new'             => __( 'Add New', 'greatermedia' ),
			'edit_item'           => __( 'Edit Album', 'greatermedia' ),
			'update_item'         => __( 'Update Album', 'greatermedia' ),
			'search_items'        => __( 'Search Albums', 'greatermedia' ),
			'not_found'           => __( 'Not found', 'greatermedia' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'greatermedia' ),
		);
		$rewrite = array(
			'slug'                => self::ALBUM_POST_TYPE,
			'with_front'          => true,
			'pages'               => true,
			'feeds'               => true,
		);
		$args = array(
			'label'               => __( 'album', 'greatermedia' ),
			'description'         => __( 'Album CPT', 'greatermedia' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', ),
			'taxonomies'          => array( 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=gallery',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-media-text',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		);
		register_post_type( self::ALBUM_POST_TYPE, $args );

	}

	/**
	 * Register the Gallerys Shadow Taxononmy
	 */
	public static function register_shadow_taxonomy() {

		$labels = array(
			'name'              => 'Gallery',
			'singular_name'     => 'Gallery',
			'search_items'      => 'Search',
			'all_items'         => 'All Galleries',
			'parent_item'       => 'Parent Gallery',
			'parent_item_colon' => 'Parent Gallery: ',
			'edit_item'         => 'Edit Gallery',
			'update_item'       => 'Update Gallery',
			'add_new_item'      => 'Add New Gallery',
			'new_item_name'     => 'New Gallery',
			'menu_name'         => 'Galleries',
		);

		$args = array(
			'labels'         => $labels,
			'rewrite'       => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'show_admin_column' => true,
			'show_tagcloud' => true,
			'hierarchical'  => true,

		);

		register_taxonomy( '_gallery', array( 'album' ), $args );
	}

	/**
	 * Update the shadow taxonomy when a galleries has been published or updated. Also ensure that the new term is no duplicated
	 *
	 * @param $post_id
	 */
	public static function update_shadow_taxonomy( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'gallery' !== get_post_type( $post_id ) ) {
			return;
		}

		$galleries = get_post( $post_id );
		if ( null === $galleries ) {
			return;
		}

		// To make sure we don't get things like 'auto-draft'
		if ( 'publish' != $galleries->post_status ) {
			return;
		}

		if ( $galleries->post_parent ) {
			$parent_gallery = get_post( $galleries->post_parent );
			$parent_term = get_term_by( 'slug', $parent_gallery->post_name, '_gallery' );
		} else {
			$parent_term = false;
		}

		$term = get_term_by( 'slug', $galleries->post_name, '_gallery' );

		if ( false === $term ) {
			$args = array();

			if ( $parent_term ) {
				$args['parent'] = $parent_term->term_id;
			}

			// See if there is an existing post_tag with the same slug as the publication. We can't trust WordPress to do this in wp_insert_term() because it will think "Complete Book Of Guns" and "Complete Book of Guns" (small "of") are different tags.
			$exising_term = get_term_by( 'slug', $galleries->post_name, 'post_tag' );

			if ( false === $exising_term) {
				wp_insert_term( $galleries->post_title, '_gallery', $args );
			} else {
				// If there is an existing term in post_tag, use its name instead of the publication's title. This bypasses any weird matching issues in wp_insert_term();
				wp_insert_term( $exising_term->name, '_gallery', $args );
			}
		} else {
			// We have the term. Make sure the term has the correct parent set (Could get out of sync if the issue was added without a parent, and changed later)

			// If we should have a parent term, but the term doesn't have this set - Add the parent ID
			if ( $parent_term && $parent_term->term_id != $term->parent ) {
				wp_update_term( $term->term_id, '_gallery', array( 'parent' => $parent_term->term_id ) );
			}

			// If we shouldn't have a parent term, but the term DOES have a parent set - Clear the parent ID
			if ( ! $parent_term && $term->parent != 0 ) {
				wp_update_term( $term->term_id, '_gallery', array( 'parent' => 0 ) );
			}
		}
	}

	/**
	 * Delete a gallery term when the corresponding gallery has been deleted
	 *
	 * @param $post_id
	 */
	public static function delete_shadow_tax_term( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'gallery' !== get_post_type( $post_id ) ) {
			return;
		}

		$galleries = get_post( $post_id );
		if ( null === $galleries ) {
			return;
		}

		$term = get_term_by( 'slug', $galleries->post_name, '_gallery' );
		if ( false !== $term ) {
			wp_delete_term( $term->term_id, '_gallery' );
		}
	}

	/**
	 * Extends live link suggestion post types.
	 *
	 * @static
	 * @access public
	 * @param array $post_types The array of already registered post types.
	 * @return array The array of extended post types.
	 */
	public static function extend_live_link_suggestion_post_types( $post_types ) {
		$post_types[] = self::GALLERY_POST_TYPE;
		return $post_types;
	}

}

GreaterMediaGalleryCPT::init();