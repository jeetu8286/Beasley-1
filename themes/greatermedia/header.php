<?php
/**
 * The template for displaying the header.
 *
 * @package Greater Media
 * @since 0.1.0
 */
 ?><!DOCTYPE html>
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
		<div id="site-wrap" class="site-wrap">
			<?php do_action( 'show_breaking_news_banner' ); ?>
			<nav id="mobile-nav" class="mobile-nav">
				<?php
				$mobile_nav = array(
					'theme_location'  => 'main-nav',
					'menu'            => '',
					'container'       => '',
					'container_class' => '',
					'container_id'    => '',
					'menu_class'      => 'mobile-nav__list js-mobile-sub-menus',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'depth'           => 0,
					'walker'          => new GreaterMediaMobileNavWalker()
				);

				wp_nav_menu( $mobile_nav );

				do_action( 'gmr_social' ); ?>
			</nav>
			<div id="header__search--form" class="header__search--form">
				<?php get_template_part( 'searchform', 'header' ); ?>
				<div id="keyword-search-container"></div>
				<script type='text/template' id="keyword-search-body-template">
					<div class='keyword-search'>
						<div class='keyword-search__header'></div>

						<div class='keyword-search__items'></div>

						<div class='keyword-search__footer'>
							<button class='keyword-search__btn' href='#'>Search All Content</button>
						</div>
					</div>
				</script>
				<script type="text/template" id="keyword-search-item-template">
					<div class='keyword-search-item'>
						<div class='keyword-search-item__keyword'>Keyword: <strong><%= keyword %></strong></div>
						<div class='keyword-search-item__article'><a href='http://example.com/'><%= title %></a></div>
					</div>
				</script>
			</div>
			<div id="page-wrap" class="page-wrap">
				<header id="header" class="header" role="banner">
					<div class="container">
						<div class="ad__leaderboard desktop">
							<?php do_action( 'acm_tag', 'leaderboard-top-of-site' ); ?>
						</div>
						<div class="ad__leaderboard mobile">
							<?php do_action( 'acm_tag', 'smartphone-wide-banner' ); ?>
						</div>
					</div>
					<div class="header__main">
						<div class="container">
							<div class="mobile-nav__toggle">
								<div class="mobile-nav__toggle--span"></div>
							</div>

								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__logo">
									<?php do_action( 'gmr_site_logo' ); ?>
								</a>

							<?php
							$main_nav = array(
								'theme_location'  => 'main-nav',
								'menu'            => '',
								'container'       => 'nav',
								'container_class' => 'header__nav',
								'container_id'    => '',
								'menu_class'      => 'header__nav--list',
								'menu_id'         => 'header__nav--list',
								'echo'            => true,
								'fallback_cb'     => 'wp_page_menu',
								'before'          => '',
								'after'           => '',
								'link_before'     => '',
								'link_after'      => '',
								'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'           => 0,
								'walker'          => new GreaterMediaNavWalker
							);
							wp_nav_menu( $main_nav );
							?>
							<div class="header__secondary">
								<div class="header__account header__account--mobile">
									<a href="#" class="header__account--btn">
										<span class="icon-user"></span>
									</a>

									<div class="header__account--container">

									</div>
								</div>
								<div class="header__account header__account--small">
									<a href="#" class="header__account--btn">
										<span class="icon-user"></span>
									</a>

									<div class="header__account--container">

									</div>
								</div>
								<div class="header__account header__account--large">
									<a href="#" class="header__account--btn">
										<span class="icon-user"></span>
										<span class="header__account--span">Login or Register</span>
									</a>
									<div class="header__account--container">

									</div>
								</div>
								<div id="header__search" class="header__search">
									<label for="s" class="header__search--label"><i class="header__search--btn"></i><div class="header__search--span"><?php _e( 'Keyword Search', 'greatermedia' ); ?></div></label>
								</div>
							</div>
						</div>
					</div>
				</header>
