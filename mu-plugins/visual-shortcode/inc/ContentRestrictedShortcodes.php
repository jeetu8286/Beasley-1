<?php

class ContentRestrictedShortcodes {

	function register() {
		add_action(
			'admin_enqueue_scripts', array( $this, 'add_base_plugin' )
		);

		add_filter(
			'mce_external_plugins', array( $this, 'add_plugins' )
		);

		add_filter(
			'mce_buttons', array( $this, 'add_buttons' )
		);

		add_filter(
			'mce_css', array( $this, 'add_css' )
		);

		add_action(
			'admin_head', array( $this, 'add_button_css' )
		);
	}

	function add_button_css() {
		$html = <<<HTML
		<style type="text/css">
i.mce-ico.mce-i-ageRestricted:before {
	content: "\f301";
	display: inline-block;
	-webkit-font-smoothing: antialiased;
	font: normal 20px/1 'dashicons';
	vertical-align: top;
}
		</style>
HTML;

		echo $html;
	}

	function add_base_plugin() {
		if ( $this->can_add_base_plugin() ) {
			wp_enqueue_script(
				'jquery-date-format',
				$this->get_plugin_url( 'date.format/date.format' ),
				array( 'jquery' ),
				'0.1.0',
				true
			);

			wp_enqueue_script(
				'jquery-datetimepicker',
				$this->get_plugin_url( 'datetimepicker/jquery.datetimepicker' ),
				array( 'jquery' ),
				'0.1.0',
				true
			);

			wp_enqueue_script(
				'visual-shortcode',
				$this->get_plugin_url( 'visual-shortcode' ),
				array( 'jquery' ),
				'0.1.0',
				false
			);
		}
	}

	function can_add_base_plugin() {
		global $post;
		return is_admin() && ! empty( $post ) &&
			(
				post_type_supports( $post->post_type, 'age-restricted-content' ) ||
				post_type_supports( $post->post_type, 'login-restricted-content' ) ||
				post_type_supports( $post->post_type, 'time-restricted-content' )
			);
	}

	function add_plugins( $plugins ) {
		$plugins['ageRestricted']   = $this->get_plugin_url( 'age-restricted' );
		$plugins['loginRestricted'] = $this->get_plugin_url( 'login-restricted' );
		$plugins['timeRestricted']  = $this->get_plugin_url( 'time-restricted' );

		return $plugins;
	}

	function add_buttons( $buttons ) {
		$buttons[] = 'ageRestricted';
		$buttons[] = 'loginRestricted';
		$buttons[] = 'timeRestricted';

		return $buttons;
	}

	function add_css( $styles ) {
		$styles   = explode( ',', $styles );
		$styles[] = $this->get_css_url( 'visual-shortcode' );
		$styles[] = $this->get_css_url( 'datetimepicker/jquery.datetimepicker', 'js' );
		$styles   = implode( ',', $styles );

		return $styles;
	}

	/* helpers */
	function get_plugin_url( $name, $dir = 'js' ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$extension = '.js';
		} else {
			$extension = '.min.js';
		}

		return plugins_url( "{$dir}/{$name}{$extension}", VISUAL_SHORTCODE_PLUGIN );
	}

	function get_css_url( $name, $dir = 'css' ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$extension = '.css';
		} else {
			$extension = '.min.css';
		}

		return plugins_url( "{$dir}/{$name}{$extension}", VISUAL_SHORTCODE_PLUGIN );
	}

}
