<?php

namespace GmrGigya;

class GigyaSocialMetaBox {

	function register() {
		add_meta_box(
			'gmr_gigya_social',
			__( 'Gigya Social', 'gmr_gigya' ),
			array( $this, 'display' ),
			'member_query',
			'normal'
		);
	}

	function render() {
		return 'Gigya Social Meta Box';
	}

	function display() {
		echo $this->render();
	}

}
