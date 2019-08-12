<?php

namespace Bbgi;

class Webhooks extends \Bbgi\Module {

	/**
	 * Registers this module.
	 *
	 * @access public
	 */
	public function register() {
		add_action( 'save_post', array( $this, 'do_save_post_webhook' ) );
		add_action( 'wp_trash_post', array( $this, 'do_trash_post_webhook' ) );
		add_action( 'delete_post', array( $this, 'do_delete_post_webhook' ) );
	}

	/**
	 * Helper to trigger save webhook and unregister self.
	 *
	 * @param int $post_id The Post id that changed
	 */
	public function do_save_post_webhook( $post_id ) {
		remove_action( 'save_post', [ $this, 'do_save_post_webhook' ] );

		$this->do_webhook( $post_id, [ 'source' => 'save_post' ] );
	}

	/**
	 * Helper to trigger trash webhook and unregister self.
	 *
	 * @param int $post_id The Post id that changed
	 */
	public function do_trash_post_webhook( $post_id ) {
		remove_action( 'wp_trash_post', [ $this, 'do_trash_post_webhook' ] );

		$this->do_webhook( $post_id, [ 'source' => 'wp_trash_post' ] );
	}

	/**
	 * Helper to trigger delete webhook and unregister self.
	 *
	 * @param int $post_id The Post id that changed
	 */
	public function do_delete_post_webhook( $post_id ) {
		remove_action( 'delete_post', [ $this, 'do_delete_post_webhook' ] );

		$this->do_webhook( $post_id, [ 'source' => 'delete_post' ] );
	}

	/**
	 * Sends webhook notification when a post is published, changed, trashed, or deleted
	 *
	 * @access public
	 * @action save_post ($post_id)
	 * @action wp_trash_post ($post_id)
	 * @action delete_post ($post_id)
	 * @param int $post_id The source post that changed
	 * @param array $opts Optional opts
	 * @return void
	 */
	public function do_webhook( $post_id, $opts = [] ) {
		if ( ! $this->needs_webhook( $post_id ) ) {
			return false;
		}

		$post = get_post( $post_id );
		if ( $post->post_status !== 'publish' ) {
			return;
		}

		$base_url  = get_site_option( 'ee_host', false );
		$appkey    = get_site_option( 'ee_appkey', false );
		$publisher = get_option( 'ee_publisher', false );

		// Abort if notification URL isn't set
		if ( ! $base_url || ! $publisher || ! $appkey ) {
			return;
		}

		$url = trailingslashit( $base_url ) . 'admin/publishers/' . $publisher . '/build?appkey=' . $appkey;

		wp_remote_post( $url, array(
			'blocking' => false,
		) );
	}

	/**
	 * Determines if the specified post & context needs a webhook push.
	 *
	 * @param int $post_id The post id.
	 * @return bool
	 */
	public function needs_webhook( $post_id ) {
		/* autosaves don't need webhook */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		/** don't webhook bulk edit requests */
		if ( isset( $_REQUEST['bulk_edit'] ) ) {
			return false;
		}

		$supported = $this->get_supported_post_types();
		$post_type = get_post_type( $post_id );

		return in_array( $post_type, $supported, true );
	}

	/**
	 * Webhook is only supported for the following post types.
	 *
	 * @return array
	 */
	public function get_supported_post_types() {
		return [
			'post',
			'page',
			'attachment',
			'gmr_gallery',
			'gmr_album',
			'episode',
			'tribe_events',
			'subscription',
			'content-kit',
			'contest',
			'songs',
			'show',
			'gmr_homepage',
			'gmr_mobile_homepage',
			'podcast',
		];
	}

}
