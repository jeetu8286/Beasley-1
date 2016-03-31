<?php

namespace GreaterMedia\HomepageCountdownClock;

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaCountdownClockMetaboxes {

	public function __construct() {
		//add_action( 'admin_enqueue_scripts', array( $this, 'register_settings_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		//add_action( 'save_post', array( $this, 'save_post' ) );
	}

	/**
	 * Enqueue JavaScript & CSS
	 * Implements admin_enqueue_scripts action
	 */
	public function admin_enqueue_scripts() {

		global $post;

		// Make sure this is the post editor
		$current_screen = get_current_screen();
		if ( 'post' !== $current_screen->base ) {
			return;
		}

		// Make sure there's a post
		if ( ! isset( $GLOBALS['post'] ) || ! ( $GLOBALS['post'] instanceof \WP_Post ) ) {
			return;
		}

		if ( $post && GMR_COUNTDOWN_CLOCK_CPT === $post->post_type ) {
			$base_path = trailingslashit( GMEDIA_HOMEPAGE_COUNTDOWN_CLOCK_URL );
			$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

			//wp_enqueue_style( 'formbuilder' );
			wp_enqueue_style( 'datetimepicker' );
			wp_enqueue_style( 'font-awesome' );

			wp_enqueue_script( 'ie8-node-enum' );
			wp_enqueue_script( 'underscore-mixin-deepextend' );
			wp_enqueue_script( 'backbone-deep-model' );
			wp_enqueue_script( 'datetimepicker' );

			//wp_enqueue_script( 'formbuilder' );
			wp_enqueue_script( 'rivets' );

			$form = @json_decode( get_post_meta( $post->ID, 'embedded_form', true ), true );
			if ( empty( $form ) ) {
				$form = array();
			} else {
				// backward compatibility: we need to be able to delete any fields
				foreach ( $form as &$sticky ) {
					unset( $sticky['sticky'] );
				}
			}
			wp_enqueue_style( 'greatermedia-countdown-clock-admin', trailingslashit( GMEDIA_HOMEPAGE_COUNTDOWN_CLOCK_URL ) . 'css/greatermedia-countdown-clock-admin.css', null, GMEDIA_HOMEPAGE_COUNTDOWN_CLOCK_VERSION );
			wp_enqueue_script( 'greatermedia-countdown-clock-admin', "{$base_path}js/countdown-clock-admin{$postfix}.js", array( 'datetimepicker' ), false, true );
		};
	}

	/**
	 * Register meta box fields through the Settings API
	 * Implements admin_enqueue_scripts action to be sure global $post is set by then
	 */
	public function register_settings_fields() {

		// Make sure this is an admin screen
		if ( ! is_admin() || 'post' !== get_current_screen()->base ) {
			return;
		}

		// Make sure there's a post
		if ( ! isset( $GLOBALS['post'] ) || ! ( $GLOBALS['post'] instanceof \WP_Post ) ) {
			return;
		}

		$post_id = absint( $GLOBALS['post']->ID );

		add_settings_section( 'greatermedia-contest-form', null, '__return_false', 'greatermedia-contest-form' );

		$form_title = get_post_meta( $post_id, 'form-title', true );
		add_settings_field( 'form-title', 'Form Title Text', array( $this, 'render_input' ), 'greatermedia-contest-form', 'greatermedia-contest-form', array(
			'post_id' => $post_id,
			'id'      => 'greatermedia_contest_form_title',
			'name'    => 'greatermedia_contest_form_title',
			'size'    => 50,
			'value'   => ! empty( $form_title ) ? $form_title : 'Enter Here to Win',
		) );

		$submit_text = get_post_meta( $post_id, 'form-submitbutton', true );
		add_settings_field( 'form-submitbutton', 'Submit Button Text', array( $this, 'render_input' ), 'greatermedia-contest-form', 'greatermedia-contest-form', array(
			'post_id' => $post_id,
			'id'      => 'greatermedia_contest_form_submit',
			'name'    => 'greatermedia_contest_form_submit',
			'size'    => 50,
			'value'   => ! empty( $submit_text ) ? $submit_text : 'Submit',
		) );

		$thank_you = get_post_meta( $post_id, 'form-thankyou', true );
		add_settings_field( 'form-thankyou', '"Thank You" Message', array( $this, 'render_input' ), 'greatermedia-contest-form', 'greatermedia-contest-form', array(
			'post_id' => $post_id,
			'id'      => 'greatermedia_contest_form_thankyou',
			'name'    => 'greatermedia_contest_form_thankyou',
			'size'    => 50,
			'value'   => ! empty( $thank_you ) ? $thank_you : 'Thanks for entering!',
		) );

		add_settings_field( 'show-submission-details', 'Show submission details', array( $this, 'render_submission_details_field'), 'greatermedia-contest-form', 'greatermedia-contest-form', $post_id );

	}

	public function render_submission_details_field( $post_id ) {
		$show = get_post_meta( $post_id, 'show-submission-details', true );
		?><select name="show-submission-details">
			<option value="1">Show</option>
			<option value="0"<?php selected( $show == 0 && $show !== false ); ?>>Hide</option>
		</select><?php
	}

	/**
	 * Return an array of active Gravity Forms
	 *
	 */
	public function get_gravity_forms() {
		if ( class_exists( 'RGFormsModel' ) ) {
			$forms      = RGFormsModel::get_forms( null, 'title' );
			$form_array = array();
			foreach ( $forms as $form ) {
				$form_array[ $form->id ] = $form->title;
			}

			return $form_array;
		}
	}

	/**
	 * Render an HTML5 date input meta field
	 *
	 * @param array $args
	 */
	public function render_date_field( array $args ) {
		static $render_server_time = true;

		$name = $args['name'];
		$date = ! empty( $args['value'] ) && is_numeric( $args['value'] ) ? $args['value'] : null;
		if ( ! empty( $date ) ) {
			$date += get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		}

		$args['value'] = '';

		$args['type'] = 'date';
		$args['name'] = $name . '[date]';
		if ( $date ) {
			$args['value'] = date( 'Y-m-d', $date );
		}
		self::render_input( $args );

		$args['type'] = 'time';
		$args['name'] = $name . '[time]';
		if ( $date ) {
			$args['value'] = date( 'H:i', $date );
		}
		self::render_input( $args );

		if ( $render_server_time ) {
			$format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
			echo ' <small>(server time is ' . date( $format, current_time( 'timestamp' ) ) . ')</small>';
			$render_server_time = false;
		}

	}

	public function render_input( array $args ) {

		if ( ! isset( $args['type'] ) || empty( $args['type'] ) ) {
			$args['type'] = 'text';
		}

		if ( isset( $args['size'] ) ) {
			$size_attr = 'size="' . absint( $args['size'] ) . '"';
		} else {
			$size_attr = '';
		}

		echo '<input type="' . esc_attr( $args['type'] ) . '" id="' . esc_attr( $args['id'] ) . '" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $args['value'] ) . '" ' . $size_attr . '>';

	}

	/**
	 * Register meta boxes on the Contest editor
	 * Implements add_meta_boxes action
	 */
	public function add_meta_boxes() {
		add_meta_box( 'countdown-clock-settings', 'Settings', array( $this, 'countdown_clock_settings_metabox' ), GMR_COUNTDOWN_CLOCK_CPT, 'normal' );
	}

	public function countdown_clock_settings_metabox( \WP_Post $post ) {
		$post_id = $post->ID;
		$post_status = get_post_status_object( $post->post_status );

		wp_nonce_field( 'countdown_clock_meta_boxes', '__countdown_clock_nonce' );

		?><ul class="tabs">
			<li class="active"><a href="#counting-down">Counting Down</a></li>
			<li><a href="#countdown-reached">Countdown Reached</a></li>
			<li><a href="#countdown-target">Countdown Target</a></li>
		</ul>

		<div id="counting-down" class="tab active">
			<?php wp_editor( get_post_meta( $post_id, 'countdown-message', true ), 'greatermedia_countdown_clock_countdown_message' ); ?>
		</div>

		<div id="countdown-reached" class="tab">
			<?php wp_editor( get_post_meta( $post_id, 'reached-message', true ), 'greatermedia_countdown_clock_reached_message' ); ?>
		</div>

		<div id="countdown-target" class="tab">
			<?php $this->_restrictions_settings( $post ); ?>
		</div><?php
	}

	private function _restrictions_settings( \WP_Post $post ) {
		$post_status = get_post_status_object( $post->post_status );
		$datetime_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

		$started = get_post_meta( $post->ID, 'contest-start', true );
		$ended = get_post_meta( $post->ID, 'contest-end', true );
		$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

		?><table class="form-table">
			<tr>
				<th scope="row"><label for="greatermedia_countdown_date">Start date</label></th>
				<td>
					<?php if ( ! $post_status->public ) : ?>
						<?php $this->render_date_field( array(
							'post_id' => $post->ID,
							'id'      => 'greatermedia_countdown_date',
							'name'    => 'greatermedia_countdown_date',
							'value'   => get_post_meta( $post->ID, 'countdown-date', true )
						) ); ?>
					<?php else : ?>
						<b>
							<?php if ( ! empty( $started ) ) : ?>
								<?php echo date( $datetime_format, $started + $offset ); ?>
							<?php else : ?>
								&#8212;
							<?php endif; ?>
						</b>

						<?php if ( ! empty( $started ) ) : ?>
							<small style="margin-left:2em;">
								(server time is <?php echo date( $datetime_format, current_time( 'timestamp' ) ); ?>)
							</small>
						<?php endif; ?>
					<?php endif; ?>
				</td>
			</tr>
		</table><?php
	}

	/**
	 * Save meta fields on post save
	 *
	 * @param int $post_id
	 */
	public function save_post( $post_id ) {

		$post = get_post( $post_id );

		// Verify that the form nonce is valid.
		if ( ! wp_verify_nonce( filter_input( INPUT_POST, '__contest_nonce' ), 'contest_meta_boxes' ) ) {
			return;
		}

		// If this is an autosave, the editor has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Make sure the post type is correct
		if ( GMR_COUNTDOWN_CLOCK_CPT !== $post->post_type ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['contest_embedded_form'] ) ) {
			/**
			 * Update the form's meta field
			 * The form JSON has slashes in it which need to be stripped out.
			 * json_decode() and json_encode() are used here to sanitize the JSON & keep out invalid values
			 */
			$form = addslashes( json_encode( json_decode( urldecode( $_POST['contest_embedded_form'] ) ) ) );
			// PHP's json_encode() may add quotes around the encoded string. Remove them.
			$form = trim( $form, '"' );
			update_post_meta( $post_id, 'embedded_form', $form );
		}

		// Update the form's "submit button" text
		if ( isset( $_POST['greatermedia_contest_form_title'] ) ) {
			$form_title = $_POST['greatermedia_contest_form_title'];
			if ( empty( $form_title ) ) {
				$form_title = 'Enter Here to Win';
			}
			update_post_meta( $post_id, 'form-title', sanitize_text_field( $form_title ) );
		}

		// Update the form's "submit button" text
		if ( isset( $_POST['greatermedia_contest_form_submit'] ) ) {
			$submit_text = $_POST['greatermedia_contest_form_submit'];
			if ( empty( $submit_text ) ) {
				$submit_text = 'Submit';
			}
			update_post_meta( $post_id, 'form-submitbutton', sanitize_text_field( $submit_text ) );
		}

		// Update the form's "thank you" message
		if ( isset( $_POST['greatermedia_contest_form_thankyou'] ) ) {
			$thank_you = $_POST['greatermedia_contest_form_thankyou'];
			if ( empty( $thank_you ) ) {
				$thank_you = 'Thanks for entering!';
			}
			update_post_meta( $post_id, 'form-thankyou', sanitize_text_field( $thank_you ) );
		}

		// Update the contest rules meta fields
		update_post_meta( $post_id, 'prizes-desc', wp_kses_post( $_POST['greatermedia_contest_prizes'] ) );
		update_post_meta( $post_id, 'how-to-enter-desc', wp_kses_post( $_POST['greatermedia_contest_enter'] ) );
		update_post_meta( $post_id, 'rules-desc', wp_kses_post( $_POST['greatermedia_contest_rules'] ) );
		update_post_meta( $post_id, 'contest_type', filter_input( INPUT_POST, 'contest_type' ) );

		$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		$dates = array(
			'contest-start' => 'greatermedia_contest_start',
			'contest-end'   => 'greatermedia_contest_end',
		);

		foreach ( $dates as $meta => $param ) {
			if ( isset( $_POST[ $param ]['date'] ) ) {
				$value = 0;
				if ( ! empty( $_POST[ $param ]['date'] ) ) {
					$value = $_POST[ $param ]['date'];
					if ( ! empty( $_POST[ $param ]['time'] ) ) {
						$value .= ' ' . $_POST[ $param ]['time'];
					}

					$value = strtotime( $value ) - $offset; // convert to timestamp and UTC
				}

				update_post_meta( $post_id, $meta, $value );
			}
		}

		$members_only = filter_input( INPUT_POST, 'greatermedia_contest_members_only', FILTER_VALIDATE_BOOLEAN );
		update_post_meta( $post_id, 'contest-members-only', $members_only );

		$single_entry = filter_input( INPUT_POST, 'greatermedia_contest_single_entry', FILTER_VALIDATE_BOOLEAN );
		update_post_meta( $post_id, 'contest-single-entry', $single_entry );

		$max_etries = filter_input( INPUT_POST, 'greatermedia_contest_max_entries', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1, 'default' => '' ) ) );
		update_post_meta( $post_id, 'contest-max-entries', $max_etries );

		$min_age = filter_input( INPUT_POST, 'greatermedia_contest_min_age', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 1, 'default' => '' ) ) );
		update_post_meta( $post_id, 'contest-min-age', $min_age );

		$show_submission_details = filter_input( INPUT_POST, 'show-submission-details', FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'default' => 1 ) ) );
		update_post_meta( $post_id, 'show-submission-details', $show_submission_details );
	}

}

$GreaterMediaCountdownClockMetaboxes = new GreaterMediaCountdownClockMetaboxes();
