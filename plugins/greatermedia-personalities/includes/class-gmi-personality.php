<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

if ( !class_exists( "GMI_Personality" ) ) {

	/**
	 * Personalities
	 *
	 * Creates a CPT and taxonomy for personalities.
	 */
	class GMI_Personality {
		const CPT_SLUG = 'gmi_personality';

		/**
		 * Default personalities constructor.
		 */
		protected function __construct() {
			add_action( 'init', array( __CLASS__, 'register_personality_cpt' ) );
			add_action( 'save_post', array( __CLASS__, 'save_cpt_meta_boxes' ) );
			add_filter( 'manage_' . self::CPT_SLUG . '_posts_columns', array( __CLASS__, 'custom_columns' ) );
			add_action( 'manage_' . self::CPT_SLUG . '_posts_custom_column', array( __CLASS__, 'custom_columns_content' ), 1, 2 );
		}

		/**
		 * Registers the Personalities CPT.
		 */
		public static function register_personality_cpt() {
			$labels = array(
				'name'					=> __( 'Personalities', 'gmi_personality' ),
				'singular_name'			=> __( 'Personality', 'gmi_personality' ),
				'add_new'				=> __( 'Add New Personality', 'gmi_personality' ),
				'add_new_item'			=> __( 'Add New Personality', 'gmi_personality' ),
				'new_item'				=> __( 'New Personality', 'gmi_personality' ),
				'edit_item'				=> __( 'Edit Personality', 'gmi_personality' ),
				'view_item'				=> __( 'View Personality', 'gmi_personality' ),
				'all_items'				=> __( 'All Personalities', 'gmi_personality' ),
				'search_items'			=> __( 'Search Personalities', 'gmi_personality' ),
				'not_found'				=> __( 'No personalities found', 'gmi_personality' ),
				'not_found_in_trash'	=> __( 'No personalities found in Trash', 'gmi_personality' ),
				'parent_item_colon'		=> __( 'Parent Personality: ', 'gmi_personality' ),
				'menu_name'				=> 'Personalities',
			);

			$args = array(
				'labels'				=> $labels,
				'description'			=> 'Personalities on the site',
				'public'				=> true,
				'publicly_queryable'	=> true,
				'show_ui'				=> true,
				'show_in_menu'			=> true,
				'query_var'				=> true,
				'rewrite'				=> array( 'slug' => 'personalities' ),
				'capability_type'		=> 'post',
				'has_archive'			=> true,
				'hierarchical'			=> false,
				'menu_icon'				=> 'dashicons-groups',
				'supports'				=> array(
												'title',
												'editor',
												'thumbnail',
												'revisions',
												'page-attributes'
											),
				'register_meta_box_cb'	=> array( __CLASS__, 'add_cpt_meta_boxes' ),
			);

			register_post_type( self::CPT_SLUG, $args );
		}

		/**
		 * Add meta boxes for the Personality CPT.
		 */
		public static function add_cpt_meta_boxes() {
			add_meta_box( 'personality_cpt_meta_boxes', 'Associated User', array( __CLASS__, 'print_cpt_meta_boxes' ), self::CPT_SLUG, 'side' );
		}

		/**
		 * Prints meta box for the personality's associated user.
		 *
		 * @param  WP_Post $post The current post
		 */
		public static function print_cpt_meta_boxes( $post ) {
			$personality_assoc_user_id = get_post_meta( $post->ID, 'personality_assoc_user_id', true );

			// Control our own nonce
			wp_nonce_field( 'save_staff_metaboxes', 'staff_metabox_nonce', true );

			$args = array(
				'show_option_none'	=> __( 'None', 'gmi_personality' ),
				'name'				=> 'personality_assoc_user_id',
				'selected'			=> intval( $personality_assoc_user_id ),
			);

			wp_dropdown_users( $args );
		}

		/**
		 * Save personality meta boxes.
		 *
		 * @param  int $post_id The post ID
		 */
		public static function save_cpt_meta_boxes( $post_id ) {
			if ( self::CPT_SLUG != get_post_type( $post_id ) || empty( $_POST ) || ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			{
				return $post_id;
			}

			if ( ! wp_verify_nonce( $_POST['staff_metabox_nonce'], 'save_staff_metaboxes' ) )
			{
				return $post_id;
			}

			if ( -1 === intval( $_POST['personality_assoc_user_id'] ) ) {
				delete_post_meta( $post_id, 'personality_assoc_user_id' );
			} else {
				$user_id = absint( $_POST['personality_assoc_user_id'] );
				update_post_meta( $post_id, 'personality_assoc_user_id', $user_id );
			}
		}

		/**
		 * Add custom columns to the Personality CPT list.
		 *
		 * @param  array $columns The default columns
		 * @return array
		 */
		public static function custom_columns( $columns ) {
			unset( $columns['date'] );

			// Add a few custom columns
			$columns = array_merge( $columns, array(
					'title'  => 'Name',
					'email'  => 'Email',
					'avatar' => 'Portrait',
			) );

			return $columns;

		}

		/**
		 * Print the custom columns
		 *
		 * @param  string $column_name		The column name
		 * @param  int $post_id				The post ID
		 */
		public static function custom_columns_content( $column_name, $post_id ) {
			$assoc_user_id = get_post_meta( $post_id, 'personality_assoc_user_id', true );

			if ( empty( $assoc_user_id ) ) {
				return;
			}

			$user_info = get_userdata( $assoc_user_id );

			switch ( $column_name ) {
				case( 'title' ):
					if ( isset( $user_info->display_name ) )
						echo sanitize_text_field( $user_info->display_name );
					break;
				case( 'email' ):
					if ( isset( $user_info->user_email ) )
						echo '<a href="' . esc_url( 'mailto:' . $user_info->user_email ) . '">' . sanitize_email( $user_info->user_email ) . '</a>';
					break;
				case( 'avatar' ):
					if ( has_post_thumbnail( $post_id ) ) {
						the_post_thumbnail( array( 50, 50 ) );
					} else if ( isset( $user_info->user_email ) ) {
						echo get_avatar( $user_info->user_email, 45 );
					}
					break;
				default:
					break;
			}
		}

		/**
		 * Class initialization function.
		 *
		 * @return GMI_Personality|mixed
		 */
		public static function init() {
			return new static();
		}
	}

	$gmr_personalities = GMI_Personality::init();
}
