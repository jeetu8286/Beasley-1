<?php
/**
 * Establishes the custom post type for editorial staff and related taxonomies
 */
class GMI_Personality {

	const CPT_SLUG = 'gmi_personality';

	public static function hooks() {
		add_action( 'init', array( __CLASS__, 'register_cpt' ) );
		add_action( 'save_post', array( __CLASS__, 'validate_save_metaboxes' ) );
		add_filter( 'manage_' . self::CPT_SLUG . '_posts_columns', array( __CLASS__, 'custom_columns' ) );
		add_action( 'manage_' . self::CPT_SLUG . '_posts_custom_column', array( __CLASS__, 'custom_columns_content' ), 1, 2 );
	}

	public static function register_cpt() {
		$labels = array(
				'name'               => 'Personalities',
				'singular_name'      => 'Personality',
				'add_new'            => 'Add New Personality',
				'all_items'          => 'All Personalities',
				'add_new_item'       => 'Add New Personality',
				'edit_item'          => 'Edit Personality',
				'new_item'           => 'New Personality',
				'view_item'          => 'View Personality',
				'search_items'       => 'Search Personalities',
				'not_found'          => 'No personalities found',
				'not_found_in_trash' => 'No personalities found in trash',
				'parent_item_colon'  => 'Parent Personality:',
				'menu_name'          => 'Personalities'
		);

		$args = array(
				'labels'               => $labels,
				'description'          => 'Personalities on the site',
				'public'               => true,
				'supports'             => array( 'title', 'editor', 'thumbnail' ),
				'rewrite'							 => array( 'slug' => 'personalities' ),
				'register_meta_box_cb' => array( __CLASS__, 'add_metaboxes' ),
				'menu_icon'            => ''
		);

		register_post_type( self::CPT_SLUG, $args );
	}

	public static function add_metaboxes() {
		add_meta_box( 'global_info', 'Global User Info', array( __CLASS__, 'print_global_metabox' ), self::CPT_SLUG, 'normal' );
	}

	public static function print_global_metabox( $post ) {
		$assoc_user = get_post_meta( $post->ID, 'assoc_user', true );

		// Control our own nonce
		wp_nonce_field( 'save_staff_metaboxes', 'staff_metabox_nonce', true ); ?>

		<table class="form-table">
			<tr>
				<th scope="row"><label for="assoc_user">Associated Blog User</label>
				</th>
				<td><select id="assoc_user" name="assoc_user">
						<option value="">Select a User</option>
						<?php
						$authors = get_users( array(
								'who' => 'authors'
						) );
						foreach ( $authors as $author ): ?>
							<option <?php selected( $assoc_user, $author->ID ); ?> value="<?php echo esc_attr( $author->ID ); ?>">
								<?php echo esc_attr( $author->display_name ); ?> ( <?php echo esc_attr( $author->user_login ); ?> )
							</option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
<?php
	}

	public static function validate_save_metaboxes( $post_id ) {
		if ( self::CPT_SLUG != get_post_type( $post_id ) || empty( $_POST ) || ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( ! wp_verify_nonce( $_POST['staff_metabox_nonce'], 'save_staff_metaboxes' ) )
			return $post_id;

		/*
		 * Associated User Metabox
		 */
		if ( empty( $_POST['assoc_user'] ) ) {
			delete_post_meta( $post_id, 'assoc_user' );
			$assoc_user = false;
		} else {
			update_post_meta( $post_id, 'assoc_user', absint( $_POST['assoc_user'] ) );
			$assoc_user = absint( $_POST['assoc_user'] );
		}

		return $post_id;
	}

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

	public static function custom_columns_content( $column_name, $post_id ) {

		$assoc_user = get_post_meta( $post_id, 'assoc_user', true );
		$userinfo   = get_userdata( $assoc_user );
		switch ( $column_name ) {
			case( 'title' ):
				if ( isset( $userinfo->display_name ) )
					echo sanitize_text_field( $userinfo->display_name );
				break;
			case( 'email' ):
				if ( isset( $userinfo->user_email ) )
					echo '<a href="' . esc_url( 'mailto:' . $userinfo->user_email ) . '">' . sanitize_email( $userinfo->user_email ) . '</a>';
				break;
			case( 'avatar' ):
				if ( has_post_thumbnail( $post_id ) ) {
					the_post_thumbnail( array( 50, 50 ) );
				} else if ( isset( $userinfo->user_email ) ) {
					echo get_avatar( $userinfo->user_email, 45 );
				}
				break;
		}
	}

}