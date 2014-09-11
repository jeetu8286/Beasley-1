<?php

namespace GreaterMedia\Gigya;

require_once __DIR__ . '/class-meta-box.php';

/**
 * QueryBuilderMetaBox is a javascript based meta box that displays a
 * user interface to assemble the constraints of a MemberQuery.
 *
 * It is a normal priority meta box displayed in the main content area.
 *
 * @package GreaterMedia\Gigya
 */
class QueryBuilderMetaBox extends MetaBox {

	public function get_id() {
		return 'query_builder';
	}

	public function get_title() {
		return 'Gigya Social';
	}

	public function get_context() {
		return 'normal';
	}

	public function get_priority() {
		return 'default';
	}

	public function get_template() {
		return 'query_builder';
	}

}
