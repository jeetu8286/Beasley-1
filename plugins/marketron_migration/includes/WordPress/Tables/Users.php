<?php

namespace WordPress\Tables;

class Users extends BaseTable {

	public $columns = array(
		'ID',
		'user_login',
		'user_pass',
		'user_nicename',
		'user_email',
		'user_url',
		'user_registered',
		'user_activation_key',
		'user_status',
		'display_name',
	);

	public $indices = array(
		'user_email',
		'display_name',
	);

	function get_table_name() {
		return 'users';
	}

	function get_prefixed_table_name() {
		global $wpdb;
		return $wpdb->base_prefix . $this->get_table_name();
	}

	function add( $fields ) {
		$fields = parent::add( $fields );
		return $fields;
	}

}
