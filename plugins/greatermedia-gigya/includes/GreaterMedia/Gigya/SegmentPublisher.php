<?php

namespace GreaterMedia\Gigya;

class SegmentPublisher {

	public $member_query;
	public $mailchimp_api_key = 'd288a2356ce46a76c0afbc67b9f537ad-us9';
	public $mailchimp_api;
	public $list_api;
	public $gmr_list_id = 'bfac8cea78';

	function __construct( $member_query ) {
		$this->member_query  = $member_query;
		$opts = array(
			'CURLOPT_FOLLOWLOCATION' => false,
		);
		$this->mailchimp_api = new \Mailchimp( $this->mailchimp_api_key, $opts );
		$this->list_api      = new \Mailchimp_Lists( $this->mailchimp_api );
	}

	function publish() {
		if ( defined( 'GMR_PUBLISH_SEGMENTS' ) && GMR_PUBLISH_SEGMENTS === false ) {
			return;
		}

		$segment_id = $this->get_segment_id();
		$emails     = $this->get_accounts_to_publish();

		$this->list_api->staticSegmentReset( $this->gmr_list_id, $segment_id );

		if ( count( $emails ) > 0 ) {
			$this->list_api->staticSegmentMembersAdd(
				$this->gmr_list_id,
				$segment_id,
				$emails
			);
		}
	}

	/* helpers */
	function get_post_id() {
		return $this->member_query->post_id;
	}

	function get_post_title() {
		$post = get_post( $this->get_post_id() );
		return $post->post_title;
	}

	function get_segment_id() {
		$post_id = $this->get_post_id();
		$segment_id = get_post_meta( $post_id, 'mailchimp_segment_id', true );

		if ( $segment_id === '' ) {
			$segment_id = $this->create_segment( $this->get_post_title() );
			update_post_meta( $post_id, 'mailchimp_segment_id', $segment_id );
		}

		return $segment_id;
	}

	function create_segment( $name ) {
		$list = $this->list_api->staticSegmentAdd( $this->gmr_list_id, $name );
		return $list['id'];
	}

	function get_accounts_to_publish() {
		$query    = $this->member_query->to_gql();
		$searcher = new AccountsSearcher();
		$response = $searcher->search( $query );
		$accounts = json_decode( $response, true );
		$accounts = $accounts['results'];
		$emails   = array();

		foreach ( $accounts as $account ) {
			$emails[] = array( 'email' => $account['profile']['email'] );
		}

		return $emails;
	}

}
