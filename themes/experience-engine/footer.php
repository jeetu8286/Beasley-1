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
				const count = carousel.classList.contains( '-large' ) ? 2.2 : 4.2;
				new Swiper( carousel, {
					slidesPerView: count,
					spaceBetween: 36,
					freeMode: true,
					breakpoints: {
						900: {
							slidesPerView: 2.2,
						},
						480: {
							slidesPerView: 1.2,
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
