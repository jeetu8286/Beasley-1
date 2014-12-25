<?php

// action hooks
add_action( 'init', 'gmr_contests_register_post_type' );
add_action( 'init', 'gmr_contests_register_endpoint' );
add_action( 'wp_enqueue_scripts', 'gmr_contests_enqueue_front_scripts' );
add_action( 'template_redirect', 'gmr_contests_process_action' );

add_action( 'gmr_contest_load', 'gmr_contests_render_form' );
add_action( 'gmr_contest_submit', 'gmr_contests_process_form_submission' );
add_action( 'gmr_contest_confirm-age', 'gmr_contests_confirm_user_age' );
add_action( 'gmr_contest_reject-age', 'gmr_contests_reject_user_age' );

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
		'taxonomies'          => array( 'contest_type' ),
		'public'              => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-forms',
		'can_export'          => true,
		'has_archive'         => 'contests',
		'rewrite'             => array( 'slug' => 'contest', 'ep_mask' => EP_GMR_CONTEST ),
	);

	register_post_type( GMR_CONTEST_CPT, $args );
	add_post_type_support( GMR_CONTEST_CPT, 'timed-content' );
}

/**
 * Registers endpoints for contests related tasks.
 *
 * @action init
 */
function gmr_contests_register_endpoint() {
	add_rewrite_endpoint( 'action', EP_GMR_CONTEST );
}

/**
 * Registers contests related scripts.
 *
 * @action wp_enqueue_scripts
 */
function gmr_contests_enqueue_front_scripts() {
	if ( is_singular( GMR_CONTEST_CPT ) ) {
		$base_path = trailingslashit( GREATER_MEDIA_CONTESTS_URL );
		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		$permalink = untrailingslashit( get_permalink() );
			
		wp_enqueue_style( 'greatermedia-contests', "{$base_path}css/greatermedia-contests.css", array( 'datetimepicker', 'parsleyjs' ), GREATER_MEDIA_CONTESTS_VERSION );
		
		wp_enqueue_script( 'greatermedia-contests', "{$base_path}js/greatermedia-contests{$postfix}.js", array( 'jquery', 'datetimepicker', 'parsleyjs', 'parsleyjs-words' ), GREATER_MEDIA_CONTESTS_VERSION, true );
		wp_localize_script( 'greatermedia-contests', 'GreaterMediaContests', array(
			'selectors' => array(
				'container' => '#contest-form',
				'form'      => '.' . GreaterMediaFormbuilderRender::FORM_CLASS,
				'yes_age'   => '.min-age-yes',
				'no_age'    => '.min-age-no',
			),
			'endpoints' => array(
				'load'        => "{$permalink}/action/load/",
				'submit'      => "{$permalink}/action/submit/",
				'confirm_age' => "{$permalink}/action/confirm-age/",
				'reject_age'  => "{$permalink}/action/reject-age/",
			),
		) );
	}
}

/**
 * Processes contest actions triggered from front end.
 *
 * @action template_redirect
 */
function gmr_contests_process_action() {
	// do nothing if it is a regular request
	$action = get_query_var( 'action' );
	if ( ! is_singular( GMR_CONTEST_CPT ) || empty( $action ) ) {
		return;
	}

	// disable batcache if it is activated
	if ( function_exists( 'batcache_cancel' ) ) {
		batcache_cancel();
	}

	// define doing AJAX if it was not defined yet
	if( ! defined( 'DOING_AJAX' ) && ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
		define( 'DOING_AJAX', true );
	}

	// disble HTTP cache
	nocache_headers();

	// do contest action
	do_action( "gmr_contest_{$action}" );
	exit;
}

/**
 * Shows contest form after user confirmed his age.
 *
 * @action gmr_contest_confirm-age
 */
function gmr_contests_confirm_user_age() {
	gmr_contests_render_form( true );
}

/**
 * Rejects contest applying to an user which doesn't meet age requirements.
 *
 * @action gmr_contest_reject-age
 */
function gmr_contests_reject_user_age() {
	$min_age = (int) get_post_meta( get_the_ID(), 'contest-min-age', true );
	echo '<p>Sorry, you must be at least ', $min_age, ' years old to enter the contest!</p>';
}

