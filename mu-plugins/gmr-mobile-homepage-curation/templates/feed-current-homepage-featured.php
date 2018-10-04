<?php
header( 'Content-Type: ' . feed_content_type( 'rss2' ) . '; charset=' . get_option( 'blog_charset' ), true );
echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';
$current = 1; ?>
<rotator>
	<?php while ( have_posts() ) : the_post(); ?>
		<panel>
			<number><?php echo esc_html( $current ++ ); ?></number>
			<image><?php bbgi_post_thumbnail_url( null, true, 640, 400 ); ?></image>
			<url><?php the_permalink_rss() ?></url>
			<redirect>n</redirect>
		</panel>
	<?php endwhile; ?>
</rotator>
