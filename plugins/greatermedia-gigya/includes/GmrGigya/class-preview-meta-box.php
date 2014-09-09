<?php

namespace GmrGigya;

class PreviewMetaBox {

	function register() {
		add_meta_box(
			'gmr_gigya_preview',
			__( 'Preview Results', 'gmr_gigya' ),
			array( $this, 'display' ),
			'member_query',
			'side'
		);
	}

	function render() {
		return 'Preview Meta Box';
	}

	function display() {
		echo $this->render();
	}

}
