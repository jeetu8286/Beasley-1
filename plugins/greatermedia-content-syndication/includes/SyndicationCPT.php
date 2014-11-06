<?php
/**
 * Created by Eduard
 * Date: 06.11.2014
 */

class SyndicationCPT {

	private $post_type = 'subscription';

	public function __construct() {
		add_action( 'init', array( $this, 'register_syndication_cpt' ) );
		add_action( 'admin_menu', array( $this, 'hide_publish_meta' ) );
		add_action( 'admin_head-post.php', array( $this, 'hide_publishing_actions' ) );
		add_action( 'admin_head-post-new.php', array( $this, 'hide_publishing_actions' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'custom_publish_meta' ) );
		add_action( 'save_post', array( $this, 'save' ) );
		add_filter( 'gettext', array( $this, 'change_publish_button' ), 10, 2 );
	}

	/**
	 * Registers a new post type for syndications
	 */
	public function register_syndication_cpt() {

		$labels = array(
			'name'                => __( 'Subscriptions', 'greatermedia' ),
			'singular_name'       => __( 'Subscription', 'greatermedia' ),
			'add_new'             => _x( 'Add New Subscription', 'greatermedia', 'greatermedia' ),
			'add_new_item'        => __( 'Add New Subscription', 'greatermedia' ),
			'edit_item'           => __( 'Edit Subscription', 'greatermedia' ),
			'new_item'            => __( 'New Subscription', 'greatermedia' ),
			'view_item'           => __( 'View Subscription', 'greatermedia' ),
			'search_items'        => __( 'Search Subscriptions', 'greatermedia' ),
			'not_found'           => __( 'No Subscriptions found', 'greatermedia' ),
			'not_found_in_trash'  => __( 'No Subscriptions found in Trash', 'greatermedia' ),
			'parent_item_colon'   => __( 'Parent Syndication:', 'greatermedia' ),
			'menu_name'           => __( 'Subscriptions', 'greatermedia' ),
		);

		$args = array(
			'labels'                => $labels,
			'hierarchical'        => false,
			'description'         => 'description',
			'taxonomies'          => array(),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 75,
			'menu_icon'           => 'dashicons-rss',
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => true,
			'capability_type'     => 'post',
			'supports'            => array(
				'title'
			)
		);

		register_post_type( $this->post_type, $args );
	}

	public function hide_publishing_actions(){
		global $post;
		if( $post->post_type == $this->post_type ){
			echo '
                <style type="text/css">
                    .misc-pub-section:not(.misc-pub-section-last),
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
		}
	}

	/**
	 * Change publish button text
	 */
	public function change_publish_button( $translation, $text ) {
		global $typenow;

		if( $typenow == $this->post_type ) {
			if( $text == 'Publish' || $text == 'Update' ) {
				return 'Save Changes';
			}
		}

		return $translation;
	}

	/**
	 * Hide default publish metabox
	 */
	public function hide_publish_meta() {
		remove_meta_box( 'submitdiv', $this->post_type, 'normal' );
	}

	public function add_meta_box() {
		add_meta_box(
			'submitdiv'
			,__( 'Subscription control' )
			,'post_submit_meta_box'
			,$this->post_type
			,'advanced'
			,'high'
		);

		add_meta_box(
			'filter_metaboxes'
			,__( 'Filters', 'greatermedia' )
			,array( $this, 'render_filter_metabox' )
			,$this->post_type
			,'advanced'
			,'high'
		);
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['subscription_custom_nonce'] ) )
			return $post_id;

		$nonce = $_POST['subscription_custom_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, plugin_basename(__FILE__) ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		if( isset( $_POST['active_inactive'] ) ) {
			$active = sanitize_text_field( $_POST['active_inactive'] );
		}

		// Update the meta field.
		update_post_meta( $post_id, '_subscription_active', $active  );

	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_filter_metabox( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( plugin_basename(__FILE__), 'subscription_custom_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

		// Display the form, using the current value.
		echo '<label for="myplugin_new_field">';
		_e( 'Description for this field', 'myplugin_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field"';
		echo ' value="' . esc_attr( $value ) . '" size="25" />';
	}

	/**
	 * Custom publish metabox for subscription active/inactive
	 */
	public function custom_publish_meta() {
		global $post;
		if ( get_post_type($post) == $this->post_type ) {
			echo '<div class="misc-pub-section misc-pub-section-last">';
				wp_nonce_field( plugin_basename( __FILE__ ), 'subscription_custom_nonce' );
				$val = get_post_meta( $post->ID, '_subscription_active', true ) ? get_post_meta( $post->ID, '_subscription_active', true ) : 'active';
				echo '<input type="radio" name="active_inactive" id="active_inactive-active" value="active" ' . checked( $val, 'active', false) . ' /> <label for="active_inactive-active" class="select-it">Active</label><br />';
				echo '<input type="radio" name="active_inactive" id="active_inactive-inactive" value="inactive" ' . checked( $val,'inactive', false) . '/> <label for="active_inactive-inactive" class="select-it">Inactive</label>';
			echo '</div>';
		}
	}
}


$SyndicationCPT = new SyndicationCPT();