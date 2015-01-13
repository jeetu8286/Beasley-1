<?php

// action hooks
add_action( 'admin_menu', 'gmr_contests_register_winners_page' );

// filter hooks
add_filter( 'post_row_actions', 'gmr_contests_add_table_row_actions', 10, 2 );
add_filter( 'parent_file', 'gmr_contests_adjust_winners_page_admin_menu' );

/**
 * Adds table row actions to contest records.
 *
 * @filter post_row_actions
 * @param array $actions The initial array of post actions.
 * @param WP_Post $post The post object.
 * @return array The array of post actions.
 */
function gmr_contests_add_table_row_actions( $actions, WP_Post $post ) {
	// do nothing if it is not a contest object
	if ( GMR_CONTEST_CPT != $post->post_type ) {
		return $actions;
	}

	// add contest winners action
	$link = admin_url( 'admin.php?page=gmr-contest-winner&contest_id=' . $post->ID );
	$actions['gmr-contest-winner'] = '<a href="' . esc_url( $link ) . '">Winners</a>';

	return $actions;
}

/**
 * Registers contest winner page in the system.
 *
 * @action admin_menu
 * @global array $_registered_pages The array of already registered pages.
 */
function gmr_contests_register_winners_page() {
	global $_registered_pages;

	$page_hook = get_plugin_page_hookname( 'gmr-contest-winner', '' );
	$_registered_pages[ $page_hook ] = true;

	add_action( $page_hook, 'gmr_contests_render_winner_page' );
}

/**
 * Renders contest winner selection page.
 */
