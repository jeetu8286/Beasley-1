<?php

namespace TenUp\AsyncThumbnails;

use TenUp\AsyncThumbnails\Logger as WP_CLI; // Cheating, so I don't have to change the code from WP_CLI media functions

class Regenerate {

    const ASYNC_ACTION = 'async_thumbnail_process_image';

    public function setup() {
        add_action( self::ASYNC_ACTION, array( $this, 'process_image' ) );
    }

    /**
     * The callback for WP Minions to process an image
     *
     * @param $args
     */
    public function process_image( $args ) {
        $image_id = $args['image_id'];

        $successes = $errors = $skips = 0;
        $this->process_regeneration( $image_id, true, true, '', '', $successes, $errors, $skips );
    }

    /**
     * Regenerate thumbnails for a given ID
     *
     * Taken right from WP-CLI https://github.com/wp-cli/media-command/blob/master/src/Media_Command.php
     *
     * @param $id
     * @param $skip_delete
     * @param $only_missing
     * @param $image_size
     * @param $progress
     * @param $successes
     * @param $errors
     * @param $skips
     */
    function process_regeneration( $id, $skip_delete, $only_missing, $image_size, $progress, &$successes, &$errors, &$skips ) {
        $title = get_the_title( $id );
        if ( '' === $title ) {
            // If audio or video cover art then the id is the sub attachment id, which has no title.
            if ( metadata_exists( 'post', $id, '_cover_hash' ) ) {
                // Unfortunately the only way to get the attachment title would be to do a non-indexed query against the meta value of `_thumbnail_id`. So don't.
                $att_desc = sprintf( 'cover attachment (ID %d)', $id );
            } else {
                $att_desc = sprintf( '"(no title)" (ID %d)', $id );
            }
        } else {
            $att_desc = sprintf( '"%1$s" (ID %2$d)', $title, $id );
        }
        $thumbnail_desc = $image_size ? sprintf( '"%s" thumbnail', $image_size ) : 'thumbnail';
        $fullsizepath = get_attached_file( $id );
        if ( false === $fullsizepath || !file_exists( $fullsizepath ) ) {
            //WP_CLI::warning( "Can't find $att_desc." );
            $errors++;
            return;
        }
        $is_pdf = 'application/pdf' === get_post_mime_type( $id );
        $needs_regeneration = $this->needs_regeneration( $id, $fullsizepath, $is_pdf, $image_size, $skip_delete, $skip_it );
        if ( $skip_it ) {
            WP_CLI::log( "$progress Skipped $thumbnail_desc regeneration for $att_desc." );
            $skips++;
            return;
        }
        if ( $only_missing && ! $needs_regeneration ) {
            WP_CLI::log( "$progress No $thumbnail_desc regeneration needed for $att_desc." );
            $successes++;
            return;
        }
        $metadata = wp_generate_attachment_metadata( $id, $fullsizepath );
        if ( is_wp_error( $metadata ) ) {
            WP_CLI::warning( sprintf( '%s (ID %d)', $metadata->get_error_message(), $id ) );
            WP_CLI::log( "$progress Couldn't regenerate thumbnails for $att_desc." );
            $errors++;
            return;
        }
        // Note it's possible for no metadata to be generated for PDFs if restricted to a specific image size.
        if ( empty( $metadata ) && ! ( $is_pdf && $image_size ) ) {
            WP_CLI::warning( sprintf( 'No metadata. (ID %d)', $id ) );
            WP_CLI::log( "$progress Couldn't regenerate thumbnails for $att_desc." );
            $errors++;
            return;
        }
        if ( $image_size ) {
            if ( $this->update_attachment_metadata_for_image_size( $id, $metadata, $image_size ) ) {
                WP_CLI::log( "$progress Regenerated $thumbnail_desc for $att_desc." );
            } else {
                WP_CLI::log( "$progress No $thumbnail_desc regeneration needed for $att_desc." );
            }
        } else {
            wp_update_attachment_metadata( $id, $metadata );
            WP_CLI::log( "$progress Regenerated thumbnails for $att_desc." );
        }
        $successes++;
    }