/**
 * Renders contest form.
 *
 * @action gmr_contest_load
 * @param boolean $skip_age Determines whether to check user age or not.
 */
function gmr_contests_render_form( $skip_age = false ) {
	$contest_id = get_the_ID();

	// check start date
	$now = current_time( 'timestamp', 1 );
	$start = (int) get_post_meta( $contest_id, 'contest-start', true );
	if ( $start > 0 && $start > $now ) {
		echo '<p>The contest is not started yet.</p>';
		return;
	}

	// check end date
	$end = (int) get_post_meta( $contest_id, 'contest-end', true );
	if ( $end > 0 && $now > $end ) {
		echo '<p>The contest is already finished.</p>';
		return;
	}

	// check the max entries limit
	$max_entries = get_post_meta( $contest_id, 'contest-max-entries', true );
	if ( $max_entries > 0 ) {
		$transient = 'contest_entries_' . $contest_id;
		$contest_entries_count = get_transient( $transient );
		if ( false === $contest_entries_count ) {
			$query = new WP_Query( array(
				'post_type'      => 'contest_entry',
				'post_status'    => 'any',
				'post_parent'    => $contest_id,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			) );

			$contest_entries_count = $query->found_posts;
			set_transient( $transient, $contest_entries_count, DAY_IN_SECONDS );
		}

		if ( $contest_entries_count >= $max_entries ) {
			echo '<p>This contest has reached maximum number of entries!</p>';
			return;
		}
	}

	// check if user has to be logged in
	$gigya_logged_in_exists = function_exists( 'is_gigya_user_logged_in' );
	$members_only = get_post_meta( $contest_id, 'contest-members-only', true );
	if ( $members_only && $gigya_logged_in_exists && ! is_gigya_user_logged_in() ) {
		$login_url = parse_url( get_permalink(), PHP_URL_PATH );
		$login_url = home_url( '/members/login/?dest=' . urlencode( $login_url ) );
		echo '<p>You must be logged in to enter the contest! <a href="', esc_url( $login_url ), '">Sign in here</a></p>';
		return;
	}

	// check if user can submit multiple entries
	$single_entry = get_post_meta( $contest_id, 'contest-single-entry', true );
	if ( $single_entry && function_exists( 'has_user_entered_contest' ) && has_user_entered_contest( $contest_id ) ) {
		echo '<p>You have already entered this contest!</p>';
		return;
	}

	// check min age restriction
	if ( ! $skip_age ) {
		$min_age = (int) get_post_meta( $contest_id, 'contest-min-age', true );
		if ( $min_age > 0 ) {
			if ( $gigya_logged_in_exists && is_gigya_user_logged_in() ) {
				$current_age = get_gigya_user_field( 'age' );
				if ( $current_age < $min_age ) {
					echo '<p>You must be at least ', $min_age, ' years old to enter the contest!</p>';
					return;
				}
			} else {
				$login_url = parse_url( get_permalink(), PHP_URL_PATH );
				$login_url = home_url( '/members/login/?dest=' . urlencode( $login_url ) );
				
				echo '<p>Please, <a href="', esc_url( $login_url ), '">sign in</a> or confirm that you are at least ', $min_age, ' years old.</p>';
				echo '<p><a class="min-age-yes" href="#">Yes, I am</a> &#8212; <a class="min-age-no" href="#">No, I am not</a></p>';
				return;
			}
		}
	}

	// render the form
	$form = get_post_meta( $contest_id, 'embedded_form', true );
	$error = GreaterMediaFormbuilderRender::render( $contest_id, $form );
	if ( is_wp_error( $error ) ) {
		echo '<p>', $error->get_error_message(), '</p>';
	}
}

/**
 * Processes contest submission.
 * 
 * @action gmr_contest_submit
 */
