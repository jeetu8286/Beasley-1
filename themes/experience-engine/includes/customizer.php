<?php

add_action( 'customize_register', 'ee_register_customizer' );

if ( ! function_exists( 'ee_register_customizer' ) ) :
	function ee_register_customizer( $wp_customize ) {

		$wp_customize->add_section( 'beasley_theme_options',
			array(
				'title'       => 'Theme Options',
				'priority'    => 1,
				'capability'  => 'edit_theme_options',
				'description' => 'Select the theme version',
			)
		);

		$wp_customize->add_setting( 'ee_theme_version',
			array(
				'default'    => 'light',
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
				'transport'  => 'postMessage',
			)
		);

		$wp_customize->add_control( 'ee_theme_version',
			array(
				'type'    => 'select',
				'label'   => 'Theme Version',
				'section' => 'beasley_theme_options',
				'choices' => array(
					'-light' => 'Light',
					'-dark'  => 'Dark',
				),
			)
		);
	}

endif;