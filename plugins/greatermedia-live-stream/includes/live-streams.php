<?php

// action hooks
add_action( 'init', 'gmr_streams_register_post_type' );
add_action( 'admin_menu', 'gmr_streams_update_admin_menu' );
add_action( 'save_post', 'gmr_streams_save_meta_box_data' );
add_action( 'manage_' . GMR_LIVE_STREAM_CPT . '_posts_custom_column', 'gmr_streams_render_custom_column', 10, 2 );
add_action( 'admin_action_gmr_stream_make_primary', 'gmr_streams_make_primary' );
add_action( 'template_redirect', 'gmr_streams_process_endpoint' );

// filter hooks
add_filter( 'manage_' . GMR_LIVE_STREAM_CPT . '_posts_columns', 'gmr_streams_filter_columns_list' );
add_filter( 'gmr_live_player_streams', 'gmr_streams_get_public_streams' );

/**
 * Registers Live Stream post type.
 *
 * @action init
 * @global WP_Rewrite $wp_rewrite The rewrite object.
 */
function gmr_streams_register_post_type() {
	global $wp_rewrite;
	
	// register post type
	register_post_type( GMR_LIVE_STREAM_CPT, array(
		'public'               => false,
		'show_ui'              => true,
		'rewrite'              => false,
		'query_var'            => false,
		'can_export'           => false,
		'hierarchical'         => false,
		'menu_position'        => 5,
		'menu_icon'            => 'dashicons-format-audio',
		'supports'             => array( 'title' ),
		'register_meta_box_cb' => 'gmr_streams_register_meta_boxes',
		'label'                => 'Live Streams',
		'labels'               => array(
			'name'               => 'Live Streams',
			'singular_name'      => 'Live Stream',
			'menu_name'          => 'Live Streams',
			'name_admin_bar'     => 'Live Stream',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Stream',
			'new_item'           => 'New Stream',
			'edit_item'          => 'Edit Stream',
			'view_item'          => 'View Stream',
			'all_items'          => 'Streams',
			'search_items'       => 'Search Streams',
			'parent_item_colon'  => 'Parent Streams:',
			'not_found'          => 'No links found.',
			'not_found_in_trash' => 'No links found in Trash.',
		),
	) );

	// register stream endpoint
	add_rewrite_endpoint( GMR_LIVE_STREAM_EP, EP_ROOT );
}

/**
 * Registers meta boxes for Live Stream post type.
 */
function gmr_streams_register_meta_boxes() {
	add_meta_box( 'call-sign', 'Call Sign', 'gmr_streams_render_call_sign_meta_box', GMR_LIVE_STREAM_CPT, 'normal', 'high' );
	add_meta_box( 'description', 'Description', 'gmr_streams_render_description_meta_box', GMR_LIVE_STREAM_CPT, 'normal' );
}

/**
 * Renders Call Sign meta box.
 *
 * @param WP_Post $post The stream post object.
 */
function gmr_streams_render_call_sign_meta_box( WP_Post $post ) {
	wp_nonce_field( 'gmr_stream_meta_boxes', '_gmr_stream_nonce', false );

	echo '<input type="text" name="stream_call_sign" class="widefat" value="', esc_attr( get_post_meta( $post->ID, 'call_sign', true ) ), '">';
	echo '<p class="description">Enter stream call sign, for instance WRIF-FM.</p>';
}

/**
 * Renders Description meta box.
 *
 * @param WP_Post $post The stream post object.
 */
function gmr_streams_render_description_meta_box( WP_Post $post ) {
	echo '<input type="text" name="stream_description" class="widefat" value="', esc_attr( get_post_meta( $post->ID, 'description', true ) ), '">';
	echo '<p class="description">Enter short description of the stream, for instance ', esc_html( '"Detroit\'s best rock all day and night, plus Dave and Chuck the Freak in the morning"' ), '.</p>';
}

/**
 * Saves Live Stream meta box data.
 *
 * @action save_post
 * @param int $post_id The post id.
 */
function gmr_streams_save_meta_box_data( $post_id ) {
	// validate nonce and user permissions
	$doing_autosave = defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE;
	$valid_nonce = wp_verify_nonce( filter_input( INPUT_POST, '_gmr_stream_nonce' ), 'gmr_stream_meta_boxes' );
	$can_edit = current_user_can( 'edit_post', $post_id );
	if ( $doing_autosave || ! $valid_nonce || ! $can_edit ) {
		return;
	}

	// save call sign
	$call_sign = sanitize_text_field( filter_input( INPUT_POST, 'stream_call_sign' ) );
	update_post_meta( $post_id, 'call_sign', $call_sign );

	// save description
	$description = sanitize_text_field( filter_input( INPUT_POST, 'stream_description' ) );
	update_post_meta( $post_id, 'description', $description );
}

/**
 * Removes "Add New" sub menu item from "Live Streams" group.
 *
 * @action admin_menu
 */
