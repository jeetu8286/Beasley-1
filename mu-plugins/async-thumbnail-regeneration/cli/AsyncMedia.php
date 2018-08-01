<?php

namespace TenUp\AsyncThumbnails\CLI;

use TenUp\AsyncThumbnails\Regenerate;

class AsyncMedia {

    public function regenerate( $args, $assoc_args = array() ) {
        if ( ! function_exists( 'wp_async_task_add' ) ) {
            \WP_CLI::error( "WP Minions not found." );
        }

        if ( empty( $args ) ) {
            \WP_CLI::confirm( 'Do you really want to regenerate all images?' );
        }

        $query_args = array(
            'post_type' => 'attachment',
            'post__in' => $args,
            'post_mime_type' => array( 'image' ),
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'ids'
        );

        $images = new \WP_Query( $query_args );

        $count = $images->post_count;

        if ( ! $count ) {
            \WP_CLI::warning( 'No images found.' );
            return;
        }

        \WP_CLI::log( sprintf( 'Found %1$d %2$s to regenerate.', $count, _n( 'image', 'images', $count ) ) );

        foreach ( $images->posts as $id ) {
            \WP_CLI::log( " - Adding Attachment ID {$id} to queue." );
            wp_async_task_add( Regenerate::ASYNC_ACTION, array( 'image_id' => $id ), 'low' );
        }

        \WP_CLI::success( "All media queued for thumbnail regeneration." );
    }

}

\WP_CLI::add_command( 'async-media', '\TenUp\AsyncThumbnails\CLI\AsyncMedia' );
