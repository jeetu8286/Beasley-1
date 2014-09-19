<?php

namespace GreaterMedia\Gigya;

//require_once __DIR__ . '/class-meta-box.php';

/**
 * DirectQueryMetaBox is the meta box for overriding the QueryBuilder
 * generated GQL.
 *
 * It is a low priority meta box that is displayed in the side bar.
 *
 * @package GreaterMedia\Gigya
 */
class DirectQueryMetaBox extends MetaBox {

	public function get_id() {
		return 'direct_query';
	}

	public function get_title() {
		return 'Direct Query';
	}

	public function get_context() {
		return 'side';
	}

	public function get_priority() {
		return 'low';
	}

	public function get_template() {
		return 'direct_query';
	}

}
