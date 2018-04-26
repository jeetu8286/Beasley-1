<?php
/**
 * Template Name: [hardcoded] Gallery
 */
get_header();

the_post(); ?>

	<!--
	@TODO Notes
	In this dummy markup, a centered ad spacer is injected every *2* slides.
	This needs to be configurable.

	The .gallery-top has a data attribute for the sidebar ad refresh interval.
	This needs to be configurable as well...?
	-->

	<!-- @TODO sidebar ad refresh interval -->
	<div class="swiper-container gallery-top" data-refresh-interval="3">
    <div class="swiper-wrapper">
			<!-- @TODO Each slide need data attributes for title, caption, slug, index -->
			<div data-index="0" class="swiper-slide" data-slug="slide-1-slug" data-title="Slide 1 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 1">
				<img src="https://placem.at/things?w=1600&h=1200&random=1" class="swiper-image">
			</div>
			<div data-index="1" class="swiper-slide" data-slug="slide-2-slug" data-title="Slide 2 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 2">
				<img src="https://placem.at/things?w=900&h=800&random=2" class="swiper-image">
			</div>

			<!-- @TODO Injected every 2 in this example, needs to be in .gallery-thumbs as well -->
			<div data-index="2" class="swiper-slide meta-spacer"></div>

			<div data-index="3" class="swiper-slide" data-slug="slide-3-slug" data-title="Slide 3 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 3">
				<img src="https://placem.at/things?w=400&h=800&random=3" class="swiper-image">
			</div>
			<div data-index="4" class="swiper-slide" data-slug="slide-4-slug" data-title="Slide 4 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 4">
				<img src="https://placem.at/things?w=1600&h=1200&random=4" class="swiper-image">
			</div>

			<div data-index="5" class="swiper-slide meta-spacer"></div>

			<div data-index="6" class="swiper-slide" data-slug="slide-5-slug" data-title="Slide 5 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 5">
				<img src="https://placem.at/things?w=1600&h=1200&random=5" class="swiper-image">
			</div>
			<div data-index="7" class="swiper-slide" data-slug="slide-6-slug" data-title="Slide 6 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 6">
				<img src="https://placem.at/things?w=1600&h=1200&random=6" class="swiper-image">
			</div>

			<div data-index="8" class="swiper-slide meta-spacer"></div>

			<div data-index="9" class="swiper-slide" data-slug="slide-7-slug" data-title="Slide 7 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 7">
				<img src="https://placem.at/things?w=1600&h=1200&random=7" class="swiper-image">
			</div>
			<div data-index="10" class="swiper-slide" data-slug="slide-8-slug" data-title="Slide 8 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 8">
				<img src="https://placem.at/things?w=1600&h=1200&random=8" class="swiper-image">
			</div>

			<div data-index="11" class="swiper-slide meta-spacer"></div>

			<div data-index="12" class="swiper-slide" data-slug="slide-9-slug" data-title="Slide 9 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 9">
				<img src="https://placem.at/things?w=1600&h=1200&random=9" class="swiper-image">
			</div>
			<div data-index="13" class="swiper-slide" data-slug="slide-10-slug" data-title="Slide 10 title" data-caption="Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. This is a description for 10">
				<img src="https://placem.at/things?w=1600&h=1200&random=10" class="swiper-image">
			</div>

			<div data-index="14" class="swiper-slide meta-spacer"></div>

    </div>
    <!-- .swiper-wrapper -->

		<!--
		@TODO Notes
		The initial sidebar information must be filled with the first slide's information.
		This will be updated with JS
		-->
	  <div class="swiper-sidebar">
	  	<div class="swiper-sidebar-text">
		  	<h2 id="js-swiper-sidebar-title">Slide 1 title</h2>
		  	<p id="js-swiper-sidebar-caption">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis, maiores, perferendis. Nam, ex, quia. Et excepturi veritatis, earum atque laboriosam enim provident eos vel libero fugiat cumque reiciendis, repellat alias.</p>
	  	</div>
	  	<div class="swiper-sidebar-sharing">
	  		<?php get_template_part( 'partials/social-share' ); ?>
	  	</div>
	  	<div class="swiper-sidebar-meta">
	  		<!-- @TODO Not sure if this is the right ad to use? -->
	  		<?php do_action( 'dfp_tag', 'dfp_ad_inlist_infinite' ); ?>
	  	</div>
	  	<button id="js-expand" class="swiper-sidebar-expand"><span class="icon-arrow-next"></span> <span class="screen-reader-text">Expand</span></button>
	  </div>
	  <!-- .swiper-sidebar -->

		<div class="swiper-meta-container">
			<div class="swiper-meta-inner">
				<!-- @TODO Centered ad code here, I put lorem ipsum for now -->
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet provident quam autem, vitae ut delectus et est magnam, odio, a ex quae. Dolorem labore facere distinctio facilis. Corrupti, nulla, dolorem.</p>
			</div>
		</div>
		<!-- .swiper-meta-container -->

	</div>
	<!-- .gallery-top -->

	<div class="gallery-thumbs">
		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=1)"></div></div>
		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=2)"></div></div>

		<div><div class="swiper-slide meta-spacer"></div></div>

		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=3)"></div></div>
		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=4)"></div></div>

		<div><div class="swiper-slide meta-spacer"></div></div>

		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=5)"></div></div>
		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=6)"></div></div>

		<div><div class="swiper-slide meta-spacer"></div></div>

		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=7)"></div></div>
		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=8)"></div></div>

		<div><div class="swiper-slide meta-spacer"></div></div>

		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=9)"></div></div>
		<div><div class="swiper-slide" style="background-image:url(https://placem.at/things?w=200&h=200&random=10)"></div></div>

		<div><div class="swiper-slide meta-spacer"></div></div>
  </div>
  <!-- .gallery-thumbs -->

<?php get_footer();