    private function needs_regeneration( $att_id, $fullsizepath, $is_pdf, $image_size, $skip_delete, &$skip_it ) {
        // Assume not skipping.
        $skip_it = false;
        // Note: zero-length string returned if no metadata, for instance if PDF or non-standard image (eg an SVG).
        $metadata = wp_get_attachment_metadata($att_id);
        $image_sizes = $this->get_intermediate_image_sizes_for_attachment( $fullsizepath, $is_pdf, $metadata );
        // First check if no applicable editor currently available (non-destructive - ie old thumbnails not removed).
        if ( is_wp_error( $image_sizes ) && 'image_no_editor' === $image_sizes->get_error_code() ) {
            // Warn unless PDF or non-standard image.
            if ( ! $is_pdf && is_array( $metadata ) && ! empty( $metadata['sizes'] ) ) {
                WP_CLI::warning( sprintf( '%s (ID %d)', $image_sizes->get_error_message(), $att_id ) );
            }
            $skip_it = true;
            return false;
        }
        // If uploaded when applicable image editor such as Imagick unavailable, the metadata or sizes metadata may not exist.
        if ( ! is_array( $metadata ) ) {
            $metadata = array();
        }
        // If set `$metadata['sizes']` should be array but explicitly check as following code depends on it.
        if ( ! isset( $metadata['sizes'] ) || ! is_array( $metadata['sizes'] ) ) {
            $metadata['sizes'] = array();
        }
        // Remove any old thumbnails (so now destructive).
        if ( ! $skip_delete ) {
            $this->remove_old_images( $metadata, $fullsizepath, $image_size );
        }
        // Check for any other error (such as load error) apart from no editor available.
        if ( is_wp_error( $image_sizes ) ) {
            // Warn but assume it may be possible to regenerate and allow processing to continue and possibly fail.
            WP_CLI::warning( sprintf( '%s (ID %d)', $image_sizes->get_error_message(), $att_id ) );
            return true;
        }
        // Have sizes - check whether there're new ones or they've changed. Note that an attachment can have no sizes if it's on or below the thumbnail threshold.
        if ( $image_size ) {
            if ( empty( $image_sizes[ $image_size ] ) ) {
                return false;
            }
            if ( empty( $metadata['sizes'][ $image_size ] ) ) {
                return true;
            }
            $metadata['sizes'] = array( $image_size => $metadata['sizes'][ $image_size ] );
        }
        if ( $this->image_sizes_differ( $image_sizes, $metadata['sizes'] ) ) {
            return true;
        }
        $dir_path = dirname( $fullsizepath ) . '/';
        // Check that the thumbnail files exist.
        foreach( $metadata['sizes'] as $size_info ) {
            $intermediate_path = $dir_path . $size_info['file'];
            if ( $intermediate_path === $fullsizepath )
                continue;
            if ( ! file_exists( $intermediate_path ) ) {
                return true;
            }
        }
        return false;
    }

    // Update attachment sizes metadata just for a particular intermediate image size.
    private function update_attachment_metadata_for_image_size( $id, $new_metadata, $image_size ) {
        $metadata = wp_get_attachment_metadata( $id );
        if ( ! is_array( $metadata ) ) {
            return false;
        }
        // If have metadata for image_size.
        if ( ! empty( $new_metadata['sizes'][ $image_size ] ) ) {
            $metadata['sizes'][ $image_size ] = $new_metadata['sizes'][ $image_size ];
            wp_update_attachment_metadata( $id, $metadata );
            return true;
        }
        // Else remove unused metadata if any.
        if ( ! empty( $metadata['sizes'][ $image_size ] ) ) {
            unset( $metadata['sizes'][ $image_size ] );
            wp_update_attachment_metadata( $id, $metadata );
            // Treat removing unused metadata as no change.
        }
        return false;
    }

}
