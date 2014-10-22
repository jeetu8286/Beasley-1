<?php
/**
 * Created by Eduard
 * Date: 15.10.2014
 */

class Shows_Meta_Box {

	public  $id;
	public  $title;
	public  $fields;
	public  $page;
	public  $context;
	public  $priority;

	public function __construct() {

	}
	public function init( $id, $title, $fields, $page, $context, $priority ) {
		$this->id       = $id;
		$this->title    = $title;
		$this->fields   = $fields;
		$this->page     = $page;
		$this->context  = $context;
		$this->priority = $priority;

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
		add_action( 'save_post',  array( $this, 'save_box' ));

	}

	/**
	 * enqueue necessary scripts and styles
	 */
	public function admin_enqueue_scripts() {
		global $pagenow;
		if ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) && get_post_type() == $this->page ) {
			wp_enqueue_script( 'meta_box', GMEDIA_SHOWS_URL . 'assets/js/src/scripts.js' );
			wp_enqueue_style( 'meta_box', GMEDIA_SHOWS_URL . 'assets/css/src/meta_box.css' );
		}
	}

	/**
	 * adds the meta box for every post type in $page
	 */
	public function add_box() {
		add_meta_box( $this->id, $this->title, array( $this, 'meta_box_callback' ), $this->page, $this->context, $this->priority );
	}

	/**
	 * outputs the meta box
	 */
	public function meta_box_callback() {
		// Use nonce for verification
		wp_nonce_field( 'custom_meta_box_nonce_action', 'custom_meta_box_nonce_field' );

		// Begin the field table and loop
		echo '<table class="form-table meta_box">';
		foreach ( $this->fields as $field) {
			echo '<tr>
					<th><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
					<td>';
			$meta = get_post_meta( get_the_ID(), $field['id'], true);
			echo $this->custom_meta_box_field( $field, $meta, false );
			echo     '<td></tr>';
		} // end foreach
		echo '</table>'; // end table
	}

	/**
	 * saves the captured data
	 */
	public function save_box( $post_id ) {
		$post_type = get_post_type();
		$post_title = get_the_title();

		// verify nonce
		if ( ! isset( $_POST['custom_meta_box_nonce_field'] ) ) {
			return $post_id;
		}

		if ( $post_type != $this->page || !wp_verify_nonce( $_POST['custom_meta_box_nonce_field'],  'custom_meta_box_nonce_action' ) ) {
			return $post_id;
		}
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// check permissions
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}

		foreach ( $this->fields as $field ) {
				$new = false;
				$old = get_post_meta( $post_id, $field['id'], true );
				if ( isset( $_POST[$field['id']] ) ) {
					$new = $_POST[$field['id']];
				}
				if ( isset( $new ) && $new != $old ) {
					update_post_meta( $post_id, $field['id'], $new );

					if( class_exists('ShowsCPT') && $field['id'] == 'show_homepage' && $new ) {
						$term_id = ShowsCPT::createShadowTerm( $post_title );
						wp_set_post_terms($post_id, $term_id, 'shows_shadow_taxonomy' );
						update_post_meta( $post_id, '_related_term_id', $term_id );
					}
				}
		}
	}

	public function custom_meta_box_field( $field, $meta = null, $repeatable = null, $type = null ) {
		if ( ! ( $field || is_array( $field ) ) )
			return;

		// get field data
		$label = isset( $field['label'] ) ? $field['label'] : null;
		$desc = isset( $field['desc'] ) ? '<span class="description">' . $field['desc'] . '</span>' : null;
		$place = isset( $field['place'] ) ? $field['place'] : null;
		$size = isset( $field['size'] ) ? $field['size'] : null;
		$post_type = isset( $field['post_type'] ) ? $field['post_type'] : null;
		$options = isset( $field['options'] ) ? $field['options'] : null;
		$settings = isset( $field['settings'] ) ? $field['settings'] : null;
		$type = isset( $field['type'] ) ? $field['type'] : null;

		// the id and name for each field
		$id = $name = isset( $field['id'] ) ? $field['id'] : null;
		if ( $repeatable ) {
			$name = $repeatable[0] . '[' . $repeatable[1] . '][' . $id .']';
			$id = $repeatable[0] . '_' . $repeatable[1] . '_' . $id;
		}
		switch( $type ) {
			// checkbox
			case 'checkbox':
				echo '<input type="checkbox" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" ' . checked( $meta, true, false ) . ' value="1" />
						<label for="' . esc_attr( $id ) . '">' . $desc . '</label>';
				break;

			case 'image':
				// image
				$image = '';
				echo '<div class="meta_box_image"><span class="meta_box_default_image" style="display:none">' . $image . '</span>';
				if ( $meta ) {
					$image = wp_get_attachment_image_src( intval( $meta ), 'medium' );
					$image = $image[0];
				}
				echo '<input name="' . esc_attr( $name ) . '" type="hidden" class="meta_box_upload_image" value="' . intval( $meta ) . '" />
					<img src="' . esc_attr( $image ) . '" class="meta_box_preview_image" alt="" />
					<a href="#" class="meta_box_upload_image_button button" rel="' . get_the_ID() . '">Choose Image</a>
					<small>&nbsp;<a href="#" class="meta_box_clear_image_button">Remove Image</a></small></div>
					<br clear="all" />' . $desc;
				break;
			default:
				echo '<input type="' . $type . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( $meta ) . '" class="regular-text" size="30" />
						<br />' . $desc;
		}
	}

}




$images = array(
	array( // Image ID field
		'label'	=> '', // <label>
		'desc'	=> 'Logo image for show.', // description
		'id'	=> 'logo_image', // field id and name
		'type'	=> 'image' // type of field
	),
);

$image_meta = new Shows_Meta_Box();
$image_meta->init( 'show_logo', 'Logo', $images, 'show', 'side', 'default', true);

$checkbox_meta = new Shows_Meta_Box();
$checkbox = array(
	array( // Image ID field
		'label'	=> '', // <label>
		'desc'	=> 'Whether show is going to have it\'s own page.', // description
		'id'	=> 'show_homepage', // field id and name
		'type'	=> 'checkbox' // type of field
	)
);
$checkbox_meta->init( 'show_homepage', 'Home page?', $checkbox, 'show', 'side', 'default', true);