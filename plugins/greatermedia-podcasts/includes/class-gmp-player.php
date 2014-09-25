<?php
/**
 * Class GMP_Player
 *
 * This class constructs a podcast player to use on the front end of a website
 */
class GMP_Player{

	/**
	 * Hook into the appropriate actions when the class is initiated.
	 */
	public static function init() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'display_podcasts', array( __CLASS__, 'render_podcasts' ) );

	}

	public static function render_podcasts() {

		global $post;
		$post_id = $post->ID;

		$slug = get_post( $post )->post_name;

		$args = array(
			'post_type'                 => 'episode',
			'_podcast'                  => $slug,
			'cache_results'             => true,
			'update_post_meta_cache'    => true,
			'update_post_term_cache'    => true
		);

		$podcasts = new WP_Query( $args );

		if ( $podcasts->have_posts() ) : while ( $podcasts->have_posts() ) : $podcasts->the_post();

			$episode_attr = array(
				'link' => get_the_permalink(),
				'title' => get_the_title()
			);

			echo '<a href="' . $episode_attr['link'] . '">' . $episode_attr['title'] . '</a>';

		endwhile;

		else : ?>

			<article id="post-not-found" class="hentry cf">
				<header class="article-header">
					<h1><?php _e( 'Oops, Post Not Found!', 'gmpodcasts' ); ?></h1>
				</header>
				<section class="entry-content">
					<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'gmpodcasts' ); ?></p>
				</section>
			</article>

		<?php endif;

	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {

		$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		wp_register_script( 'gmpodcasts-js', GMPODCASTS_URL . "/assets/js/greater_media_podcasts{$postfix}.js", array( 'jquery' ), GMPODCASTS_VERSION, true );
		wp_register_script( 'mediaelement-js', GMPODCASTS_URL . "/assets/js/vendor/mediaelement-and-player{$postfix}.js", array( 'jquery' ), '2.15.1', true );

		wp_enqueue_script( 'gmpodcasts-js' );
		wp_enqueue_script( 'mediaelement-js' );

		wp_enqueue_style( 'gmpodcasts-css', GMPODCASTS_URL . "/assets/css/greater_media_podcasts{$postfix}.css", array(), GMPODCASTS_VERSION );

	}

}