<?php

if ( ! has_custom_logo() ) :
	return;
endif;

?>
<div class="primary-sidebar">
	<a href="#primary-menu" id="js-menu-toggle" class="site-menu-toggle">
		<span class="screen-reader-text">
			<?php esc_html_e( 'Primary Menu', 'tenup' ); ?>
		</span>
		<span aria-hidden="true">☰</span>
	</a>
	<div class="logo" itemscope itemtype="http://schema.org/Organization">
		<a itemprop="url" href="<?php the_permalink(); ?>">
			<?php the_custom_logo(); ?>
			<span class="screen-reader-text"><?php wp_title(); ?></span>
		</a>
	</div>
	<nav id="js-primary-nav" class="primary-nav" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" aria-hidden="false">
		<?php get_template_part( 'partials/primary', 'navigation' ); ?>
	</nav>
</div>