function gmr_streams_update_admin_menu() {
	remove_submenu_page( 'edit.php?post_type=' . GMR_LIVE_STREAM_CPT, 'post-new.php?post_type=' . GMR_LIVE_STREAM_CPT );
}

/**
 * Adds Call Sign column to the Streams table.
 *
 * @fitler manage_gmr-live-stream_posts_columns
 * @param array $columns The columns array.
 * @return array Extended array of columns.
 */
function gmr_streams_filter_columns_list( $columns ) {
	$cut_mark = array_search( 'title', array_keys( $columns ) ) + 1;

	$columns = array_merge(
		array_slice( $columns, 0, $cut_mark ),
		array(
			'call_sign' => 'Call Sign',
			'primary'   => 'Primary',
			'endpoint'  => 'Endpoint',
		),
		array_slice( $columns, $cut_mark )
	);

	return $columns;
}

/**
 * Renders Call Sign column at the Streams table.
 *
 * @action manage_gmr-live-stream_posts_custom_column
 * @param string $column_name The column name to render.
 * @param int $post_id The current stream id.
 */
function gmr_streams_render_custom_column( $column_name, $post_id ) {
	switch ( $column_name ) {
		case 'call_sign':
			echo '<b>', esc_html( get_post_meta( $post_id, 'call_sign', true ) ), '</b>';
			break;
		case 'primary':
			$post = get_post( $post_id );
			if ( $post->menu_order == 1 ) {
				echo '<span class="dashicons dashicons-star-filled"></span>';
			} else {
				echo '<a href="', wp_nonce_url( 'admin.php?action=gmr_stream_make_primary&stream=' . $post_id, 'gmr_mark_primary_stream' ), '" title="Make Primary">';
					echo '<span class="dashicons dashicons-star-empty"></span>';
				echo '</a>';
			}
			break;
		case 'endpoint':
			$call_sign = trim( get_post_meta( $post_id, 'call_sign', true ) );
			if ( ! empty( $call_sign ) ) {
				$endpoint = home_url( sprintf( '/%s/%s', GMR_LIVE_STREAM_EP, urlencode( $call_sign ) ) );
				printf( '<a href="%s" target="_blank">%s</a>', esc_url( $endpoint ), parse_url( $endpoint, PHP_URL_PATH ) );
			} else {
				echo '&#8212;';
			}
			break;
	}
}

/**
 * Marks a stream as primary.
 *
 * @action admin_action_gmr_stream_make_primary
 */
function gmr_streams_make_primary() {
	check_admin_referer( 'gmr_mark_primary_stream' );

	$stream_id = filter_input( INPUT_GET, 'stream', FILTER_VALIDATE_INT );
	if ( ! $stream_id || ! ( $stream = get_post( $stream_id ) ) || GMR_LIVE_STREAM_CPT != $stream->post_type ) {
		wp_die( 'Stream was not found.' );
	}

	$paged = 0;

	do {
		$paged++;
		$query = new WP_Query( array(
			'post_type'           => GMR_LIVE_STREAM_CPT,
			'post_status'         => 'any',
			'posts_per_page'      => 100,
			'paged'               => $paged,
			'ignore_sticky_posts' => true,
		) );

		while ( $query->have_posts() ) {
			$stream = $query->next_post();
			$stream->menu_order = $stream->ID == $stream_id ? 1 : 0;
			wp_update_post( $stream->to_array() );
		}
	} while ( $paged <= $query->max_num_pages );
	
	wp_redirect( wp_get_referer() );
	exit;
}

/**
 * Returns public streams.
 *
 * @filter gmr_live_player_streams
 * @return array The array of public streams.
 */
function gmr_streams_get_public_streams() {
	$paged = 0;
	$streams = array();

	do {
		$paged++;
		$query = new WP_Query( array(
			'post_type'           => GMR_LIVE_STREAM_CPT,
			'posts_per_page'      => 100,
			'paged'               => $paged,
			'ignore_sticky_posts' => true,
			'orderby'             => 'menu_order',
			'fields'              => 'ids',
		) );

		while ( $query->have_posts() ) {
			$stream_id = $query->next_post();

			$call_sign = get_post_meta( $stream_id, 'call_sign', true );
			if ( empty( $call_sign ) ) {
				continue;
			}

			$streams[ $call_sign ] = get_post_meta( $stream_id, 'description', true );
		}
	} while ( $paged <= $query->max_num_pages );
	
	return $streams;
}

/**
 * Processes stream endpoing submission.
 *
 * @action template_redirect
 */
function gmr_streams_process_endpoint() {
	$stream = get_query_var( GMR_LIVE_STREAM_EP );
	if ( empty( $stream ) ) {
		return;
	}

	$query = new WP_Query( array(
		'post_type'           => GMR_LIVE_STREAM_CPT,
		'meta_key'            => 'call_sign',
		'meta_value'          => $stream,
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => 1,
		'no_found_rows'       => true,
	) );

	if ( ! $query->have_posts() ) {
		status_header( 404 );
		exit;
	}

	$stream = $query->next_post();

	exit;
}