<?php
/**
 * Created by Eduard
 * Date: 20.11.2014 0:10
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class GreaterMedia_Keyword_Admin {

	private $plugin_slug = 'gmedia_keywords' ;
	private $supported_post_types = array( 'post' );
	private $postfix;

	public function __construct() {
		$this->postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';

		// Load admin style sheet and js.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		//add_action( 'admin_init', array( $this, 'settings_sections' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );

		add_action( 'wp_ajax_delete_keyword', array( $this, 'delete_keyword' ) );

		add_filter( 'found_posts', array( $this, 'alter_search_results' ) );
	}

	public function enqueue_admin_styles(){
		wp_enqueue_style( 'select2');
		wp_enqueue_style(
			$this->plugin_slug . '-admin-style'
			, GMKEYWORDS_URL . "assets/css/greatermedia_keywords{$this->postfix}.css"
			, array()
			, GMKEYWORDS_VERSION
			, 'all'
		);
	}

	public function enqueue_admin_scripts(){

		wp_enqueue_script( 'select2');
		wp_enqueue_script( 'jquery-effects-core');
		wp_enqueue_script( 'jquery-effects-slide');

		wp_enqueue_script(
			$this->plugin_slug . '-admin-script'
			, GMKEYWORDS_URL . "assets/js/greatermedia_keywords{$this->postfix}.js"
			, array( 'jquery' )
			, GMKEYWORDS_VERSION
		);

		wp_localize_script(
			$this->plugin_slug . '-admin-script'
			, 'ajax_data'
			, array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'delete_key_nonce' => wp_create_nonce( 'perform-keyword-delete-nonce' )
			)
		);
	}


	/**
	 * Register the administration menu
	 *
	 */
	public function add_plugin_admin_menu() {

		/**
		 * Add a settings page for this plugin to the Tools menu.
		 */
		add_submenu_page(
			'tools.php',
			'On-Air Keywords',
			'Manage Keywords',
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add or update a WordPress option.
	 * The option will _not_ auto-load.
	 * Source: https://eamann.com/tech/wordpress-options-auto-loading/
	 *
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return bool
	 */
	public function add_or_update( $name, $value ) {
		$success = add_option( $name, $value, '', 'no' );

		if ( ! $success ) {
			$success = update_option( $name, $value );
		}

		return $success;
	}

	/**
	 * Store key and linkec_content in WP_Cache object
	 *
	 * @param $name
	 * @param $data
	 *
	 * @return bool
	 */
	public function add_or_update_cache( $name, $data ) {

		$success = wp_cache_add( $name, $data, "keywords" );

		if( ! $success ) {
			$success = wp_cache_set( $name, $data, "keywords" );
		}

		return $success;
	}

	public function save_settings() {
		$pairs = get_option( $this->plugin_slug . '_option_name' );
		$pairs = $this->array_map_r( 'sanitize_text_field', $pairs );
		if ( isset( $_POST["save_keyword_settings"] ) && current_user_can('manage_options') ) {

			$linked_content = isset( $_POST['linked_content'] ) ? sanitize_text_field( $_POST['linked_content'] ) : '';
			$keyword = isset( $_POST['keyword'] )? sanitize_text_field( $_POST['keyword'] ) : '';

			$linked_content = explode( ',', $linked_content );

			if( $keyword == '' ) {
				echo '<div id="message" class="error"><p>Keyword can\'t be empty!</p></div>';
				return false;
			}

			if( is_array( $pairs ) && array_key_exists( $keyword, $pairs ) ) {
				echo '<div id="message" class="error"><p>Keyword ' . esc_html( $keyword ) . ' already used!</p></div>';
				return false;
			}

			$pairs[$keyword] = array(
				'post_id'       =>  intval( $linked_content[0] ),
				'post_title'    =>  sanitize_text_field( $linked_content[1] )
			);

			if( $this->add_or_update( $this->plugin_slug . '_option_name', $pairs ) ) {
				echo '<div id="message" class="updated"><p>Keywords saved</p></div>';
				$this->add_or_update_cache( $this->plugin_slug . '_option_name', $pairs );
			}
		}

		return true;
	}


	public function delete_keyword() {

		$success = false;
		// get nonce from ajax post
		$nonce = $_POST['delete_key_nonce'];

		// verify nonce, with predefined
		if ( ! wp_verify_nonce( $nonce, 'perform-keyword-delete-nonce' ) ) {
			die ( ':P' );
		}

		if( isset( $_POST['post_id'] ) ) {
			$key_post_id = intval( $_POST['post_id'] );
			$pairs = get_option( $this->plugin_slug . '_option_name' );
			$pairs = $this->array_map_r( 'sanitize_text_field', $pairs );
			foreach ( $pairs as $key => $linked_content ) {
				if( $linked_content['post_id'] == $key_post_id ) {
					unset( $pairs[$key] );
					$success = $this->add_or_update( $this->plugin_slug . '_option_name', $pairs );
					$this->add_or_update_cache( $this->plugin_slug . '_option_name', $pairs );
					break;
				}
			}
		}

		die( $success );
	}

	public function alter_search_results() {

		$search = sanitize_text_field( get_query_var('s') );

		if( is_search() && $search ) {
			global $wp_query;
			$options = wp_cache_get( $this->plugin_slug . '_option_name', "keywords" );

			if( !$options ) {
				$options = get_option( $this->plugin_slug . '_option_name' );
				$options = $this->array_map_r( 'sanitize_text_field', $options );
				$this->add_or_update_cache( $this->plugin_slug . '_option_name', $options );
			}

			if( array_key_exists( $search, $options) ) {
				if( is_array( $wp_query->posts ) ) {
					array_unshift( $wp_query->posts, $options[$search]['post_id'] );
				} else {
					$wp_query->posts = array( $options[$search]['post_id'] );
				}
			}
		}
	}


	/**
	 * Helper function to sanitize multidimensional array
	 * http://php.net/manual/en/function.array-map.php#78904
	 *
	 * @param $func
	 * @param $arr
	 *
	 * @return array
	 */
	public function array_map_r( $func, $arr ) {
		$newArr = array();
		if( is_array( $arr) ) {
			foreach( $arr as $key => $value )
			{
				$newArr[ $key ] = ( is_array( $value ) ? $this->array_map_r( $func, $value ) : ( is_array($func) ? call_user_func_array($func, $value) : $func( $value ) ) );
			}

			return $newArr;
		} else {
			$arr = is_array($func) ? call_user_func_array($func, $arr) : $func( $arr );
			return $arr;
		}
	}
}


new GreaterMedia_Keyword_Admin();