<?php
/**
 * Show schedule archive template file
 *
 * @package Greater Media
 * @since   0.1.0
 */

get_header(); ?>

	<main class="main" role="main">

		<div class="container">

			<section class="content">

				<h2 class="content__heading">
					Show schedule from <?php bloginfo( 'name' ); ?>
				</h2>

				<?php 

				$episodes = gmrs_get_scheduled_episodes();

				$days = array();
				$start = current( get_weekstartend( date( DATE_ISO8601 ) ) );
				$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

				?>

				<div class="shows__schedule">
					<div class="shows__schedule--days">

						<?php for ( $i = 0; $i < 7; $i++, $start += DAY_IN_SECONDS ) : ?>
							<div class="shows__schedule--day">

								<div class="shows__schedule--dayofweek">
									<?php echo date( 'l', $start ); ?>
								</div>

								<div class="shows__schedule--episodes">
									<?php $day = date( 'N', $start ); ?>
									<?php if ( ! empty( $episodes[ $day ] ) ) : ?>
										<?php for ( $j = 0, $len = count( $episodes[ $day ] ); $j < $len; $j++ ) :
											$episode = $episodes[ $day ][ $j ];
											$styles = array(
												'top:' . ( ( strtotime( $episode->post_date ) % DAY_IN_SECONDS ) * 60 / HOUR_IN_SECONDS ) . 'px',
												'height:' . ( $episode->menu_order * 60 / HOUR_IN_SECONDS ) . 'px',
												'background-color:' . gmrs_show_color( $episode->post_parent, 0.15 ),
												'border-color:' . gmrs_show_color( $episode->post_parent, 0.75 ),
											);

											?><div class="shows__schedule--episode"
												 style="<?php echo implode( ';', $styles ) ?>"
												 data-hover-color="<?php echo gmrs_show_color( $episode->post_parent, 0.4 ) ?>">

												<small>
													<?php echo date( 'M d', strtotime( $episode->post_date_gmt ) + $offset ); ?><br>
													<?php echo date( 'h:i A', strtotime( $episode->post_date_gmt ) + $offset ); ?><br>
													<?php echo date( 'h:i A', strtotime( $episode->post_date_gmt ) + $episode->menu_order + $offset ); ?><br>
												</small>

												<b><?php echo esc_html( $episode->post_title ); ?></b>
											</div>
										<?php endfor; ?>
									<?php endif; ?>
								</div>
								
							</div>
						<?php endfor; ?>

					</div>
				</div>

			</section>

		</div>

	</main>

<?php get_footer(); ?>