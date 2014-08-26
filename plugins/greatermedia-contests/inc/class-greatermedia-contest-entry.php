<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaContestEntry {

	const COMMENT_TYPE = 'contest_entry';

	public $comment_data;

	private function __construct() {

		$this->comment_data                  = array();
		$this->comment_data['comment_type']  = self::COMMENT_TYPE;
		$this->comment_data['user_id']       = 1;
		$this->comment_data['comment_agent'] = 'Greater Media Web';

	}

	public static function for_comment_data( $post_id, $source_url, $author_name, $author_email, $author_ip = '127.0.0.1' ) {

		$comment                                       = new self();
		$comment->comment_data['comment_post_ID']      = $post_id;
		$comment->comment_data['comment_author']       = $author_name;
		$comment->comment_data['comment_author_email'] = $author_email;
		$comment->comment_data['comment_author_IP']    = $author_ip;
		$comment->comment_data['comment_author_url']   = $source_url;

		return $comment;

	}

	public static function for_comment_id( $comment_id ) {

		$comment_data          = get_comment( intval( $comment_id ) );
		$comment               = new self();
		$comment->comment_data = $comment_data;

		return $comment;

	}

	/**
	 * Save the comment to the database
	 * @return int comment ID
	 */
	public function save() {

		global $timestart;

		$filtered_comment_data = apply_filters( 'gm_contest_entry_data', $this->comment_data );
		if ( ! isset( $filtered_comment_data['comment_date'] ) || empty( $filtered_comment_data['comment_date'] ) ) {
			$filtered_comment_data['comment_date'] = date( 'Y-m-d H:i:s', intval( $timestart ) );
		}

		if ( isset( $filtered_comment_data['comment_ID'] ) ) {
			wp_update_comment( $filtered_comment_data );
		} else {
			$this->comment_data['comment_ID'] = wp_insert_comment( $filtered_comment_data );
		}

		return $this->comment_data['comment_ID'];

	}

	public static function register_hooks() {

		// Hide the custom comment type from queries
		add_filter( 'comments_clauses', array( __CLASS__, 'comments_clauses' ), 10, 2 );
		add_filter( 'comment_feed_where', array( __CLASS__, 'comment_feed_where' ), 10, 2 );
		add_filter( 'wp_count_comments', array( __CLASS__, 'wp_count_comments' ), 10, 2 );

	}

	/**
	 * Exclude notes (comments) on edd_payment post type from showing in Recent
	 * Comments widgets
	 *
	 * @param array $clauses          Comment clauses for comment query
	 * @param obj   $wp_comment_query WordPress Comment Query Object
	 *
	 * @return array $clauses Updated comment clauses
	 */
	public static function comments_clauses( $clauses, $wp_comment_query ) {

		global $wpdb;

		$clauses['where'] .= sprintf( ' AND comment_type != "%s"', self::COMMENT_TYPE );

		return $clauses;

	}

	/**
	 * Exclude notes (comments) on edd_payment post type from showing in comment feeds
	 *
	 * @param array $where
	 * @param obj   $wp_comment_query WordPress Comment Query Object
	 *
	 * @return array $where
	 */
	public static function comment_feed_where( $where, $wp_comment_query ) {

		global $wpdb;

		$where .= $wpdb->prepare( " AND comment_type != %s", self::COMMENT_TYPE );

		return $where;

	}

	/**
	 * Remove EDD Comments from the wp_count_comments function
	 *
	 * @access public
	 *
	 * @param array $stats   (empty from core filter)
	 * @param int   $post_id Post ID
	 *
	 * @return array Array of comment counts
	 */
	public static function wp_count_comments( $stats, $post_id ) {

		global $wpdb, $pagenow;

		$post_id = (int) $post_id;

		$stats = wp_cache_get( "comments-{$post_id}", 'counts' );

		if ( false !== $stats ) {
			return $stats;
		}

		$where = sprintf( 'WHERE comment_type != "%s"', self::COMMENT_TYPE );

		if ( $post_id > 0 ) {
			$where .= $wpdb->prepare( " AND comment_post_ID = %d", $post_id );
		}

		$count = $wpdb->get_results( "SELECT comment_approved, COUNT( * ) AS num_comments FROM {$wpdb->comments} {$where} GROUP BY comment_approved", ARRAY_A );

		$total    = 0;
		$approved = array( '0' => 'moderated', '1' => 'approved', 'spam' => 'spam', 'trash' => 'trash', 'post-trashed' => 'post-trashed' );
		foreach ( (array) $count as $row ) {
			// Don't count post-trashed toward totals
			if ( 'post-trashed' != $row['comment_approved'] && 'trash' != $row['comment_approved'] ) {
				$total += $row['num_comments'];
			}
			if ( isset( $approved[$row['comment_approved']] ) ) {
				$stats[$approved[$row['comment_approved']]] = $row['num_comments'];
			}
		}

		$stats['total_comments'] = $total;
		foreach ( $approved as $key ) {
			if ( empty( $stats[$key] ) ) {
				$stats[$key] = 0;
			}
		}

		$stats = (object) $stats;
		wp_cache_set( "comments-{$post_id}", $stats, 'counts' );

		return $stats;

	}

}

GreaterMediaContestEntry::register_hooks();