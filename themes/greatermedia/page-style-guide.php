<?php
/**
 * The main template file
 *
 * @package Greater Media
 * @since 0.1.0
 */
 
get_header( 'styleguide' ); ?>

<section id="colors" class="styleguide-colors styleguide-sections">
	<div class="styleguide-content">
		<h2 class="styleguide-section-title"><?php _e( 'Colors', 'greatermedia' ); ?></h2>
		<ul class="styleguide-color-list">
			<li class="styleguide-color-swatch white">
				<span class="styleguide-color-swatch-variable"><?php _e( '$white', '' ); ?></span>
				<span class="styleguide-color-swatch-hex"><?php _e( '#ffffff', '' ); ?></span>
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch light-gray">
				<span class="styleguide-color-swatch-variable"><?php _e( '$light-gray', '' ); ?></span>
				<span class="styleguide-color-swatch-hex"><?php _e( '#f5f5f5', '' ); ?></span>
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch black">
				<span class="styleguide-color-swatch-variable"><?php _e( '$black', '' ); ?></span>
				<span class="styleguide-color-swatch-hex"><?php _e( '#000000', '' ); ?></span>
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
			<li class="styleguide-color-swatch">
			</li>
		</ul>
	</div>
</section>

<section id="typography" class="styleguide-typography styleguide-sections">
	<div class="styleguide-content">
		<h2 class="styleguide-section-title"><?php _e( 'Typography', 'greatermedia' ); ?></h2>
	</div>

<section id="icons" class="styleguide-icons styleguide-sections">
	<div class="styleguide-content">
		<h2 class="styleguide-section-title"><?php _e( 'Icons', 'greatermedia' ); ?></h2>
	</div>
</section>

<section id="buttons" class="styleguide-buttons styleguide-sections">
	<div class="styleguide-content">
		<h2 class="styleguide-section-title"><?php _e( 'Buttons', 'greatermedia' ); ?></h2>
	</div>
</section>

<section id="forms" class="styleguide-forms styleguide-sections">
	<div class="styleguide-content">
		<h2 class="styleguide-section-title"><?php _e( 'Forms', 'greatermedia' ); ?></h2>
	</div>
</section>

<section id="navigations" class="styleguide-navigations styleguide-sections">
<div class="styleguide-content">
	<h2 class="styleguide-section-title"><?php _e( 'Navigations', 'greatermedia' ); ?></h2>
</div>

<section id="discussions" class="styleguide-discussions styleguide-sections">
	<div class="styleguide-content">
		<h2 class="styleguide-section-title"><?php _e( 'Discussions', 'greatermedia' ); ?></h2>
	</div>
</section>

<section id="layout" class="styleguide-layout styleguide-sections">
	<div class="styleguide-content">
		<h2 class="styleguide-section-title"><?php _e( 'Layout', 'greatermedia' ); ?></h2>
	</div>
</section>

<?php get_footer() ;