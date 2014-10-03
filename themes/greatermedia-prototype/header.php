<?php
/**
 * The template for displaying the header.
 *
 * @package Greater Media Prototype
 * @since   0.1.0
 */
?><!DOCTYPE html>
<head>
	<title>Greater Media Prototype</title>
	<?php // mobile meta ?>
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<?php wp_head(); ?>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
</head>
<body <?php body_class(); ?>>
	<?php do_action( 'show_breaking_news_banner' ); ?>
<main id="container">
	<header class="page-header">
		<div class="account">
			<div class="player">
				<a href="#" class="pjaxer"><i class="fa fa-play-circle"> Listen Live</i></a>
			</div>
			<a href="" class="register pjaxer">register</a>
			<a href="" class="login pjaxer">Login</a>
		</div>
		<p class="site-ids">
			Network: <?php echo get_current_site()->site_name; ?> (ID: <?php echo get_current_site()->id; ?>)<br>
			Blog ID: <?php echo get_current_blog_id(); ?>
		</p>

		<h1>
			<a href="<?php echo home_url(); ?>" class="pjaxer"><?php echo get_bloginfo( 'name' ); ?></a>
		</h1>
	</header>
