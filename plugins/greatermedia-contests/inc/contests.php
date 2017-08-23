<?php

// action hooks
add_action( 'init', 'gmr_contests_register_post_type' );
add_action( 'wp_enqueue_scripts', 'gmr_contests_enqueue_front_scripts', 100 );
add_action( 'manage_' . GMR_CONTEST_CPT . '_posts_custom_column', 'gmr_contests_render_contest_column', 10, 2 );
add_action( 'before_delete_post', 'gmr_contests_prevent_hard_delete' );
add_action( 'wp_trash_post', 'gmr_contests_prevent_hard_delete' );
add_action( 'transition_post_status', 'gmr_contests_prevent_trash_transition', 10, 3 );
add_action( 'admin_enqueue_scripts', 'gmr_contests_admin_enqueue_scripts' );

// filter hooks
add_filter( 'map_meta_cap', 'gmr_contests_map_meta_cap', 10, 4 );
add_filter( 'ajax_query_attachments_args', 'gmr_contests_adjuste_attachments_query' );
add_filter( 'wp_link_query_args', 'gmr_contests_exclude_ugc_from_editor_links_query' );
add_filter( 'gmr-homepage-curation-post-types', 'gmr_contest_register_curration_post_type' );
add_filter( 'gmr-show-curation-post-types', 'gmr_contest_register_curration_post_type' );
add_filter( 'post_thumbnail_html', 'gmr_contests_post_thumbnail_html', 10, 4 );
add_filter( 'manage_' . GMR_CONTEST_CPT . '_posts_columns', 'gmr_contests_filter_contest_columns_list' );
add_filter( 'post_row_actions', 'gmr_contests_filter_contest_actions', PHP_INT_MAX, 2 );
add_filter( 'gmr_live_link_suggestion_post_types', 'gmr_contests_extend_live_link_suggestion_post_types' );
add_filter( 'pre_get_posts', 'gmr_filter_expired_contests' );

/**
 * Enqueues contests related scripts.
 *
 * @action wp_enqueue_scripts 100
 */
function gmr_contests_enqueue_front_scripts() {
	// @NOTE: we have to always load frontend script, because we would have troubles when pjax is enabled
	$base_path = trailingslashit( GREATER_MEDIA_CONTESTS_URL );
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_script( 'greatermedia-contests', "{$base_path}js/contests{$postfix}.js", array( 'jquery' ), GREATER_MEDIA_CONTESTS_VERSION, true );
	wp_rocketloader_script( 'greatermedia-contests' );
}

/**
 * Enqueues admin styles.
 *
 * @action admin_enqueue_scripts.
 * @global string $typenow The current post type.
 */
function gmr_contests_admin_enqueue_scripts() {
	global $typenow;
	if ( in_array( $typenow, array( GMR_SURVEY_CPT, GMR_CONTEST_CPT ) ) ) {
		wp_enqueue_style( 'greatermedia-contests-admin', trailingslashit( GREATER_MEDIA_CONTESTS_URL ) . 'css/greatermedia-contests-admin.css', null, GREATER_MEDIA_CONTESTS_VERSION );
	}
}

/**
 * Registers contest post type in the curration types list.
 *
 * @filter gmr-homepage-curation-post-types
 * @filter gmr-show-curation-post-types
 * @param array $types Array of already registered types.
 * @return array Extended array of post types.
 */
function gmr_contest_register_curration_post_type( $types ) {
	$types[] = GMR_CONTEST_CPT;
	return $types;
}

/**
 * Removes UGC from editor links query.
 *
 * @filter wp_link_query_args
 * @param array $args The array of query args.
 * @return array Adjusted array of query args.
 */
function gmr_contests_exclude_ugc_from_editor_links_query( $args ) {
	if ( ! empty( $args['post_type'] ) ) {
		$post_type = $args['post_type'];
		if ( is_string( $post_type ) ) {
			$post_type = array_map( 'trim', explode( ',', $post_type ) );
		}

		unset( $post_type[ array_search( GMR_SUBMISSIONS_CPT, $post_type ) ] );
		$args['post_type'] = $post_type;
	}
	return $args;
}

/**
 * Removes delete_post(s) capabilities for public contests or contest entries.
 *
 * @filter map_meta_cap
 * @global string $pagenow The current page.
 * @global string $typenow The current type.
 * @param array $caps The array of user capabilities.
 * @param string $cap The current capability to check against.
 * @param int $user_id The current user id.
 * @param array $args Additional parameters.
 * @return array The array of allowed capabilities.
 */
