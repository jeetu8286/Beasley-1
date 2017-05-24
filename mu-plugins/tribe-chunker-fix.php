<?php
/**
 * Tribe added a new "Chunker" that tries to split post meta among many keys, and automatically combine when querying
 * This basically killed our database, and didn't ACTUALLY end up splitting anything, because our infrastructure
 * is actually capable of storing lots of data.
 *
 * Disabling this new functionality by removing support from all the post types.
 *
 * Verified this fixed the excessive CPU load as well.
 *
 * 5/22/2017 - Chris Marslender
 */

add_filter( 'tribe_meta_chunker_post_types', function( $post_types ) {
        return array();
}, 15 );
