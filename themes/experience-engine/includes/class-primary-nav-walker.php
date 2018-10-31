<?php

class PrimaryNavWalker extends \Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		parent::start_lvl( $output, $depth, $args );
		$output = str_replace( '<ul ', '<ul aria-hidden="true" aria-label="Submenu" ', $output );
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$element = '';
		parent::start_el( $element, $item, $depth, $args, $id );

		if ( in_array( 'menu-item-has-children', $item->classes ) ) {
			$element = str_replace( '<a ', '<a aria-haspopup="true" ', $element );
			$element .= '<button class="sub-menu-activator"></button>';
		}

		$output .= $element;
	}

}