function gmr_contests_map_meta_cap( $caps, $cap, $user_id, $args ) {
	global $pagenow, $typenow;

	if ( ! in_array( $typenow, array( GMR_CONTEST_CPT ) ) ) {
		return $caps;
	}

	if ( ! in_array( $pagenow, array( 'edit.php', 'post.php' ) ) ) {
		return $caps;
	}

	if ( in_array( $cap, array( 'delete_post', 'delete_posts' ) ) ) {
		if ( is_array( $args ) && ! empty( $args ) ) {
			// let's allow removal for non public contests
			$post = get_post( current( $args ) );
			if ( $post && GMR_CONTEST_CPT == $post->post_type ) {
				$status = get_post_status_object( $post->post_status );
				if ( $status && ! $status->public ) {
					return $caps;
				}
			}
		}

		$caps[] = 'do_not_allow';

		unset( $caps[ array_search( 'delete_post', $caps ) ] );
		unset( $caps[ array_search( 'delete_posts', $caps ) ] );
	}

	return $caps;
}

/**
 * Prevents started contest or contest entry deletion.
 *
 * @action before_delete_post
 * @param int $post The post id, which will be deleted.
 * @param string $post_status The actuall post status before removal.
 */
function gmr_contests_prevent_hard_delete( $post, $post_status = null ) {
	// do nothing if a post doesn't exist
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	if ( GMR_CONTEST_CPT == $post->post_type ) {
		if ( empty( $post_status ) ) {
			$post_status = $post->post_status;
		}

		$status = get_post_status_object( $post_status );
		if ( $status && $status->public ) {
			wp_die( 'You can not delete or trash already started contest.', '', array( 'back_link' => true ) );
		}
		return;
	}
}

/**
 * Prevent started contests or contest entries transition to trash.
 *
 * @action transition_post_status 10 3
 * @param string $new_status The new status.
 * @param string $old_status The old status.
 * @param WP_Post $post The post object.
 */
function gmr_contests_prevent_trash_transition( $new_status, $old_status, $post ) {
	if ( 'trash' == $new_status ) {
		gmr_contests_prevent_hard_delete( $post, $old_status );
	}
}

/**
 * Removes "private" post status from query args for ajax request which returns
 * images for media popup.
 *
 * @filter ajax_query_attachments_args
 *
 * @param array $args The initial array of query arguments.
 * @return array Adjusted array of query arguments which doesn't contain "private" post status.
 */
function gmr_contests_adjuste_attachments_query( $args ) {
	if ( isset( $args['post_status'] ) ) {
		$post_status = is_array( $args['post_status'] )
			? $args['post_status']
			: explode( ',', $args['post_status'] );

		unset( $post_status[ array_search( 'private', $post_status ) ] );

		if ( empty( $post_status ) ) {
			$post_status = array( 'inherit' );
		}

		$args['post_status'] = $post_status;
	}

	return $args;
}

/**
 * Registers custom post types related to contests area.
 *
 * @action init
 */
function gmr_contests_register_post_type() {
	$labels = array(
		'name'               => 'Contests',
		'singular_name'      => 'Contest',
		'menu_name'          => 'Contests',
		'parent_item_colon'  => 'Parent Contest:',
		'all_items'          => 'All Contests',
		'view_item'          => 'View Contest',
		'add_new_item'       => 'Add New Contest',
		'add_new'            => 'Add New',
		'edit_item'          => 'Edit Contest',
		'update_item'        => 'Update Contest',
		'search_items'       => 'Search Contests',
		'not_found'          => 'Not found',
		'not_found_in_trash' => 'Not found in Trash'
	);

	$args   = array(
		'label'               => 'Contests',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'public'              => true,
		'menu_position'       => 32,
		'menu_icon'           => 'dashicons-forms',
		'can_export'          => true,
		'has_archive'         => 'contests',
		'rewrite'             => array( 'slug' => 'contests', 'ep_mask' => EP_GMR_CONTEST ),
		'capability_type'     => array( 'contest', 'contests' ),
		'map_meta_cap'        => true,
	);

	register_post_type( GMR_CONTEST_CPT, $args );
	add_post_type_support( GMR_CONTEST_CPT, 'timed-content' );
}

/**
 * Determines whether contest is started or not.
 *
 * @param int|WP_Post $contest_id The contest object or id.
 * @return boolean TRUE if contest has not been started yet, otherwise FALSE.
 */
