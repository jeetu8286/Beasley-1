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
		add_action( 'add_meta_boxes', array( $this, 'initialize_meta_boxes' ), 10, 2 );
		add_filter( 'wp_insert_post_data', array( $this, 'serialize_member_query' ), 10, 2 );
		add_action( 'save_post', array( $this, 'publish_member_query' ), 10, 2 );

		$preview_ajax_handler = new PreviewAjaxHandler();
		$preview_ajax_handler->register();
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
	}

	/**
	 * Registers the metaboxes for the plugin.
	 *
	 * @access public
	 * @param string $post_type The current post_type name
	 * @param WP_Post $post The current post object
	 * @return void
	 */
	public function initialize_meta_boxes( $post_type, $post ) {
		if ( $post_type !== 'member_query' ) {
			return;
		}

		$member_query = new MemberQuery( $post );
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
			plugins_url( 'js/query_builder.js?cache=' . strtotime( 'now' ), $this->plugin_file )
		);

		wp_localize_script(
			'query_builder', 'member_query_data', $member_query->properties
		);

		$meta = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'preview_nonce' => wp_create_nonce( 'preview_member_query' )
		);

		wp_localize_script(
			'query_builder', 'member_query_meta', $meta
		);
	}

	function initialize_styles( $member_query ) {
		wp_enqueue_style(
			'gmr_gigya',
			plugins_url( 'css/gmr_gigya.css?cache=' . strtotime( 'now' ), $this->plugin_file )
		);
	}

	/**
	 * Serialize member query before post is saved.
	 */
	function serialize_member_query( $sanitized_data, $raw_data = null ) {
		if ( $sanitized_data['post_type'] !== 'member_query' ) {
			return $sanitized_data;
		}

		$member_query = new MemberQuery( $raw_data );
		foreach ( $this->get_meta_boxes( $member_query ) as $meta_box ) {
			$meta_box->verify_nonce();
		}

		$member_query_builder = new MemberQueryBuilder();
		$member_query_builder->prepare();

		$sanitized_data['post_content'] = $member_query_builder->build();

		return $sanitized_data;
	}

	function publish_member_query( $post_id, $post = null ) {
		if ( $post->post_type === 'member_query' && $post->post_status === 'publish' ) {
			$member_query      = new MemberQuery( $post );
			$segment_publisher = new SegmentPublisher( $member_query );
			$segment_publisher->publish();
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
	 */
	public function get_meta_boxes( $member_query = null ) {
		if ( count( $this->meta_boxes ) === 0 ) {
			$this->meta_boxes = array();

			$this->meta_boxes['preview'] = $this->meta_box_for(
				array(
					'id'       => 'preview',
					'title'    => 'Preview Results',
					'context'  => 'side',
					'priority' => 'default',
					'template' => 'preview',
				),
				$member_query
			);

			$this->meta_boxes['direct_query'] = $this->meta_box_for(
				array(
					'id'       => 'direct_query',
					'title'    => 'Direct Query',
					'context'  => 'side',
					'priority' => 'low',
					'template' => 'direct_query',
				),
				$member_query
			);

			$this->meta_boxes['query_builder'] = $this->meta_box_for(
				array(
					'id'       => 'query_builder',
					'title'    => 'Gigya Social',
					'context'  => 'normal',
					'priority' => 'default',
					'template' => 'query_builder',
				),
				$member_query
			);
		}

		return $this->meta_boxes;
	}

	public function meta_box_for( $params, $member_query ) {
		$meta_box = new MetaBox( $member_query );
		$meta_box->params = $params;

		return $meta_box;
	}

}
