<?php

// action hooks
add_action( 'widgets_init', 'gmr_ll_register_widgets' );

/**
 * Registers live link widgets.
 *
 * @action widgets_init
 */
function gmr_ll_register_widgets() {
	register_widget( 'Shows_Widget' );
}

/**
 * Shows blogroll widget class.
 */
class Shows_Widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct( 'gmr-shows-widget', 'GMR - Shows' );
	}

	/**
	 * Renders widget.
	 *
	 * @access public
	 * @param array $args The widget settings array.
	 * @param array $instance The widget instance settings array.
	 */
	public function widget( $args, $instance ) {
		$current_episode = gmrs_get_current_show_episode();
		if ( ! $current_episode ) {
			return;
		}
		
		$episodes = new WP_Query( array(
			'post_type'           => ShowsCPT::EPISODE_CPT,
			'post_status'         => array( 'publish', 'future' ),
			'orderby'             => 'date',
			'order'               => 'ASC',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => 2,
			'date_query'          => array(
				array(
					'after'     => date( DATE_ISO8601, strtotime( $current_episode->post_date_gmt ) - MINUTE_IN_SECONDS ),
					'inclusive' => true,
					'column'    => 'post_date_gmt'
				),
			),
		) );

		// do nothing if there is no posts
		if ( ! $episodes->have_posts() ) {
			return;
		}

		// render widget
		echo $args['before_widget'];
			echo '<ul>';
				while ( $episodes->have_posts() ) :
					$episode = $episodes->next_post();
					$show = get_post( $episode->post_parent );
					if ( ! $show ) {
						continue;
					}

					$term = TDS\get_related_term( $show );
					if ( ! $term ) {
						continue;
					}
					
					$show_stuff = new WP_Query( array(
						'post_type'           => apply_filters( 'gmr_show_widget_item_post_types', array() ),
						'post_status'         => 'any',
						'orderby'             => 'date',
						'order'               => 'ASC',
						'ignore_sticky_posts' => true,
						'posts_per_page'      => -1,
						'tax_query'           => array(
							'taxonomy' => ShowsCPT::SHOW_TAXONOMY,
							'field'    => 'term_id',
							'terms'    => $term->term_id,
						),
						'date_query'          => array(
							array(
								'after'     => date( DATE_ISO8601, strtotime( $episode->post_date_gmt ) - MINUTE_IN_SECONDS ),
								'inclusive' => true,
								'column'    => 'post_date_gmt'
							),
						),
					) );

					echo '<li>';
						echo '<span>', esc_html( $show->post_title ), '</span>';
						echo '<ul>';
							while ( $show_stuff->have_posts() ) :
								$show_stuff->the_post();
								echo '<li>', apply_filters( 'gmr_show_widget_item', get_the_title() ), '</li>';
							endwhile;
						echo '</ul>';
					echo '</li>';

					wp_reset_postdata();
				endwhile;
			echo '</ul>';
		echo $args['after_widget'];
	}

}