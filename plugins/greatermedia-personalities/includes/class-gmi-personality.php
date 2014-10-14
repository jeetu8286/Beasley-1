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
		const SHADOW_TAX_SLUG = '_gmi_personality';

		/**
		 * Default personalities constructor.
		 */
		protected function __construct() {
			add_action( 'init', array( __CLASS__, 'register_personality_cpt' ) );
			add_action( 'init', array( __CLASS__, 'register_personality_shadow_taxonomy' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_personality_info_meta_box' ) );
			add_action( 'save_post', array( __CLASS__, 'save_personality_cpt_meta_boxes' ) );
			add_action( 'save_post', array( __CLASS__, 'update_personality_shadow_taxonomy' ) );
			add_action( 'before_delete_post', array( __CLASS__, 'delete_personality_shadow_tax_term' ) );
			add_filter( 'manage_' . self::CPT_SLUG . '_posts_columns', array( __CLASS__, 'custom_columns' ) );
			add_action( 'manage_' . self::CPT_SLUG . '_posts_custom_column', array( __CLASS__, 'custom_columns_content' ), 1, 2 );
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
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
			);

			register_post_type( self::CPT_SLUG, $args );
		}

		/**
		 * Register shadow taxonomy for the Personality CPT. The terms are kept in sync with the personalities added.
		 *
		 * @return [type] [description]
		 */
		public static function register_personality_shadow_taxonomy() {
			$labels = array(
				'name'              => __( 'Personalities', 'gmi_personality' ),
				'singular_name'     => __( 'Personality', 'gmi_personality' ),
				'search_items'      => __( 'Search', 'gmi_personality' ),
				'all_items'         => __( 'All Personalities', 'gmi_personality' ),
				'parent_item'       => __( 'Parent Personality: ', 'gmi_personality' ),
				'parent_item_colon' => __( 'Parent Personality: ', 'gmi_personality' ),
				'edit_item'         => __( 'Edit Personality', 'gmi_personality' ),
				'update_item'       => __( 'Update Personality', 'gmi_personality' ),
				'add_new_item'      => __( 'Add New Personality', 'gmi_personality' ),
				'new_item_name'     => __( 'New Personality Name', 'gmi_personality' ),
				'menu_name'         => __( 'Personalities', 'gmi_personality' ),
			);

			$args = array(
				'labels'				=> $labels,
				'rewrite'				=> array(
					'slug' 				=> 'personalities'
				),
				'show_ui'       		=> true,
				'show_in_menu'  		=> true,
				'show_admin_column'  	=> true,
				'show_tagcloud'			=> true,
				'hierarchical'  		=> false,
			);

			register_taxonomy( self::SHADOW_TAX_SLUG, array( 'post' ), $args );
		}

		/**
		 * Adds the meta box container for personality info.
		 *
		 * @param int $post_type The post ID
		 */
		public static function add_personality_info_meta_box( $post_type ) {

			$post_types = array( self::CPT_SLUG );

			if ( in_array( $post_type, $post_types ) ) {
				add_meta_box(
					'personality_info_meta_box'
					, __( 'Personality Info', self::CPT_SLUG )
					, array( __CLASS__, 'render_personality_info_meta_box_content' )
					, $post_type
					, 'advanced'
					, 'high'
				);
			}
		}

		/**
		 * Render Meta Box content for personalities.
		 *
		 * @param  WP_POST $post The post object.
		 */
		public static function render_personality_info_meta_box_content( $post ) {
			// Use get_post_meta to retrieve an existing value from the database.
			$personality_assoc_user_id = get_post_meta( $post->ID, '_personality_assoc_user_id', true );
			$user_info = get_userdata( $personality_assoc_user_id );

			$facebook_url = get_post_meta( $post->ID, '_personality_facebook_url', true );
			$twitter_url = get_post_meta( $post->ID, '_personality_twitter_url', true );


			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'personality_info_meta_box', 'personality_info_meta_box_nonce' );

			$user_select_args = array(
				'show_option_none'	=> __( 'None', 'gmi_personality' ),
				'name'				=> 'personality_assoc_user_id',
				'selected'			=> intval( $personality_assoc_user_id ),
			);
		?>
			<div id="personality-info" class="personality-meta">
				<div class="personality-meta-row">
					<label for="personality_assoc_user_id"
					       class="personality-meta-label"><?php esc_html_e( 'Associated User', 'gmi_personality' ); ?></label>
		<?php
					wp_dropdown_users( $user_select_args );
		?>
				</div>

				<div class="personality-meta-row">
					<label for="personality_facebook_url"
					       class="personality-meta-label"><?php esc_html_e( 'Facebook URL', 'gmi_personality' ); ?></label>
					<input type="text" id="personality_facebook_url" name="personality_facebook_url"
					       placeholder="<?php esc_attr_e( 'Facebook URL', 'gmi_personality' ); ?>"
					       value="<?php echo esc_url( $facebook_url ); ?>"/>
				</div>

				<div class="personality-meta-row">
					<label for="personality_twitter_url"
					       class="personality-meta-label"><?php esc_html_e( 'Twitter URL', 'gmi_personality' ); ?></label>
					<input type="text" id="personality_twitter_url" name="personality_twitter_url"
					       placeholder="<?php esc_attr_e( 'Twitter URL', 'gmi_personality' ); ?>"
					       value="<?php echo esc_url( $twitter_url ); ?>"/>
				</div>

				<div class="personality-meta-row cf">
					<label class="personality-meta-label"><?php esc_html_e( 'Photo', 'gmi_personality' ); ?></label>

					<div class="personality_thumbnail">
		<?php
					if ( has_post_thumbnail( $post->ID ) ) {
						the_post_thumbnail( array( 50, 50 ) );
					} else if ( isset( $user_info->user_email ) ) {
						echo get_avatar( $user_info->user_email, 45 );
					}
		?>
					</div>


					<div class="personality-info">
						<?php esc_html_e( 'If an associated user is chosen above, their photo will be shown by default. You can show a different photo by using the the "Set Featured Image" link in the "Featured Image" box on the right side of this page.', 'gmi_personality' ); ?>
					</div>
				</div>
			</div>
		<?php

		}

		/**
		 * Save personality meta boxes.
		 *
		 * @param  int $post_id The post ID
		 */
		public static function save_personality_cpt_meta_boxes( $post_id ) {
			if ( self::CPT_SLUG != get_post_type( $post_id ) || empty( $_POST ) || ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			{
				return $post_id;
			}

			if ( ! isset( $_POST['personality_info_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['personality_info_meta_box_nonce' ], 'personality_info_meta_box' ) ) {
				return;
			}

			if ( -1 === intval( $_POST['personality_assoc_user_id'] ) ) {
				delete_post_meta( $post_id, '_personality_assoc_user_id' );
			} else {
				$user_id = absint( $_POST['personality_assoc_user_id'] );
				update_post_meta( $post_id, '_personality_assoc_user_id', $user_id );
			}

			update_post_meta( $post_id, '_personality_facebook_url', esc_url_raw( $_POST['personality_facebook_url'] ) );
			update_post_meta( $post_id, '_personality_twitter_url', esc_url_raw( $_POST['personality_twitter_url'] ) );
		}

		/**
		 * Keep the personlity shadow taxonomy and CPT in sync.
		 *
		 * @param  int $post_id The post being saved
		 */
		public static function update_personality_shadow_taxonomy( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( self::CPT_SLUG !== get_post_type( $post_id ) ) {
				return;
			}

			$personality = get_post( $post_id );

			if ( null === $personality ) {
				return;
			}

			if ( 'publish' != $personality->post_status ) {
				return;
			}

			$term = get_term_by( 'slug', $personality->post_name, self::SHADOW_TAX_SLUG );

			if ( false === $term ) {
				$args = array();

				// See if there is an existing post_tag with the same slug as the personality. We can't trust WordPress to do this in wp_insert_term() because it will think "This Is A Title" and "This is a Title" (small "is" and "a") are different tags.
				$exising_term = get_term_by( 'slug', $personality->post_name, 'post_tag' );

				if ( false === $exising_term) {
					wp_insert_term( $personality->post_title, self::SHADOW_TAX_SLUG, $args );
				} else {
					// If there is an existing term in post_tag, use its name instead of the personality's title. This bypasses any weird matching issues in wp_insert_term();
					wp_insert_term( $exising_term->name, self::SHADOW_TAX_SLUG, $args );
				}
			}
		}

		/**
		 * Delete the matching shadow taxonomy term when the entry in the CPT is deleted.
		 *
		 * @param  int $post_id The post ID being deleted
		 */
		public static function delete_personality_shadow_tax_term( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( self::CPT_SLUG !== get_post_type( $post_id ) ) {
				return;
			}

			$personality = get_post( $post_id );

			if ( null === $personality ) {
				return;
			}

			$term = get_term_by( 'slug', $personality->post_name, self::SHADOW_TAX_SLUG );

			if ( false !== $term ) {
				wp_delete_term( $term->term_id, self::SHADOW_TAX_SLUG );
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
			$assoc_user_id = get_post_meta( $post_id, '_personality_assoc_user_id', true );

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
		 * Enqueue supporting admin scripts.
		 */
		public static function admin_enqueue_scripts() {
			$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

			if ( self::CPT_SLUG === get_post_type() )
			{
				wp_enqueue_style( 'personality-admin', GMI_PERSONALITY_URL . "assets/css/greater_media_personalities_admin{$postfix}.css", array(), GMI_PERSONALITY_VERSION, 'all' );
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
