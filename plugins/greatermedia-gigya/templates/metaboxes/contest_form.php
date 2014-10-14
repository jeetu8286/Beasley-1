<?php

// TODO: write custom query sorted on last changed timestamp and move out of template,
$forms           = \RGFormsModel::get_forms( true );
$post_id         = $this->member_query->ID; // TODO: fix after MetaBox cleanup
$contest_form_id = get_post_meta( $post_id, 'contest_form_id', true );

?>

<div class="contest-form-heading">
	<h4>Select Existing Form:</h4>
</div>

<select id="contest-form-select" style="width: 100%" name="contest_form_id">
	<?php foreach ( $forms as $form ) {

		$label = esc_attr( $form->title );
		$value = esc_html( $form->id );
		$selected = $contest_form_id === $form->id ? 'selected="selected"' : '';

		echo "<option value='$value' $selected>$label</option>";

	} ?>
</select>

<div class="contest-form-divider">
	<p>or</p>
</div>

<div class="contest-form-new">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=gf_new_form' ) )?>" target="_blank" class="button button-secondary">Create New Form</a>
</div>



