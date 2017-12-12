<?php

namespace Beasley\Syndication\CLI;

use Cmmarslender\PostIterator\Logger;

class DetachPostIterator extends \Cmmarslender\PostIterator\PostIterator {

	/**
	 * File handle for csv file to write detached posts to
	 *
	 * @var
	 */
	public $csv_file;

	/**
	 * Dry run setting. If true, nothing is updated in the database.
	 *
	 * @var bool
	 */
	public $dry_run = false;

	public function __construct( $args, $csv_file, $dry_run = false ) {
		$this->csv_file = $csv_file;
		$this->dry_run = $dry_run;

		$this->write_csv_headers();

		parent::__construct( $args );
	}

	public function write_csv_headers() {
		$headers = array(
			'blog_id',
			'post_id',
			'post_title',
			'post_url',
			'source_site_id'
		);

		fputcsv( $this->csv_file, $headers );
	}

	public function process_post() {
		$post_id = $this->current_post_object->ID;

		\Cmmarslender\PostIterator\Logger::log( "Processing Post {$post_id}" );

		$syndication_old_data = get_post_meta( $post_id, 'syndication_old_data', true );
		if ( ! empty( $syndication_old_data ) ) {
			Logger::log( " - Post {$post_id} is syndicated" );
			$syndication_old_data = unserialize( $syndication_old_data );

			$source_site_id = $syndication_old_data['blog_id'];
			$source_post_id = $syndication_old_data['id'];

			switch_to_blog( $source_site_id );
			$source_post = get_post( $source_post_id );
			restore_current_blog();

			if ( $source_post->post_content != $this->current_post_object->post_content ) {
				$data = array(
					get_current_blog_id(),
					$this->current_post_object->ID,
					$this->current_post_object->post_title,
					get_the_permalink( $this->current_post_object->ID ),
					$source_site_id
				);
				fputcsv( $this->csv_file, $data );

				if ( $this->dry_run === false ) {
					Logger::log( " - Post {$post_id} is different than source site. Detaching." );
					update_post_meta( $post_id, 'syndication-detached', 'true' );
				} else {
					Logger::log( " - Post {$post_id} is different than source site." );
				}

			}
		}
	}

}