function gmr_contests_render_winner_page() {
	$contest = filter_input( INPUT_GET, 'contest_id', FILTER_VALIDATE_INT );
	if ( ! $contest || ! ( $contest = get_post( $contest ) ) || GMR_CONTEST_CPT != $contest->post_type ) {
		wp_die( 'Contest has not been found.' );
	}

	$entries = filter_input( INPUT_GET, 'entries', FILTER_VALIDATE_INT, array( 'options' => array(
		'min_range' => 1,
		'default'   => 5,
	) ) );

	$gender = strtolower( filter_input( INPUT_GET, 'gender' ) );
	if ( $gender != 'm' && $gender != 'f' ) {
		$gender = null;
	}

	$aged_from = filter_input( INPUT_GET, 'aged_from', FILTER_VALIDATE_INT, array(
		'options' => array(
			'min_range' => 12,
			'max_range' => 55,
			'default'   => null,
		),
	) );

	$aged_to = filter_input( INPUT_GET, 'aged_to', FILTER_VALIDATE_INT, array(
		'options' => array(
			'min_range' => 12,
			'default'   => null,
		),
	) );

	$table = new GMR_Contest_Winners_Table( array(
		'contest_id' => $contest->ID,
		'entries'    => $entries,
		'gender'     => $gender,
		'aged_from'  => $aged_from,
		'aged_to'    => $aged_to,
	) );

	$table->prepare_items();

	?><div id="contest-winner-selection" class="wrap">
		<h2>
			<?php echo esc_html( $contest->post_title ); ?>: Winners
			<a class="add-new-h2" href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . GMR_CONTEST_CPT ) ); ?>">
				Back to Contests
			</a>
		</h2>

		<form id="winners-query-form">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
			<input type="hidden" name="contest_id" value="<?php echo $contest->ID; ?>">

			<input type="submit" class="button button-primary" value="Select Winner(s)">

			Select 
			<input type="text" name="entries" class="small-text" value="<?php echo esc_attr( $entries ); ?>">
			# of random
			<select name="gender">
				<option value="">Male or Female</option>
				<option value="m"<?php selected( 'm', $gender ); ?>>Male Only</option>
				<option value="f"<?php selected( 'f', $gender ); ?>>Female Only</option>
			</select>
			winners aged from
			<select name="aged_from">
				<option value="">---</option>
				<?php for ( $i = 12; $i <= 55; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>"<?php selected( $i, $aged_from ); ?>>
						<?php echo esc_html( $i ); ?>
					</option>
				<?php endfor; ?>
			</select>
			to
			<select name="aged_to">
				<option value="">---</option>
				<?php for ( $i = 12; $i <= 55; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>"<?php selected( $i, $aged_to ); ?>>
						<?php echo esc_html( $i ); ?>
					</option>
				<?php endfor; ?>
				<option value="99"<?php selected( $aged_to > 55 ); ?>>56+</option>
			</select>
		</form>

		<?php $table->display(); ?>
	</div><?php
}

/**
 * Adjustes parent and submenu files.
 *
 * @filter parent_file
 * @global string $submenu_file The current submenu page.
 * @param string $parent_file The parent file name.
 * @return string The parent file.
 */
function gmr_contests_adjust_winners_page_admin_menu( $parent_file ) {
	global $submenu_file;

	if ( ! empty( $_REQUEST['page'] ) && 'gmr-contest-winner' == $_REQUEST['page'] ) {
		$parent_file = 'edit.php?post_type=' . GMR_CONTEST_CPT;
		$submenu_file = 'edit.php?post_type=' . GMR_CONTEST_CPT;
	}

	return $parent_file;
}

// include list table class file if it hasn't been included yet
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

/**
 * Contest winners table.
 */
class GMR_Contest_Winners_Table extends WP_List_Table {

	public function prepare_items() {
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $this->get_columns(), array(), $sortable );

		$args = array(
			'post_type'           => GMR_CONTEST_ENTRY_CPT,
			'post_status'         => 'any',
			'post_parent'         => $this->_args['contest_id'],
			'posts_per_page'      => $this->_args['entries'],
			'ignore_sticky_posts' => true,
			'orderby'             => 'rand',
			'meta_query'          => array( 'relation' => 'AND' ),
		);

		if ( ! empty( $this->_args['gender'] ) ) {
			$args['meta_query'][] = array(
				'key'   => 'entrant_gender',
				'value' => $this->_args['gender'],
			);
		}

		if ( ! empty( $this->_args['aged_from'] ) ) {
			$args['meta_query'][] = array(
				'key'     => 'entrant_birth_year',
				'value'   => date( 'Y' ) - $this->_args['aged_from'],
				'compare' => '<=',
				'type'    => 'NUMERIC',
			);
		}

		if ( ! empty( $this->_args['aged_to'] ) ) {
			$args['meta_query'][] = array(
				'key'     => 'entrant_birth_year',
				'value'   => date( 'Y' ) - $this->_args['aged_to'],
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}

		$query = new WP_Query();
		$this->items = $query->query( $args );
	}

	public function column_name( WP_Post $entry ) {
		$author = gmr_contest_get_entry_author( $entry->ID );
		$actions = array(
			'mark-winner' => '<a href="#">Mark as Winner</a>',
			'disqualify'  => '<a href="#">Disqualify</a>',
		);
		
		return '<b>' . $author . '</b>' . $this->row_actions( $actions );
	}

	public function column_gender( WP_Post $entry ) {
		$gender = get_post_meta( $entry->ID, 'entrant_gender', true );
		
		if ( 'm' == $gender ) {
			return 'Male';
		}
		
		if ( 'f' == $gender ) {
			return 'Female';
		}

		return '&#8212;';
	}

	public function column_email( WP_Post $entry ) {
		$email = gmr_contest_get_entry_author_email( $entry->ID );
		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return '&#8212;';
		}

		return sprintf( '<a href="mailto:%1$s" title="%1$s">%1$s</a>', $email );
	}

	public function column_submitted( WP_Post $entry ) {
		return sprintf(
			'<span title="%s">%s ago</span>',
			mysql2date( 'M j, Y H:i', $entry->post_date ),
			human_time_diff( strtotime( $entry->post_date ), current_time( 'timestamp' ) )
		);
	}

	public function column_default( WP_Post $entry, $column_name ) {
		$fields = GreaterMediaFormbuilderRender::parse_entry( $entry->post_parent, $entry->ID );
		if ( isset( $fields[ $column_name ] ) ) {
			$value = $fields[ $column_name ]['value'];
			if ( 'file' == $fields[ $column_name ]['type'] ) {
				return wp_get_attachment_image( $value, array( 75, 75 ) );
			} elseif ( is_array( $value ) ) {
				return implode( ', ', array_map( 'esc_html', $value ) );
			} else {
				return esc_html( $value );
			}
		}

		return '&#8212;';
	}

	public function get_columns() {
		$columns = array(
			'name'   => 'Name',
			'email'  => 'Email',
			'gender' => 'Gender',
		);

		$form = get_post_meta( $this->_args['contest_id'], 'embedded_form', true );
		if ( ! empty( $form ) ) {
			if ( is_string( $form ) ) {
				$clean_form = trim( $form, '"' );
				$form = json_decode( $clean_form );
			}

			foreach ( $form as $field ) {
				$columns[ $field->cid ] = $field->label;
			}
		}

		$columns['submitted'] = 'Submitted';

		return $columns;
	}

}