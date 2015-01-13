<?php
/**
 * Posts Screen Thumbnails
 * 
 * Adds a column to the posts screen to show each post's thumbnail. Works with
 * custom post types as well. 
 */

namespace Greater_Media\Posts_Screen_Thumbnails;

class Thumbnail_Column {

	private $_post_type; 
	
	private $_thumbnail_size;

	private $_column_name; 
	
	/**
	 * Register the thumbnail column for a post type. 
	 * 
	 * @param string $post_type
	 * @param array|string $thumbnail_size
	 */
	public function __construct( $post_type = 'post', $thumbnail_size = 'greater_media/thumbnail_column' )
	{
		$this->_post_type = $post_type;
		$this->_thumbnail_size = $thumbnail_size; 
		$this->_column_name = 'greater_media_thumbnail';  
		
		add_filter( 'manage_' . $this->_post_type . '_posts_columns', array( $this, 'filter_columns' ) );
		add_action( 'manage_' . $this->_post_type . '_posts_custom_column', array( $this, 'do_custom_column' ), 10, 2 );
		add_action( 'admin_head', array( $this, 'admin_head' ) ); 
	}

	/**
	 * Add the custom column to the columns list. 
	 * 
	 * @param array $columns
	 * @return array 
	 */
	public function filter_columns( $columns )
	{
		// Put the thumbnail right after the checkbox.
		$columns = array_merge(
			array_slice( $columns, 0, 1 ),
			array( $this->_column_name => 'Thumbnail' ),
			array_slice( $columns, 1 )
		);

		return $columns;
	}

	/**
	 * Render the custom column. 
	 * 
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function do_custom_column( $column_name, $post_id )
	{
		if ( $this->_column_name == $column_name ) {
			if (current_user_can('edit_post', $post_id)) {
				echo "<a href='", get_edit_post_link( $post_id ), "'>", get_the_post_thumbnail( $post_id, $this->_thumbnail_size ), "</a>";
			} else {
				echo get_the_post_thumbnail( $post_id, $this->_thumbnail_size );
			}
		}
	}
	
	/**
	 * Add some CSS to the page head that sets the column width to match the 
	 * thumbnail width. 
	 */
	public function admin_head() 
	{
		global $_wp_additional_image_sizes; 
		
		if ( is_array( $this->_thumbnail_size ) ) {
			$width = $this->_thumbnail_size[0];
			$height = $this->_thumbnail_size[1];
		} elseif ( isset( $_wp_additional_image_sizes[ $this->_thumbnail_size ] ) ) {
			$width = $_wp_additional_image_sizes[ $this->_thumbnail_size ]['width']; 
			$height = $_wp_additional_image_sizes[ $this->_thumbnail_size ]['height']; 
		} else {
			return; 
		}
		
		?>
		<style type='text/css'>
			.column-<?php echo sanitize_html_class( $this->_column_name ); ?> { width: <?php echo (int) $width; ?>px; }
			.column-<?php echo sanitize_html_class( $this->_column_name ); ?> img { width: <?php echo (int) $width; ?>px; height: <?php echo (int) $height; ?>px; }
		</style>
		<?php 
	}
}
