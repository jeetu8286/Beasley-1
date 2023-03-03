<?php
use Bbgi\Integration\Google;
?>
<?php

	$headerCacheTag = [];

	if (  is_front_page() ) {
		$headerCacheTag[] = $_SERVER['HTTP_HOST'].'-'.'home';
	} else if (is_archive()) {
		$obj = get_queried_object();


		if (isset($obj->slug)) {
			$headerCacheTag[] = "archive" . "-" . $obj->slug;
		}

		if (isset($wp_query->query['post_type'])) {
			$headerCacheTag[] = "archive-" . $wp_query->query['post_type'];
			$headerCacheTag[] = $wp_query->query['post_type'];
		}

	}  else {
		global $post;
		$currentPostType	= "";
		$currentPostSlug	= "";
		if ( get_post_type() ) :
			$currentPostType = get_post_type();
			$headerCacheTag[] = $currentPostType;

			if ($currentPostType == "episode") {
				$headerCacheTag[] = "podcast";
			}


		endif;
		if (  isset( $post->post_name ) && $post->post_name != "" ) :
			$currentPostSlug = "-".$post->post_name;
		endif;

		$headerCacheTag[] = $currentPostType.$currentPostSlug;
	}

	append_current_device_to_cache_tag($headerCacheTag);

	header("Cache-Tag: " . implode(",", $headerCacheTag) , true);
	header("X-Cache-BBGI-Tag: " . implode(",", $headerCacheTag) , true);
?>
<!doctype html>
<html lang="en">
	<head <?php language_attributes(); ?>>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1"><?php

		if ( is_singular() && ! empty( $GLOBALS['ee_blog_id'] ) ) :
			add_action( 'wp_head', 'restore_current_blog', 1 );
			ee_switch_to_article_blog();
		endif;

		wp_head();

	?></head>
	<body <?php body_class( get_option( 'ee_theme_version', '-dark' ) ); ?>>
		<div class="skip-links">
			<a href="#q">Skip to Search</a>
			<a href="#live-player">Skip to Live Player</a>
			<a href="#content">Skip to Content</a>
			<a href="#footer">Skip to Footer</a>
		</div><?php

		do_action( 'beasley_after_body' );

		if ( ! ee_is_common_mobile() ) :
			get_template_part( 'partials/splash-screen' );
			get_template_part( 'partials/header' );
		endif;

		?><div id='main-container-div' class="container">
			<main id="content" class="content">
				<?php
					if ( class_exists( Google::class ) ) {
						Google::render_ga_placeholder();
					}
					if ( ee_is_whiz() ) {
						echo '<div id="whiz-leaderboard-container">';
						do_action( 'dfp_tag', 'top-leaderboard' );
						echo '</div>';
					}
				?>
				<div id="inner-content">
				<?php
					$mparticle_implementation = sprintf(
							'<img src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="0" height="0" alt="" onload = "{
						window.beasleyanalytics.setAnalyticsForMParticle(\'beasley_event_id\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'primary_category\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'primary_category_id\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'show_name\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'show_id\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'tags\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'content_type\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'view_type\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'daypart\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'post_id\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'wp_author\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'primary_author\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'secondary_author\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'ad_block_enabled\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'ad_tags_enabled\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'consent_cookie\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'event_day_of_the_week\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'event_hour_of_the_day\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'prebid_enabled\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'platform\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_date\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_day_of_the_week\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_hour_of_the_day\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_month\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_time_of_day\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_timestamp_local\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_timestamp_UTC\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'publish_year\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'section_name\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'video_count\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'word_count\', %s);
						window.beasleyanalytics.setAnalyticsForMParticle(\'categories_stringified\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'tags_stringified\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'referrer\', \'%s\');
						window.beasleyanalytics.setAnalyticsForMParticle(\'UTM\', \'%s\');

						window.beasleyanalytics.sendMParticleEvent(
							window.mparticleEventNames.pageView,
						);
					}"/>',
							'beasley_event_id?',
							'primary_category?',
							'primary_category_id?',
							'show_name?',
							'show_id?',
							'tags?',
							'article', 					// content_type
							'primary',  					// view_type
							'daypart?',
							'post_id?',
							'wp_author?',
							'primary_author?',
							'secondary_author?',
							'ad_block_enabled?',
							'ad_tags_enabled?',
							'consent_cookie?',
							'event_day_of_the_week?',
							'event_hour_of_the_day?',
							'prebid_enabled?',
							'platform?',
							null, 							// publish_date
							null, 							// publish_day_of_the_week
							null, 							// publish_hour_of_the_day
							null, 							// publish_month
							null, 							// publish_time_of_day
							null, 							// publish_timestamp_local
							null, 							// publish_timestamp_UTC
							null, 							// publish_year
							'section_name?',
							null, 							// video_count
							null, 							// word_count
							'categories_stringified?',
							'tags_stringified?',
							'referrer?',
							'UTM?'
					);
					echo $mparticle_implementation;
				?>
