<!doctype html>
<html>
	<head <?php language_attributes(); ?>>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class( '-dark' ); ?>>
		<?php get_template_part( 'partials/header' ); ?>
		<div class="container">
			<main id="content" class="content">