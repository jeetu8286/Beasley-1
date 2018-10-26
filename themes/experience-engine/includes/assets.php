<?php

add_action( 'wp_head', 'ee_load_fonts', 0 );
add_action( 'wp_enqueue_scripts', 'ee_enqueue_front_scripts' );

add_filter( 'wp_audio_shortcode_library', '__return_false' );

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

if ( ! function_exists( 'ee_enqueue_front_scripts' ) ) :
	function ee_enqueue_front_scripts() {
		$base = untrailingslashit( get_template_directory_uri() );
	
		wp_enqueue_style( 'ee-app', "{$base}/bundle/app.css", null, null );
	
		wp_register_script( 'td-sdk', '//sdk.listenlive.co/web/2.9/td-sdk.min.js', null, null, true );
		wp_register_script( 'ee-app-vendors', "{$base}/bundle/vendors-app.js", null, null, true );
		wp_enqueue_script( 'ee-app', "{$base}/bundle/app.js", array( 'td-sdk', 'ee-app-vendors' ), null, true );
	
		wp_localize_script( 'ee-app', 'bbgiconfig', array(
			'firebase'    => apply_filters( 'firebase_settings', array() ),
			'livePlayer' => array(
				'streams' => function_exists( 'gmr_streams_get_public_streams' ) ? gmr_streams_get_public_streams() : array(),
			),
		) );
	}
endif;

if ( ! function_exists( 'ee_load_fonts' ) ) :
	function ee_load_fonts() {
		$config = array(
			'google' => array(
				'families' => array(
					'Libre+Franklin:300,400,500,600,700',
				),
			),
		);

		printf( '<script type="text/javascript">var WebFontConfig = %s;</script>', wp_json_encode( $config ) );
		echo '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/webfont/1/webfont.js" async></script>';
		echo '<noscript><link href="//fonts.googleapis.com/css?family=Libre+Franklin:300,400,500,600,700" rel="stylesheet"></noscript>';
	}	
endif;
