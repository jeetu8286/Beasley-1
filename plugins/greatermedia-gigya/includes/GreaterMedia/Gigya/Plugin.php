<?php

namespace GreaterMedia\Gigya;

/**
 * The main plugin class for greatermedia-gigya.
 *
 * @package GreaterMedia\Gigya
 */
class Plugin {

	/**
	 * Path to the main plugin file. Used with WordPress helpers like
	 * plugins_url.
	 *
	 * @var string
	 */
	public $plugin_file = null;

	/**
	 * Stores the plugin_file and initializes any dependencies.
	 *
	 * @access public
	 * @param string $plugin_file The path to the main plugin file.
	 */
	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Sets up the initialization hooks for the plugin.
	 *
	 * @access public
	 * @return void
	 */
	public function enable() {
		add_action( 'init', array( $this, 'initialize' ) );
		add_action( 'admin_init', array( $this, 'initialize_admin' ) );
	}

	/**
	 * Initializes the CPT for the plugin.
	 *
	 * @access public
	 * @return void
	 */
	public function initialize() {
		$this->member_query_post_type = new MemberQueryPostType();
		$this->member_query_post_type->register();

		$this->contest_post_type = new ContestPostType();

		$session_data = array(
			'data' => array(
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'register_account_nonce' => wp_create_nonce( 'register_account' ),
				'gigya_login_nonce'      => wp_create_nonce( 'gigya_login' ),
				'gigya_logout_nonce'     => wp_create_nonce( 'gigya_logout' ),
				'cid'                    => get_gigya_user_id(),
			)
		);

		// TODO: figure out if session code should live in this plugin
		wp_enqueue_script( 'jquery' );
		wp_localize_script( 'jquery', 'gigya_session_data', $session_data );

		/* Lazy register ajax handlers only if this is an ajax request */
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->register_ajax_handlers();
		}
	}

	public function initialize_admin() {
		add_action( 'add_meta_boxes_member_query', array( $this, 'initialize_member_query_meta_boxes' ) );
		add_action( 'add_meta_boxes_contest', array( $this, 'initialize_contest_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'did_save_post' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'show_flash' ) );

		$form_entry_publisher = new FormEntryPublisher();
		$form_entry_publisher->enable();
	}

	/**
	 * Registers the ajax handlers for this plugin.
	 *
	 * @access public
	 * @return void
	 */
	public function register_ajax_handlers() {
		$handlers   = array();

		$handlers[] = new Ajax\GigyaLoginAjaxHandler();
		$handlers[] = new Ajax\GigyaLogoutAjaxHandler();
		$handlers[] = new Ajax\PreviewAjaxHandler();
		$handlers[] = new Ajax\RegisterAjaxHandler();
		$handlers[] = new Ajax\ListEntryTypesAjaxHandler();
		$handlers[] = new Ajax\ListEntryFieldsAjaxHandler();

		foreach ( $handlers as $handler ) {
			$handler->register();
		}
	}

	/**
	 * Registers the member query post_type metaboxes.
	 *
	 * @access public
	 * @param WP_Post $post The current post object
	 * @return void
	 */
	public function initialize_member_query_meta_boxes( $post ) {
		$member_query = new MemberQuery( $post->ID );
		$this->member_query_post_type->register_meta_boxes( $member_query );

		$this->initialize_member_query_scripts( $member_query );
		$this->initialize_member_query_styles( $member_query );
	}

	/**
	 * Registers the contest post_type metaboxes.
	 *
	 * @access public
	 * @param WP_Post $post The current post object
	 * @return void
	 */
	public function initialize_contest_meta_boxes( $post ) {
		$data = array(
			'forms'           => \RGFormsModel::get_forms( true ),
			'post'            => $post,
			'post_id'         => $post->ID,
			'contest_form_id' => get_post_meta( $post->ID, 'contest_form_id', true ),
		);
		$this->contest_post_type->register_meta_boxes( $data );

		$this->enqueue_script( 'select2', 'js/vendor/select2.js' );
		$this->enqueue_script( 'contest_form_select', 'js/contest_form_select.js', 'select2' );

		$this->enqueue_style( 'select2', 'css/vendor/select2.css' );
		$this->enqueue_style( 'contest_form_select', 'css/contest_form_select.css', 'select2' );
	}

	function initialize_member_query_scripts( $member_query ) {
		wp_dequeue_script( 'autosave' );

		wp_enqueue_script( 'underscore' );
		wp_enqueue_script( 'backbone' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		//$this->enqueue_script( 'select2', 'js/vendor/select2.js' );

		$this->enqueue_script( 'backbone.collectionView', 'js/vendor/backbone.collectionView.js', 'backbone' );
		$this->enqueue_script( 'query_builder', 'js/query_builder.js', array( 'backbone', 'underscore' ) );

		wp_localize_script(
			'query_builder', 'member_query_data', $member_query->properties
		);

		$meta = array(
			'ajax_url'                   => admin_url( 'admin-ajax.php' ),
			'preview_member_query_nonce' => wp_create_nonce( 'preview_member_query' ),
			'list_entry_types_nonce'     => wp_create_nonce( 'list_entry_types' ),
			'list_entry_fields_nonce'    => wp_create_nonce( 'list_entry_fields' ),
		);

		wp_localize_script(
			'query_builder', 'member_query_meta', $meta
		);
	}

	function initialize_member_query_styles( $member_query ) {
		$this->enqueue_style( 'query_builder', 'css/query_builder.css' );
		//$this->enqueue_style( 'select2', 'css/vendor/select2.css' );
	}

	/**
	 * If post was saved then calls member query or contest form helper
	 * functions, else ignores the save.
	 *
	 * @access public
	 * @param int $post_id The id of the parent post CPT of this MemberQuery
	 * @param WP_Post $post The post object that was saved.
	 * @return void
	 */
	public function did_save_post( $post_id, $post = null ) {
		if ( ! is_null( $post ) ) {
			$post_type   = $post->post_type;
			$post_status = $post->post_status;

			if ( $post_status === 'publish' ) {
				switch ( $post_type ) {
					case 'member_query':
						return $this->publish_member_query( $post_id, $post );

					case 'contest':
						return $this->update_form_for_contest( $post_id, $post );

				}
			}
		}
	}

	/**
	 * Saves the MemberQuery JSON in postmeta and then publishes the
	 * segment to MailChimp.
	 *
	 * If constraints not present in POST assumed to be a quick-edit and
	 * does not update the member query.
	 *
	 * @access public
	 * @param int $post_id The id of the parent post CPT of this MemberQuery
	 * @param WP_Post $post The post object that was saved.
	 * @return void
	 */
	public function publish_member_query( $post_id, $post = null ) {
		if ( ! array_key_exists( 'constraints', $_POST ) ) {
			return;
		}

		$this->member_query_post_type->verify_meta_box_nonces();

		try {
			$member_query = new MemberQuery( $post_id );
			$member_query->build_and_save();

			//$segment_publisher = new SegmentPublisher( $member_query );
			//$segment_publisher->publish();
		} catch ( \Exception $e ) {
			$this->set_flash( $e->getMessage() );
		}
	}

	public function update_form_for_contest( $post_id, $post ) {
		$this->contest_post_type->verify_meta_box_nonces();

		$contest_form_id = intval( $_POST['contest_form_id'] );
		$key             = 'contest_form_id';

		// TODO: validate if gform exists?
		if ( is_int( $contest_form_id ) ) {
			update_post_meta( $post_id, $key, $contest_form_id );
		} else {
			delete_post_meta( $post_id, $key );
		}
	}

	/* helpers */
	public function show_flash() {
		$flash = $this->get_flash();
		if ( $flash !== false ) {
			include GMR_GIGYA_PATH . '/templates/flash.php';
			$this->clear_flash();
		}
	}

	public function get_flash_key() {
		return get_current_user_id() . '_member_query_flash';
	}

	public function set_flash( $message ) {
		set_transient( $this->get_flash_key(), $message, 30 );
	}

	public function get_flash() {
		return get_transient( $this->get_flash_key() );
	}

	public function clear_flash() {
		delete_transient( $this->get_flash_key() );
	}

	public function enqueue_script( $id, $path, $dependency = null ) {
		if ( ! is_null( $dependency ) ) {
			if ( is_array( $dependency ) ) {
				$dependencies = $dependency;
			} else {
				$dependencies = array( $dependency );
			}
		} else {
			$dependencies = array();
		}

		wp_enqueue_script(
			$id,
			plugins_url( $path, $this->plugin_file ),
			$dependencies,
			GMR_GIGYA_VERSION
		);
	}

	public function enqueue_style( $id, $path, $dependency = null ) {
		if ( ! is_null( $dependency ) ) {
			if ( is_array( $dependency ) ) {
				$dependencies = $dependency;
			} else {
				$dependencies = array( $dependency );
			}
		} else {
			$dependencies = array();
		}

		wp_enqueue_style(
			$id,
			plugins_url( $path, $this->plugin_file ),
			$dependencies,
			GMR_GIGYA_VERSION
		);
	}
}
