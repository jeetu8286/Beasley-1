<?php 
/**
 * Entry partial
 * 
 * This is a work-in-progress and will eventually be broken into multiple 
 * partials for the individual formats and post types. But for now this one 
 * covers them all. 
 */
$layout = sprintf( 'gmr_category_%d_layout', get_queried_object_id() );
$layout = get_option( $layout );
if ( ! empty( $layout ) ) {
	$layout = 'category-' . $layout;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry2' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
	
	

	<footer class="entry2__footer">
		<?php
		$category = get_the_category();

		if( isset( $category[0] ) ){
			echo '<a href="' . esc_url( get_category_link($category[0]->term_id ) ) . '" class="entry2__footer--category">' . esc_html( $category[0]->cat_name ) . '</a>';
		}
		?>

		<a href="<?php the_permalink(); ?>" class="entry2__footer--read-more"><?php _e( 'read more', 'greatermedia' ); ?></a>
	</footer>
</article>
