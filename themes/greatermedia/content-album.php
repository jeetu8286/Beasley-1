<?php while ( have_posts() ) : the_post(); ?>

	<div class="container">

		<?php if ( has_post_thumbnail() ) {

				the_post_thumbnail( 'full', array( 'class' => 'single__featured-img' ) );

			}
		?>

		<section class="content">

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

				<div class="ad__inline--right">
					<img src="http://placehold.it/300x250&amp;text=inline ad">
				</div>

				<header class="entry__header">

					<time class="entry__date" datetime="<?php echo get_the_time(); ?>"><?php the_date('F j'); ?></time>
					<h2 class="entry__title" itemprop="headline"><?php the_title(); ?></h2>
					<a class="icon-facebook social-share-link" href="http://www.facebook.com/sharer/sharer.php?u=[URL]&title=[TITLE]"></a>
					<a class="icon-twitter social-share-link" href="http://twitter.com/home?status=[TITLE]+[URL]"></a>
					<a class="icon-google-plus social-share-link" href="https://plus.google.com/share?url=[URL]"></a>

				</header>

				<section class="entry-content" itemprop="articleBody">

					<?php the_content(); ?>

				</section>

				<?php get_template_part( 'partials/post', 'footer' ); ?>

			</article>

			<?php
			$post_id = get_query_var( 'post_id' );

			$children_args = array(
				'post_type' => GreaterMediaGalleryCPT::GALLERY_POST_TYPE,
				'post_parent' => $post_id,
				'fields' => 'title',
				'limit' => 1000,
				'orderby' => 'menu_order',
				'order' => 'ASC',
			);

			$children_galleries = get_posts( $children_args );

			foreach ( $children_galleries as $post ) : setup_postdata( $post ); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class( 'gallery__grid--column' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

					<div class="gallery__grid--thumbnail">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'gmr-gallery-grid-thumb' ); ?>
						</a>
					</div>

					<div class="gallery__grid--meta">
						<h3 class="gallery__grid--title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
					</div>

				</article>
			<?php endforeach;
			wp_reset_postdata();?>

		</section>

	</div>

<?php endwhile; ?>
