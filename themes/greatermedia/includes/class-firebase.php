<?php

class Firebase {

	public static function init() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new Firebase();

			add_action( 'admin_init', array( $instance, 'register_settings' ), 9 );
		}
	}

	public function register_settings() {
		$text_callback = array( $this, 'render_text_setting' );
		
		$fields = array(
			'apiKey'            => 'API Key',
			'authDomain'        => 'Auth Domain',
			'databaseURL'       => 'Database URL',
			'storageBucket'     => 'Storage Bucket',
			'messagingSenderId' => 'Messaging Sender ID',
		);

		add_settings_section( 'beasley_firebase', 'Firebase', '__return_false', 'media' );

		foreach ( $fields as $key => $label ) {
			$full_key = "beasley_firebase_{$key}";
			add_settings_field( $full_key, $label, $text_callback, 'media', 'beasley_firebase', $full_key );
			register_setting( 'media', $full_key, 'sanitize_text_field' );
		}
	}

	public function render_text_setting( $name ) {
		?><input type="text" class="regular-text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( get_option( $name ) ); ?>"><?php
	}

}

Firebase::init();