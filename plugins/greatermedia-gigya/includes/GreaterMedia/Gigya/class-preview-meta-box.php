<?php

namespace GreaterMedia\Gigya;

require_once __DIR__ . '/class-meta-box.php';

/**
 * PreviewMetaBox displays the accounts matching the current query.
 *
 * It is a normal priority meta box displayed in the sidebar.
 */
class PreviewMetaBox extends MetaBox {

	public function get_id() {
		return 'preview';
	}

	public function get_title() {
		return 'Preview Results';
	}

	public function get_context() {
		return 'side';
	}

	public function get_priority() {
		return 'default';
	}

	public function get_template() {
		return 'preview';
	}

}
