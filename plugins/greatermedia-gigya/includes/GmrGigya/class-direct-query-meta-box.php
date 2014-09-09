<?php

namespace GmrGigya;

class DirectQueryMetaBox {

	function register() {
		add_meta_box(
			'post_submit_metabox',
			__( 'Direct Query', 'gmr_gigya' ),
			array( $this, 'display' ),
			'member_query',
			'side',
			'low'
		);
	}

	function render() {
		return '<input type="hidden" name="direct_query" value="direct_query_value" />';
	}

	function display() {
		echo $this->render();
	}

}