function gmr_contests_process_form_submission() {
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
		return;
	}

	$submitted_values = array();
	$submitted_files  = array( 'images' => array(), 'other'  => array() );
	
	$contest_id = get_the_ID();
	$form = @json_decode( get_post_meta( $contest_id, 'embedded_form', true ) );
	foreach ( $form as $field ) {
		$post_array_key = 'form_field_' . $field->cid;
		if ( 'file' === $field->field_type ) {
			if ( isset( $_FILES[ $post_array_key ] ) ) {
				$file_type_index = file_is_valid_image( $_FILES[ $post_array_key ]['tmp_name'] ) ? 'images' : 'other';
				$submitted_files[ $file_type_index ][ $post_array_key ] = $_FILES[ $post_array_key ];
			}
		} else if ( isset( $_POST[ $post_array_key ] ) ) {
			if ( is_scalar( $_POST[ $post_array_key ] ) ) {
				$submitted_values[ $field->cid ] = sanitize_text_field( $_POST[ $post_array_key ] );
			} else if ( is_array( $_POST[ $post_array_key ] ) ) {
				$submitted_values[ $field->cid ] = array_map( 'sanitize_text_field', $_POST[ $post_array_key ] );
			}
		}
	}

	list( $entrant_reference, $entrant_name ) = gmr_contests_get_gigya_entrant_id_and_name();

	$entry = GreaterMediaContestEntryEmbeddedForm::create_for_data( $contest_id, $entrant_name, $entrant_reference, GreaterMediaContestEntry::ENTRY_SOURCE_EMBEDDED_FORM, json_encode( $submitted_values ) );
	$entry->save();

	gmr_contests_handle_submitted_files( $submitted_files, $entry );

	do_action( 'greatermedia_contest_entry_save', $entry );
	delete_transient( 'contest_entries_' . $contest_id );

	echo wpautop( get_post_meta( $contest_id, 'form-thankyou', true ) );
}

/**
 * Saves contest submitted files.
 * 
 * @param array $submitted_files
 * @param GreaterMediaContestEntry $entry
 */
function gmr_contests_handle_submitted_files( array $submitted_files, GreaterMediaContestEntry $entry ) {
	/**
	 * Ignoring the "other" files per GMR-343
	 * "There's no reason for Contest or Survey upload fields to allow any filetypes other than images. Aside
	 * from security considerations, it also becomes much more complex to manage user generated content if it's
	 * anything beside photos."
	 */
	if ( empty( $submitted_files['images'] ) ) {
		return;
	}

	if ( 1 === count( $submitted_files['images'] ) ) {
		// Single image. Create a GreaterMediaUserGeneratedImage.
		$ugc = GreaterMediaUserGeneratedContent::for_data_type( 'image' );
		$ugc->post->post_parent = $entry->post_id();
		$ugc->save();

		reset( $submitted_files );
		$upload_field = key( $submitted_files['images'] );

		$attachment_id = media_handle_upload( $upload_field, $entry->post->post_parent, array( 'post_status' => 'private' ) );

		$ugc->post->post_content = wp_get_attachment_image( $attachment_id, 'full' );
		$ugc->save();

		set_post_thumbnail( $ugc->post->ID, $attachment_id );
	} else {
		// Multiple images. Create a GreaterMediaUserGeneratedGallery.
		$ugc = GreaterMediaUserGeneratedContent::for_data_type( 'gallery' );
		$ugc->post->post_parent = $entry->post_id();
		$ugc->save();

		$attachment_ids = array();
		foreach ( array_keys( $submitted_files['images'] ) as $upload_field ) {
			$attachment_ids[] = media_handle_upload( $upload_field, $entry->post->post_parent, array( 'post_status' => 'private' ) );
		}

		$ugc->post->post_content = '[gallery ids="' . implode( ',', $attachment_ids ) . '"]';
		$ugc->save();

		set_post_thumbnail( $ugc->post->ID, $attachment_ids[0] );
	}
}

/**
 * Get Gigya ID and build name, from Gigya session data if available
 *
 * @return array
 */
function gmr_contests_get_gigya_entrant_id_and_name() {
	$entrant_name = 'Anonymous Listener';
	$entrant_reference = null;
	
	if ( class_exists( '\GreaterMedia\Gigya\GigyaSession' ) ) {
		$gigya_session = \GreaterMedia\Gigya\GigyaSession::get_instance();
		$gigya_id = $gigya_session->get_user_id();
		if ( ! empty( $gigya_id ) ) {
			$entrant_reference = $gigya_id;
			$entrant_name      = $gigya_session->get_key( 'firstName' ) . ' ' . $gigya_session->get_key( 'lastName' );
		}
	}

	return array( $entrant_reference, $entrant_name );
}