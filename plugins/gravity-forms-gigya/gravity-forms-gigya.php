<?php
/*
Plugin Name: Greater Media, Gravity Forms, Gigya Prototype
Author: 10up
License: gpl
*/

/**
 * Get the Gravity Forms representation for a given entry ID.
 * e.g. greatermediatestsite.dev?entry=50
 *
 * Will echo out the lead data, and also the form data associated with the lead.
 * Then, because its mission is over, it will die.
 */
function debug_gravity_forms() {
	if ( isset( $_GET['entry'] ) ) {
		$lead_id = $_GET['entry'];
		$lead = RGFormsModel::get_lead( $lead_id ); 
		$form = GFFormsModel::get_form_meta( $lead['form_id'] ); 

		$values= array();

		var_dump( $lead_id );
		echo '=======';
		var_dump( $lead );
		echo '=======';
		var_dump( $form );

		die();
	}

}
add_action( 'wp_head', 'debug_gravity_forms' );

/**
 * When a Gravity form is submitted it fires this hook callback. The hook contains the entries and the form.
 * The $form array contains all the $field data, which is then used to extract data from $entry
 *
 * For initial prototyping, this function outputs formatted data and dies. This is a proof of concept.
 *
 * The array that it var_dumps() could easily be converted into JSON with json_encode(). With a bit more work it
 * could be formatted as plain key: value text. Both of which are needed.
 *
 * 
 * 
 * @param  array $entry Gravity Forms entry object {@link http://www.gravityhelp.com/documentation/page/Developer_Docs#Entry_Object}
 * @param  array $form  Gravity Forms form object {@link http://www.gravityhelp.com/documentation/page/Developer_Docs#Form_Object}
 */
function tdd_after_submission( $entry, $form ) {

	// Build up this array from gigya data
	$gigya_array = array();
	$gigya_profile = array();

	// Go through each field and try to get data associated with each
	foreach( $form['fields'] as $field ) {

		$value = _tdd_normalize_entry_value( $field['id'], $entry, $field );

		// Don't store empty values!
		if ( empty( $value ) ) {
			continue;
		}

		$gigya_array[$field['label']] = $value;
		
		if ( isset( $field['gigyaDemographic'] ) && ! empty( $field['gigyaDemographic'] ) ) {
			$gigya_profile[$field['gigyaDemographic']] = $value;
		}
		

	} // endforeach
	
	// wrap all the entries with the form title
	$gigya_array = array( $form['title'] => $gigya_array );
	?>
	<?php get_header(); ?>
	<div class="entry-content">
		<h2><a href="#" onclick="jQuery('.gigya-profile').toggle(); return false;">Normalized user profile data</a></h2>
		<pre class="gigya-profile" style=""><code>
			<?php print_r( $gigya_profile ); ?>
		</code></pre>

		<h2><a href="#" onclick="jQuery('.gigya-array').toggle(); return false;">Submitted to Gigya</a></h2>
		<pre class="gigya-array" style=""><code>
			<?php print_r( $gigya_array ); ?>
		</code></pre>

		<h2><a href="#" onclick="jQuery('.gravity-forms-entry').toggle(); return false;">Gravity Forms Entry Object</a></h2>
		<pre class="gravity-forms-entry" style="display: none;"><code>
			<?php print_r( $entry ); ?>
		</code></pre>

		<h2><a href="#" onclick="jQuery('.gravity-forms-form').toggle(); return false;">Gravity Forms Form Object</a></h2>
		<pre class="gravity-forms-form" style="display: none;"><code>
			<?php print_r( $form ); ?>
		</code></pre>

	</div>
	<?php get_footer(); ?>
	<?php
	die();

}
add_action("gform_after_submission", "tdd_after_submission", 10, 2);

/**
 * Get the value from $entry for a given $id.
 * Needs $field for context.
 * 
 * @param  integer|string $id Gravity forms field id. sometimes an integer, sometimes a string.
 * @param  array $entry Gravity Forms entry object
 * @param  array $field A subset representing a single field in the Gravity Forms Form object
 * @return string       normalized entry value
 */
function _tdd_normalize_entry_value( $id, $entry, $field ) {

		// If this field has "inputs" that means we need to go get them all. It's a select, check, or radio list.
	if ( isset( $field['inputs'] ) ) {
		$inputs = array();
		foreach ( $field['inputs'] as $input ) {

			$key = $input['label'];
			$value = $entry[ (string) $input['id']];

			$value = maybe_unserialize( $value );

				// Don't store keys if the value is empty
			if ( empty( $value ) ) {
				continue;
			}

				// Don't store labels as keys if the value is the same
			if ( $key === $value ) {
				$inputs[] = $value;
			} else {
				$inputs[$key] = $value;
			}

		}

		return $inputs;
	} else {
		return maybe_unserialize($entry[$field['id']]);
	}
}

/**
 * Sets up the HTML for selecting the Gigya demographic fields in Gravity Forms
 * @param  integer $position where in the form it should be shown
 * @param  integer $form_id a form ID, in case you want to restrict to certain forms
 */
function tdd_gigya_profile_settings($position, $form_id){

	$gigya_fields = tdd_get_gigya_fields();

    //create settings on position 50 (right after Admin Label)
	if($position == 0){
		?>
		<li class="gigya_setting field_setting">
			<label for="field_admin_label">
				<?php _e("Submit to Gigya Demographic Field", "gravityforms"); ?>
				<?php gform_tooltip("gigya_demographic_fields") ?>
			</label>
			<select id="gigya_demographic" onChange="SetFieldProperty('gigyaDemographic', jQuery(this).val());">
				<option value="">Don't add this field's value to user's profile</option>
				<?php foreach ( $gigya_fields as $gigya_field_slug => $gigya_field_name ): ?>
				<option value="<?php echo $gigya_field_slug; ?>"><?php echo $gigya_field_name; ?></option>
			<?php endforeach; ?>
		</select>
	</li>
	<?php
}
}
add_action("gform_field_advanced_settings", "tdd_gigya_profile_settings", 10, 2);

/**
 * Returns an array of Gigya profile fields
 * @return array gigya fields
 */
function tdd_get_gigya_fields() {

	$gigya_fields = array(
		'favorite-band' => 'Favorite Band',
		'zip-code' => 'Zipcode',
		't-shirt-size' => 'T-Shirt Size',
		);

	return $gigya_fields;
}

/**
 * Outputs scripts for gravity forms. Gravity Forms uses javascript to save fields and show the previously saved value
 * Note that the actual field (output in gigya_profile_settings() ) has no name="" attribute.
 */
function editor_script(){
	?>
	<script type='text/javascript'>

        //binding to the load field settings event to initialize the checkbox
        jQuery(document).bind("gform_load_field_settings", function(event, field, form){
        	jQuery('.gigya_setting').show();
        	if ( field["gigyaDemographic"] ) {
        		jQuery('.gigya_setting').find('[value='+field["gigyaDemographic"]+']').attr('selected','selected');
        	}

        });
        </script>
        <?php
    }
    add_action("gform_editor_js", "editor_script");

/**
 * Add a help tooltip for the gigya profile fields
 * @param array $tooltips array of tooltips.
 */
function add_encryption_tooltips($tooltips){
	$tooltips["form_field_encrypt_value"] = "<h6>Encryption</h6>Check this box to encrypt this field's data";
	return $tooltips;
}
add_filter('gform_tooltips', 'add_encryption_tooltips');
