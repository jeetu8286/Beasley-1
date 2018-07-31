<?php

namespace TenUp\AsyncThumbnails\CLI;

class AsyncMedia {

    public function regenerate() {
        if ( ! function_exists( 'wp_async_task_add' ) ) {
            \WP_CLI::error( "WP Minions not found." );
        }

        // @todo actually queue things

        \WP_CLI::success( "All media queued for thumbnail regeneration." );
    }

}

\WP_CLI::add_command( 'async-media', '\TenUp\AsyncThumbnails\CLI\AsyncMedia' );
