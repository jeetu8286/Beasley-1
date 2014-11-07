<?php

class GMR_Live_Link_Widget extends WP_Widget {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		parent::__construct( 'gmr-ll-widget', 'Live Links' );
	}

	/**
	 * Renders widget.
	 *
	 * @access public
	 * @param array $args The widget settings array.
	 * @param array $instance The widget instance settings array.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? 'Live Links' : $instance['title'], $instance, $this->id_base );

		$query = new WP_Query( apply_filters( 'gmr_live_link_widget_query_args', array(
			'post_type'           => GMR_LIVE_LINK_CPT,
			'post_status'         => 'publish',
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => $instance['posts_count'],
		) ) );

		// do nothing if there is no posts
		if ( ! $query->have_posts() ) {
			return;
		}

		// render widget
		echo $args['before_widget'];
			if ( ! empty( $title ) ) :
				echo $args['before_title'] . $title . $args['after_title'];
			endif;

			echo '<ul>';
				while ( $query->have_posts() ) :
					$query->the_post();

					$link = gmr_ll_get_redirect_link( get_the_ID() );
					if ( $link ) :
						echo '<li>';
							echo '<a href="', esc_url( $link ), '">', get_the_title(), '</a>';
						echo '</li>';
					endif;
				endwhile;
			echo '</ul>';
		echo $args['after_widget'];

		wp_reset_postdata();
	}

	/**
	 * Updates widget instance settings.
	 *
	 * @access public
	 * @param array $new_instance The new settings array.
	 * @param array $old_instance The old settings array.
	 * @return array The updated settings array.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['posts_count'] = filter_var( $new_instance['posts_count'], FILTER_VALIDATE_INT, array( 'options' => array( 'min_range' => 0, 'default' => 20 ) ) );

		return $instance;
	}

	/**
	 * Renders widget form.
	 *
	 * @access public
	 * @param array $instance The widget instance.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title'       => '',
			'posts_count' => 20,
		) );

		$title = strip_tags( $instance['title'] );
		$title_field_id = $this->get_field_id( 'title' );

		$count = intval( $instance['posts_count'] );
		$count_field_id = $this->get_field_id( 'posts_count' );
		
		?><p>
			<label for="<?php echo $title_field_id; ?>">Title:</label>
			<input class="widefat" id="<?php echo $title_field_id; ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<p>
			<label for="<?php echo $count_field_id; ?>">Posts count:</label>
			<input class="widefat" id="<?php echo $count_field_id; ?>" name="<?php echo $this->get_field_name( 'posts_count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>">
		</p><?php
	}

}