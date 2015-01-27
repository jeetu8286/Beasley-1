<?php
	global $post;
	$args = array(
		'numberposts' => 500,
		'offset' => 0,
		'orderby' => 'post_date',
		'order' => 'DESC',
		'post_type' => GMP_CPT::EPISODE_POST_TYPE,
		'post_status' => 'publish',
		'post_parent' => $post->ID
	);

	$episodes =  new WP_Query( $args );
	$recent_posts = $episodes->posts[0];
	$episode_date = strtotime( $recent_posts->post_date );
?>
<section class="podcast__meta">
	<h3 class="podcast__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<div class="podcast__parent--title">Latest Episode: <a href="<?php the_permalink(); ?>"><?php echo $recent_posts->post_title; ?></a></div>
	<div class="podcast__desc">
		<?php the_excerpt(); ?>
	</div>
	<div class="podcast__date">Last Episode Date: <?php echo date( 'F j', $episode_date ) ?></div>
	<br>
	<div class="podcast__date">Number of Episodes: <?php echo $episodes->found_posts; ?></div>
</section>