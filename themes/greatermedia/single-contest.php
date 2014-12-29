<?php
/**
 * Single Post template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<main class="main" role="main">

			<?php if ( defined( 'GREATER_MEDIA_GIGYA_TEST_UI' ) && GREATER_MEDIA_GIGYA_TEST_UI ) {
				if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div class="container">

				<section class="content">

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'full', array( 'class' => 'single__featured-img--contest' ) ); ?>
							<?php endif; ?>

							<section class="col__inner--left">

								<header class="entry__header">
									<?php $encoded_permalink = urlencode( get_permalink() ); ?>
									<?php $encoded_title = urlencode( get_the_title() ); ?>

									<time class="entry__date" datetime="<?php echo get_the_time(); ?>"><?php the_date( 'F j' ); ?></time>
									<h2 class="entry__title" itemprop="headline"><?php the_title(); ?></h2>

									<a class="icon-facebook social-share-link" href="http://www.facebook.com/sharer/sharer.php?u=<?php echo $encoded_permalink; ?>&title=<?php echo $encoded_title; ?>"></a>
									<a class="icon-twitter social-share-link" href="http://twitter.com/home?status=<?php echo $encoded_title; ?>+<?php echo $encoded_permalink; ?>"></a>
									<a class="icon-google-plus social-share-link" href="https://plus.google.com/share?url=<?php echo $encoded_permalink; ?>"></a>

								</header>

								<?php if ( ( $contest_prize = trim( get_post_meta( get_the_ID(), 'prizes-desc', true ) ) ) ) : ?>
									<h4>What you win:</h4>
									<?php echo wpautop( $contest_prize ); ?>
								<?php endif; ?>

								<?php if ( ( $contest_rules = trim( get_post_meta( get_the_ID(), 'rules-desc', true ) ) ) ) : ?>
									<p>
										<a class="contest-attr--rules-toggler" href="#" data-toggle="collapse" data-target="#contest-rules" data-alt-text="Hide Contest Rules">
											Show Contest Rules
										</a>
									</p>
									
									<div id="contest-rules" class="contest-attr--rules" style="display:none;"><?php echo wpautop( $contest_rules ); ?></div>
								<?php endif; ?>

								<?php the_content(); ?>

							</section>


							<section id="contest-form" class="col__inner--right">
								LOADING... <?php //TODO: replace with a spinner ?>
							</section>

							<?php get_template_part( 'partials/submission', 'tiles' ); ?>

						</article>

					<?php endwhile;

					else : ?>

						<article id="post-not-found" class="hentry cf">

							<header class="article-header">

								<h1><?php _e( 'Oops, Post Not Found!', 'greatermedia' ); ?></h1>

							</header>

							<section class="entry-content">

								<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'greatermedia' ); ?></p>

							</section>

						</article>

					<?php endif;
				} else if ( true /*is_gigya_user_logged_in()*/ ) {

					if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

							<header class="entry-header">

								<h2 class="entry-title" itemprop="headline">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

							</header>

							<?php

							$post_id         = get_the_ID();
							$contest_form_id = get_post_meta( $post_id, 'contest_form_id', true );

							if ( $contest_form_id ) {
								gravity_form( $contest_form_id );
							}

							?>

						</article>

					<?php endwhile;

					else : ?>

						<article id="post-not-found" class="hentry cf">

							<header class="article-header">

								<h1><?php _e( 'Oops, Post Not Found!', 'greatermedia' ); ?></h1>

							</header>

							<section class="entry-content">

								<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'greatermedia' ); ?></p>

							</section>

						</article>

					<?php endif;
				} else {

					echo '<article><h3>Please login</h3></article>';

				} ?>

			</section>

		</div>

	</main>

<?php get_footer();