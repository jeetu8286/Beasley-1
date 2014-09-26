<?php
/**
 * Template file for displaying single contest
 *
 * @package Greater Media Prototype
 * @since   0.1.0
 */

get_header();

while ( have_posts() ):
	the_post();
	?>
	<article <?php post_class(); ?>>
		<h1><?php the_title(); ?></h1>
		<?php

		$form = get_post_meta( get_the_ID(), 'form', true );
		$start_date = get_post_meta( get_the_ID(), 'start-date', true );
		$end_date = get_post_meta( get_the_ID(), 'end-date', true );
		$rules = get_post_meta( get_the_ID(), 'rules-desc', true );
		$how_to_enter = get_post_meta( get_the_ID(), 'how-to-enter-desc', true );
		$prizes = get_post_meta( get_the_ID(), 'prizes-desc', true );

		if( !empty( $prizes ) ){
			echo '<p><h2>Prizes: </h2>'. apply_filters('the_content', $prizes ) .'</p>';
		}
		if( !empty( $how_to_enter ) ){
			echo '<p><h2>How to Enter: </h2>'. apply_filters('the_content', $how_to_enter ) .'</p>';
		}
		if( !empty( $rules ) ){
			echo '<p><h2>Rules: </h2>'. apply_filters('the_content', $rules ) .'</p>';
		}
		if( !empty( $start_date ) && !empty( $end_date ) ){
			echo '<p><h2>Dates: </h2>'. date("F d", esc_attr( $start_date ) ) . ' &#8211; ' . date("F d, Y", esc_attr( $end_date ) );
		}
		if( !empty( $form ) ){
			if ( function_exists( 'gravity_form' ) ){
				gravity_form( absint( $form ), false, false );
			}
		}

		?>
	</article>
<?php
endwhile;
get_footer();
?>