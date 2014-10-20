<?php
/**
 * The template for displaying the header.
 *
 * @package Greater Media
 * @since 0.1.0
 */
 ?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

	<!--[if lt IE 7]>
	<html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
	<!--[if (IE 7)&!(IEMobile)]>
	<html class="no-js lt-ie9 lt-ie8"><![endif]-->
	<!--[if (IE 8)&!(IEMobile)]>
	<html class="no-js lt-ie9"><![endif]-->
	<!--[if gt IE 8]><!-->
	<html class="no-js"><!--<![endif]-->

		<head>
			<meta charset="utf-8">

			<?php // Google Chrome Frame for IE ?>
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

			<title><?php wp_title( '' ); ?></title>

			<?php // mobile meta ?>
			<meta name="HandheldFriendly" content="True">
			<meta name="MobileOptimized" content="320">
			<meta name="viewport" content="width=device-width,height=device-height,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">

			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

			<?php // wordpress head functions ?>
			<?php wp_head(); ?>
			<?php // end of wordpress head ?>

			<?php // drop Google Analytics Here ?>
			<?php // end analytics ?>

		</head>

		<body <?php body_class(); ?>>
			<?php

				$defaults = array(
					'theme_location'  => 'main-nav',
					'menu'            => '',
					'container'       => 'nav',
					'container_class' => 'mobile-nav',
					'container_id'    => 'mobile-nav',
					'menu_class'      => 'mobile-nav--list',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'           => 0,
					'walker'          => ''
				);

				wp_nav_menu( $defaults );

			?>
			<div class="site-wrap">
				<header id="header" class="header" role="banner">
					<div class="container">
						<div class="mobile-nav--toggle">
							<div class="mobile-nav--toggle--span"></div>
						</div>
						<div class="header-logo">
							<a href="<?php echo home_url(); ?>"><img src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/wmmr-logo.png" alt="<?php bloginfo( 'name' ); ?> | <?php bloginfo( 'description' ); ?>" class="header-logo--img"></a>
						</div>
						<?php

							$defaults = array(
								'theme_location'  => 'main-nav',
								'menu'            => '',
								'container'       => 'nav',
								'container_class' => 'header-nav--main',
								'container_id'    => '',
								'menu_class'      => 'header-nav--list',
								'menu_id'         => 'header-nav--list',
								'echo'            => true,
								'fallback_cb'     => 'wp_page_menu',
								'before'          => '',
								'after'           => '',
								'link_before'     => '',
								'link_after'      => '',
								'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'           => 0,
								'walker'          => ''
							);

							wp_nav_menu( $defaults );

						?>
						<div class="header-secondary">
							<?php

							$defaults = array(
								'theme_location'  => 'secondary-nav',
								'menu'            => '',
								'container'       => 'nav',
								'container_class' => 'header-secondary--nav',
								'container_id'    => '',
								'menu_class'      => 'header-nav--list',
								'menu_id'         => 'header-nav--list',
								'echo'            => true,
								'fallback_cb'     => 'wp_page_menu',
								'before'          => '',
								'after'           => '',
								'link_before'     => '',
								'link_after'      => '',
								'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'           => 0,
								'walker'          => ''
							);

							wp_nav_menu( $defaults );

							?>
							<div class="header-secondary--search">
								<i class="fa fa-search"></i>
							</div>
						</div>
					</div>
				</header>