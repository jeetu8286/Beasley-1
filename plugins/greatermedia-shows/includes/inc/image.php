<?

$prefix = 'sample_';

$fields = array(
	array( // Image ID field
		'label'	=> 'Image', // <label>
		'desc'	=> 'A description for the field.', // description
		'id'	=> $prefix.'image', // field id and name
		'type'	=> 'image' // type of field
	)
);

/**
 * Instantiate the class with all variables to create a meta box
 * var $id string meta box id
 * var $title string title
 * var $fields array fields
 * var $page string|array post type to add meta box to
 * var $js bool including javascript or not
 */
$sample_box = new custom_add_meta_box( 'sample_box', 'Sample Box', $fields, 'post', true );