function gmr_contest_is_not_started( $contest_id = null ) {
	if ( ! $contest_id ) {
		$contest_id = get_the_ID();
	}

	$now = current_time( 'timestamp', 1 );
	$start = (int) get_post_meta( $contest_id, 'contest-start', true );

	return $start > 0 && $start > $now;
}

/**
 * Determines whether contest is finished or not.
 *
 * @param int|WP_Post $contest_id The contest object or id.
 * @return boolean TRUE if contest is finished, otherwise FALSE.
 */
function gmr_contest_is_finished( $contest_id = null ) {
	if ( ! $contest_id ) {
		$contest_id = get_the_ID();
	}

	$now = current_time( 'timestamp', 1 );
	$end = (int) get_post_meta( $contest_id, 'contest-end', true );

	return $end > 0 && $now > $end;
}

/**
 * Saves contest submitted files.
 *
 * @param array $submitted_files
 * @param GreaterMediaContestEntry $entry
 */
function gmr_contests_handle_submitted_files( array $submitted_files, GreaterMediaContestEntry $entry ) {
	if ( empty( $submitted_files ) ) {
		return;
	}

	$thumbnail = null;
	$data_type = count( $submitted_files ) == 1 ? 'image' : 'gallery';

	$ugc = GreaterMediaUserGeneratedContent::for_data_type( $data_type );
	$ugc->post->post_parent = $entry->post->post_parent;

	reset( $submitted_files );
	$thumbnail = current( $submitted_files );

	switch ( $data_type ) {
		case 'image':
			$ugc->post->post_content = wp_get_attachment_image( current( $submitted_files ), 'full' );
			break;
		case 'gallery':
			$ugc->post->post_content = '[gallery ids="' . implode( ',', $submitted_files ) . '"]';
			break;
	}

	$ugc->save();

	set_post_thumbnail( $ugc->post->ID, $thumbnail );

	add_post_meta( $ugc->post->ID, 'contest_entry_id', $entry->post->ID );
	update_post_meta( $entry->post->ID, 'submission_id', $ugc->post->ID );
}

/**
 * Substitutes original thumbnail on a special thumbnail for contest submissions.
 *
 * @filter post_thumbnail_html 10 4
 * @param string $html Original thumbnail html.
 * @param int $post_id The contest submission id.
 * @param int $post_thumbnail_id The thumbnail id.
 * @param string $size The size of thumbnail.
 * @return string The html of a thumbnail.
 */
function gmr_contests_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size ) {
	$post = get_post( $post_id );
	if ( GMR_SUBMISSIONS_CPT != $post->post_type ) {
		return $html;
	}

	$image = wp_get_attachment_image_src( $post_thumbnail_id, $size );
	if ( empty( $image ) ) {
		return $html;
	}

	return sprintf( '<div class="contest__submission--thumbnail" style="background-image:url(%s)"></div>', $image[0] );
}

/**
 * Return contest entry author name.
 *
 * @param int $entry_id The contest entry id.
 * @return string The author name.
 */
function gmr_contest_get_entry_author( $entry_id, $return = 'string' ) {
	$username = trim( get_post_meta( $entry_id, 'entrant_name', true ) );
	if ( ! $username ) {
		$username = 'guest';
	}

	if ( 'string' == $return ) {
		return $username;
	}

	$username = explode( ' ', $username );
	$last_name = array_pop( $username );
	$first_name = implode( ' ', $username );

	return array( $first_name, $last_name );
}

/**
 * Returns user email.
 *
 * @param int|WP_Post $entry_id Contest post object or id.
 * @return string The email address.
 */
function gmr_contest_get_entry_author_email( $entry_id ) {
	$email = get_post_meta( $entry_id, 'entrant_email', true );
	return filter_var( $email, FILTER_VALIDATE_EMAIL );
}

/**
 * Returns contest type label of a certain contest.
 *
 * @param int|WP_Post $contest The contest post object or id.
 * @return string The contest type label.
 */
function gmr_contest_get_type_label( $contest = null ) {
	$contest = get_post( $contest );

	switch ( get_post_meta( get_the_ID(), 'contest_type', true ) ) {
		case 'onair':
			return 'On Air';
		case 'both':
			return 'On Air & Online';
		case 'online':
		default:
			return 'Online';
	}

	return '';
}

/**
 * Adds columns to the contests table.
 *
 * @filter manage_contest_posts_columns
 * @param array $columns Initial array of columns.
 * @return array The array of columns.
 */
