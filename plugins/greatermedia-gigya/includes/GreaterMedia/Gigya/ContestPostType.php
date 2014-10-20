<?php

namespace GreaterMedia\Gigya;

/**
 * ContestPostType => post_type = 'contest'. Curently only for managing
 * the meta boxes for this post type.
 *
 * TODO: cleanup duplication
 */
class ContestPostType {

	public $meta_boxes = array();

	/**
	 * Lazy initializes the meta boxes for this post_type. This keeps the
	 * footprint down on the POST request, since we don't need to
	 * register the meta boxes there.
	 *
	 * For the POST request, we'll use null data. For those
	 * requests the meta boxes only do nonce verification.
	 *
	 * @access public
	 * @param mixed $data
	 * @return array Associative array of meta box objects
	 */
	public function get_meta_boxes( $data = null ) {
		if ( count( $this->meta_boxes ) === 0 ) {
			$this->meta_boxes = array();

			$this->meta_boxes['contest_form'] = $this->meta_box_for(
				array(
					'post_type' => 'contest',
					'id'       => 'contest_form',
					'title'    => __( 'Contest Form', 'gmr_gigya' ),
					'context'  => 'side',
					'priority' => 'default',
					'template' => 'contest_form',
				), $data
			);
		}

		return $this->meta_boxes;
	}

	/**
	 * Registers the meta boxes with the associated data object.
	 *
	 * @access public
	 * @param mixed $data The data to associate with a metabox.
	 * @return void
	 */
	public function register_meta_boxes( $data ) {
		$meta_boxes = $this->get_meta_boxes( $data );

		foreach ( $meta_boxes as $meta_box ) {
			$meta_box->register();
		}
	}

	/**
	 * Verifies than correct nonces were passed for each MetaBox.
	 *
	 * Exits script execution with a warning if invalid.
	 *
	 * @access public
	 * @return void
	 */
	public function verify_meta_box_nonces() {
		$meta_boxes = $this->get_meta_boxes( null );

		foreach ( $meta_boxes as $meta_box ) {
			$result = $meta_box->verify_nonce();
			// only runs in PHPUnit, since the script has already
			// ended execution before, if the nonce was invalid
			if ( ! $result ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Builds a new meta box for the specified params.
	 *
	 * @access public
	 * @param array $params The params to pass to the meta box object
	 * @param mixed $data The data associated with the meta box.
	 * @return MetaBox
	 */
	public function meta_box_for( $params, $data ) {
		$meta_box = new MetaBox( $data );
		$meta_box->params = $params;

		return $meta_box;
	}
}
