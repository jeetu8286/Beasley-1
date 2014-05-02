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
	<?php wp_head(); ?>
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
</head>
<body <?php body_class(); ?>>
<main>
	<header class="page-header">
		<div class="account">
			<div class="player">
				<a href="#"><i class="fa fa-play-circle"> Listen Live</i></a>
			</div>
			<a href="" class="register">register</a>
			<a href="" class="login">Login</a>
		</div>
		<p class="site-ids">
			Network: <?php echo get_current_site()->site_name; ?> (ID: <?php echo get_current_site()->id; ?>)<br>
			Blog ID: <?php echo get_current_blog_id(); ?>
		</p>

		<h1>
			<a href="<?php echo home_url(); ?>"><?php echo get_bloginfo( 'name' ); ?></a>
		</h1>
	</header>