function gmr_contests_filter_contest_columns_list( $columns ) {
	// put just after the title column
	$cut_mark = array_search( 'title', array_keys( $columns ) ) + 1;

	$columns = array_merge(
		array_slice( $columns, 0, $cut_mark ),
		array(
			'start_date'  => 'Start Date',
			'finish_date' => 'Finish Date',
		),
		array_slice( $columns, $cut_mark )
	);

	$columns['date'] = 'Created Date';

	return $columns;
}

/**
 * Renders custom columns for the contests table.
 *
 * @param string $column_name The column name which is gonna be rendered.
 * @param int $post_id The post id.
 */
function gmr_contests_render_contest_column( $column_name, $post_id ) {
	if ( 'start_date' == $column_name ) {
		$timestamp = (int) get_post_meta( $post_id, 'contest-start', true );
		echo ! empty( $timestamp ) ? date( get_option( 'date_format' ), $timestamp ) : '&#8212;';
	} elseif ( 'finish_date' == $column_name ) {
		$timestamp = (int) get_post_meta( $post_id, 'contest-end', true );
		echo ! empty( $timestamp ) ? date( get_option( 'date_format' ), $timestamp ) : '&#8212;';
	}
}

/**
 * Filters contest actions at the contests table.
 *
 * @filter post_row_actions PHP_INT_MAX 2
 * @param array $actions The initial array of actions.
 * @param WP_Post $post The actual contest object.
 * @return array Filtered array of actions.
 */
function gmr_contests_filter_contest_actions( $actions, WP_Post $post ) {
	// do nothing if it is not a contest post
	if ( GMR_CONTEST_CPT != $post->post_type ) {
		return $actions;
	}

	// unset redundant actions
	unset( $actions['inline hide-if-no-js'], $actions['edit_as_new_draft'], $actions['clone'] );

	// move trash/delete link to the end of actions list if it exists
	foreach ( array( 'trash', 'delete' ) as $key ) {
		if ( isset( $actions[ $key ] ) ) {
			$link = $actions[ $key ];
			unset( $actions[ $key ] );
			$actions[ $key ] = $link;
		}
	}

	return $actions;
}

/**
 * Determines whether a contest has file fields or not.
 *
 * @param WP_Post|int $contest The contest object or id.
 * @return boolean TRUE if a contest has file fields, otherwise FALSE.
 */
function gmr_contest_has_files( $contest ) {
	$contest = get_post( $contest );
	if ( ! $contest || GMR_CONTEST_CPT != $contest->post_type ) {
		return false;
	}

	$form = get_post_meta( $contest->ID, 'embedded_form', true );
	if ( empty( $form ) ) {
		return array();
	}

	if ( is_string( $form ) ) {
		$clean_form = trim( $form, '"' );
		$form = json_decode( $clean_form );
	}

	foreach ( $form as $field ) {
		if ( 'file' == $field->field_type ) {
			return true;
		}
	}

	return false;
}

/**
 * Extends live link suggestion post types.
 *
 * @param array $post_types The array of already registered post types.
 * @return array The array of extended post types.
 */
function gmr_contests_extend_live_link_suggestion_post_types( $post_types ) {
	$post_types[] = GMR_CONTEST_CPT;
	return $post_types;
}

/**
 * Filters out expired contests
 */
global $did_filter_expired_contests;
$did_filter_expired_contests = false;

function gmr_filter_expired_contests( $query ) {
	global $did_filter_expired_contests;

	if ( ! is_admin() && ( is_search() || is_post_type_archive( GMR_CONTEST_CPT ) ) && ! $did_filter_expired_contests ) {
		$now           = time();
		$query_params = array(
			'relation' => 'AND',
			array(
				'relation' => 'OR',
				/* This is a contest with an valid end timestamp */
				array(
					'key'     => 'contest-end',
					'type'    => 'NUMERIC',
					'value'   => $now,
					'compare' => '>',
				),
				/* any other post/type which matches the search query */
				array(
					'key'     => 'contest-end',
					'type'    => 'NUMERIC',
					'value'   => '',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'   => 'contest-end',
					'type'  => 'NUMERIC',
					'value' => 0,
				)
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'secret',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'secret',
					'type'    => 'NUMERIC',
					'value'   => 1,
					'compare' => '!=',
				),
			)
		);

		$query->set( 'meta_query', $query_params );
		$did_filter_expired_contests = true;

		return $query;
	} else {
		return $query;
	}
}
