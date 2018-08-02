<?php get_header(); ?>

<div class="container">

	<section class="content"><?php
		global $wp_query;

		$count = $wp_query->found_posts;
		$search_query = get_search_query();
		$keyword_post_id = intval( get_post_with_keyword( $search_query ) );
		if ( $keyword_post_id != 0 ) :
			$count += 1;
		endif;

		if ( $keyword_post_id != 0 ):
			?><h3 class="search__keyword">
				Keyword:
				<span class="search__keyword--term">
					<?php echo esc_html( $search_query ); ?>
				</span>
			</h3>

			<div class="keyword__search--results">
				<?php do_action( 'keyword_search_result' ); ?>
			</div><?php
		endif;

		get_search_form();

		if ( have_posts() ) :
			?><h3 class="search__keyword">
				<?php echo intval( $count ); ?> Results Found:
			</h3><?php

			get_template_part( 'partials/loop', 'search' );
			greatermedia_load_more_button( array( 'partial_slug' => 'partials/loop-search' ) );
		else :
			if ( $keyword_post_id == 0 ):
				?><article id="post-not-found" class="hentry cf">
					<header class="article-header">
						<h1>No Results Found!</h1>
					</header>

					<section class="entry-content">
						<p>
							Try searching for something else, or click one of the links above.
						</p>
					</section>
				</article><?php
			endif;
		endif;
	?></section>

	<?php get_sidebar(); ?>
</div>

<?php get_footer();
