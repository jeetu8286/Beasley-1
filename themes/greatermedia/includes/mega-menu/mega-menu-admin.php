<?php
class GreaterMediaMegaMenuAdmin {

	public static $options = array(
		'' => 'Standard',
		'fw-mc' => 'Full-width, multi-column',
		'fw-pi' => 'Full-width with preview images',
		'fw-la' => 'Full-width with latest articles'
	);

	public static $meta_key = 'gmr_menu_format2';

	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, 'nav_menu_fields' ), null, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, 'save_nav_menu_fields' ), null, 3 );
	}

	public static function nav_menu_fields( $item_id, $item, $depth, $args ) {

		// Only apply this drop down to the root-level menus.
		if ( $depth > 0 ) {
			return;
		}

		echo $item_id;
		$format = get_post_meta( $item_id, self::$meta_key, true );
		?>
		<p class="description description-wide">
			<?php echo $format; ?>
			<label>Format:<br>
				<select name="nav-menu-format[<?php echo $item_id; ?>]" id="nav-menu-format-<?php echo intval( $item_id ); ?>">
					<?php
					foreach ( self::$options as $value => $text ) {
					?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $format ); ?>>
							<?php echo esc_html( $text ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</label>
			<?php wp_nonce_field( 'menu_format_' . $item_id, 'menu_format_nonce' ); ?>
		</p>
		<?php
	}

	public static function save_nav_menu_fields( $menu_id, $menu_item_db_id, $menu_item_data ) {

		if ( isset( $_POST['nav-menu-format'] ) ) {
			foreach ( $_POST['nav-menu-format'] as $menu_item_id => $nav_menu_format ) {

				if ( array_key_exists( $nav_menu_format, self::$options ) && ! empty( $nav_menu_format ) ) {
					update_post_meta( $menu_item_id, self::$meta_key, $nav_menu_format ); // $nav_menu_format checked against whitelist
				} else {
					delete_post_meta( $menu_item_id, self::$meta_key);
				}

			}
		}

	}

	/**
	 * Helper function to get nav menu format
	 * @param $nav_item_id integer
	 * @return bool|string
	 */
	public static function get_nav_menu_format( $nav_item_id ) {

		$format = get_post_meta( $nav_item_id, self::$meta_key, true );

		// check against whitelist
		if ( array_key_exists( $format, self::$options ) ) {
			return $format;
		} else {
			return false;
		}

	}



}
add_action( 'after_setup_theme', array( 'GreaterMediaMegaMenuAdmin', 'init' ) );