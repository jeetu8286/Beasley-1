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
	 * List of currently registered meta boxes.
	 *
	 * @var array
	 */
	public $meta_boxes = array();

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
		add_action( 'add_meta_boxes_member_query', array( $this, 'initialize_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'publish_member_query' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'show_flash' ) );

		$preview_ajax_handler = new PreviewAjaxHandler();
		$preview_ajax_handler->register();

		$register_ajax_handler = new RegisterAjaxHandler();
		$register_ajax_handler->register();

		$gigya_login_ajax_handler = new GigyaLoginAjaxHandler();
		$gigya_login_ajax_handler->register();

		$gigya_logout_ajax_handler = new GigyaLogoutAjaxHandler();
		$gigya_logout_ajax_handler->register();

		$form_entry_publisher = new FormEntryPublisher();
		$form_entry_publisher->enable();
	}

	/**
	 * Initializes the CPT for the plugin.
	 *
	 * @access public
	 * @return void
	 */
	public function initialize() {
		$member_query_post_type = new MemberQueryPostType();
		$member_query_post_type->register();

		$session_data = array(
			'data' => array(
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'register_account_nonce' => wp_create_nonce( 'register_account' ),
				'gigya_login_nonce'      => wp_create_nonce( 'gigya_login' ),
				'gigya_logout_nonce'     => wp_create_nonce( 'gigya_logout' ),
				'cid'                    => gigya_user_id()
			)
		);

		// TODO: figure out if session code should live in this plugin
		wp_enqueue_script( 'jquery' );
		wp_localize_script( 'jquery', 'gigya_session_data', $session_data );
	}

	/**
	 * Registers the metaboxes for the plugin.
	 *
	 * @access public
	 * @param WP_Post $post The current post object
	 * @return void
	 */
	public function initialize_meta_boxes( $post ) {
		$member_query = new MemberQuery( $post->ID );
		$meta_boxes   = $this->get_meta_boxes( $member_query );

		foreach ( $meta_boxes as $meta_box ) {
			$meta_box->register();
		}

		$this->initialize_scripts( $member_query );
		$this->initialize_styles( $member_query );
	}

	function initialize_scripts( $member_query ) {
		wp_dequeue_script( 'autosave' );
		wp_enqueue_script(
			'query_builder',
			plugins_url( 'js/query_builder.js', $this->plugin_file ),
			array('underscore'),
			GMR_GIGYA_VERSION
		);

		wp_localize_script(
			'query_builder', 'member_query_data', $member_query->properties
		);

		$meta = array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'preview_nonce' => wp_create_nonce( 'preview_member_query' )
		);

		wp_localize_script(
			'query_builder', 'member_query_meta', $meta
		);
	}

	function initialize_styles( $member_query ) {
		wp_enqueue_style(
			'gmr_gigya',
			plugins_url( 'css/gmr_gigya.css', $this->plugin_file ),
			array(),
			GMR_GIGYA_VERSION
		);
	}

	/**
	 * Saves the MemberQuery JSON in postmeta and then publishes the
	 * segment to MailChimp.
	 *
	 * @access public
	 * @param int $post_id The id of the parent post CPT of this MemberQuery
	 * @param WP_Post $post The post object that was saved.
	 * @return void
	 */
	public function publish_member_query( $post_id, $post = null ) {
		if ( ! is_null( $post ) && $post->post_type === 'member_query' && $post->post_status === 'publish' ) {
			$this->verify_meta_box_nonces();

			try {
				$member_query = new MemberQuery( $post_id );
				$member_query->build_and_save();

				$segment_publisher = new SegmentPublisher( $member_query );
				$segment_publisher->publish();
			} catch ( \Exception $e ) {
				$this->set_flash( $e->getMessage() );
			}
		}
	}

	public function show_flash() {
		$flash = $this->get_flash();
		if ( $flash !== false ) {
			include GMR_GIGYA_PATH . '/templates/flash.php';
			$this->clear_flash();
		}
	}

	/* helpers */
	/**
	 * Lazy initializes the meta boxes for this plugin. This keeps the
	 * footprint down on the POST request, since we don't need to
	 * register the meta boxes there.
	 *
	 * For the POST request, we'll use null member_query. For those
	 * requests the meta boxes only do nonce verification.
	 *
	 * @access public
	 * @param MemberQuery $member_query
	 * @return array Associative array of meta box objects
	 */
	public function get_meta_boxes( $member_query = null ) {
		if ( count( $this->meta_boxes ) === 0 ) {
			$this->meta_boxes = array();

			$this->meta_boxes['preview'] = $this->meta_box_for(
				array(
					'id'       => 'preview',
					'title'    => __( 'Preview Results', 'gmr_gigya' ),
					'context'  => 'side',
					'priority' => 'default',
					'template' => 'preview',
				),
				$member_query
			);

			$this->meta_boxes['direct_query'] = $this->meta_box_for(
				array(
					'id'       => 'direct_query',
					'title'    => __( 'Direct Query', 'gmr_gigya' ),
					'context'  => 'side',
					'priority' => 'low',
					'template' => 'direct_query',
				),
				$member_query
			);

			$this->meta_boxes['query_builder'] = $this->meta_box_for(
				array(
					'id'       => 'query_builder',
					'title'    => __( 'Gigya Social', 'gmr_gigya' ),
					'context'  => 'normal',
					'priority' => 'default',
					'template' => 'query_builder',
				),
				$member_query
			);
		}

		return $this->meta_boxes;
	}

	/**
	 * Builds a new meta box for the specified params.
	 *
	 * @access public
	 * @param array $params The params to pass to the meta box object
	 * @param MemberQuery $member_query The member query associated with the meta box.
	 * @return MetaBox
	 */
	public function meta_box_for( $params, $member_query ) {
		$meta_box = new MetaBox( $member_query );
		$meta_box->params = $params;

		return $meta_box;
	}

	/**
	 * Verifies than correct nonces were passed for each MetaBox.
	 *
	 * Exits script execution with a warning if invalid.
	 *
	 * @access public
	 * @return void
	 */
	public function verify_meta_box_nonces() {
		$meta_boxes = $this->get_meta_boxes( null );

		foreach ( $meta_boxes as $meta_box ) {
			$meta_box->verify_nonce();
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
}
