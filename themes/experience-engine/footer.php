					</div>
				<?php get_template_part( 'partials/ads/footer' ); ?>
			</main><?php

			if ( ! ee_is_jacapps() ) :
				get_template_part( 'partials/footer' );
				get_template_part( 'partials/modals' );
				get_template_part( 'partials/live-player' );
			endif;
		?></div>

		<?php wp_footer(); ?>
		<script>
			//@TODO :: We need to see if we can properly load this in the React App.
			const carousels = document.querySelectorAll( '.swiper-container' );

			carousels.forEach( carousel => {
				const count = carousel.classList.contains( '-large' ) ? 2.3 : 4.3;
				new Swiper( carousel, {
					slidesPerView: count,
					spaceBetween: 36,
					freeMode: true,
					breakpoints: {
						900: {
							slidesPerView: 2.3,
						},
						480: {
							slidesPerView: 1.3,
							spaceBetween: 27,
						}
					},
					navigation: {
						nextEl: '.swiper-button-next',
						prevEl: '.swiper-button-prev',
					},
				} );
			} );
			</script>
	</body>
</html>
