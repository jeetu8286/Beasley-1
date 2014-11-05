<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( "Please don't try to access this file directly." );
}

class GreaterMediaFormbuilderRender {

	const FIELD_SIZE_SMALL = '10';
	const FIELD_SIZE_MEDIUM = '25';
	const FIELD_SIZE_LARGE = '40';

	private function __construct() {
		// Use the public static methods. Don't instantiate this class directly.
	}

	public static function register_actions() {
		add_action( 'wp', array( __CLASS__, 'process_form_submission' ) );
	}

	/**
	 * @uses do_action
	 */
	public static function process_form_submission() {

		if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
			return;
		}

		if ( ! isset( $_POST['contest_id'] ) ) {
			return;
		}

		if ( absint( $_POST['contest_id'] ) !== absint( $GLOBALS['post']->ID ) ) {
			return;
		}

		// @TODO get entrant name from Gigya
		$entrant_name = 'John Doe';
		// @TODO get entrant reference (ID) from Gigya
		$entrant_reference = '12345';

		// Pretty sure this is our form submission at this point
		$form = json_decode( get_post_meta( $GLOBALS['post']->ID, 'embedded_form', true ) );
		if ( empty( $form ) ) {
			return;
		}

		$submitted_values = array();
		foreach ( $form as $field ) {

			$post_array_key = 'form_field_' . $field->cid;
			
			if ( isset( $_POST[ $post_array_key ] ) ) {
				$submitted_values[ $field->cid ] = sanitize_text_field( $_POST[ $post_array_key ] );
			}

		}

		$entry = GreaterMediaContestEntryEmbeddedForm::create_for_data(
			$GLOBALS['post']->ID,
			$entrant_name,
			$entrant_reference,
			GreaterMediaContestEntry::ENTRY_SOURCE_EMBEDDED_FORM,
			json_encode( $submitted_values )
		);

		$entry->save();

		do_action( 'greatermedia_contest_entry_save', $entry );

	}

	/**
	 * Retrieve a custom list of HTML tags & attributes we're allowing in a rendered form
	 * @return array valid tags
	 */
	protected static function allowed_tags() {

		static $tags;
		if ( ! isset( $tags ) ) {

			$tags = array();

			// Add form tags
			$tags['input'] = array(
				'id'          => 1,
				'class'       => 1,
				'type'        => 1,
				'name'        => 1,
				'value'       => 1,
				'disabled'    => 1,
				'size'        => 1,
				'minlength'   => 1,
				'maxlength'   => 1,
				'pattern'     => 1,
				'placeholder' => 1,
				'step'        => 1,
				'readonly'    => 1,
				'min'         => 1,
				'max'         => 1,
				'form'        => 1,
			);

			$tags['select'] = array(
				'id'        => 1,
				'name'      => 1,
				'class'     => 1,
				'autofocus' => 1,
				'disabled'  => 1,
				'form'      => 1,
				'multiple'  => 1,
				'required'  => 1,
				'size'      => 1,
			);

			$tags['label'] = array(
				'for'   => 1,
				'form'  => 1,
				'class' => 1,
			);

			$tags['p'] = array(
				'class' => 1,
			);

		}

		return $tags;
	}

	/**
	 * @param $post_id
	 * @param $form
	 *
	 * @uses render_text
	 */
	public static function render( $post_id, $form ) {

		if ( ! is_numeric( $post_id ) ) {
			throw new InvalidArgumentException( '$post_id must be an integer post ID' );
		}

		if ( is_string( $form ) ) {
			$form = json_decode( $form );
		}

		if ( null === $form ) {
			throw new InvalidArgumentException( '$form parameter is invalid' );
		}

		if ( ! is_array( $form ) ) {
			throw new InvalidArgumentException( '$form should be a JSON string or an Object' );
		}

		echo '<form action="" method="post" enctype="multipart/form-data">';
		echo '<input type="hidden" name="contest_id" value="' . absint( $post_id ) . '" />';

		foreach ( $form as $field ) {

			$renderer_method = 'render_' . $field->field_type;

			// Make sure the field type has been implemented/is valid
			if ( ! method_exists( __CLASS__, $renderer_method ) ) {
				throw new InvalidArgumentException( sprintf( 'Form field %s has an unimplemented field type', $field->cid ) );
			}

			echo wp_kses( self::$renderer_method( $post_id, $field ), self::allowed_tags() );

		}

		echo '</form>';

	}

	/**
	 * Render a text field on a form using data from formbuilder
	 *
	 * @param stdClass $field
	 *
	 * @return string HTML
	 */
	private static function render_text( $post_id, stdClass $field ) {

		$html     = '';
		$field_id = 'form_field_' . $field->cid;

		$label_tag_attributes = array(
			'for' => $field_id,
		);

		$label = ( isset( $field->label ) ) ? $field->label : '';

		$input_tag_attributes = array(
			'id'   => $field_id,
			'name' => $field_id,
			'type' => 'text',
		);

		$description = ( isset( $field->field_options->description ) ) ? $field->field_options->description : '';

		$description_tag_attributes = array();

		// Give the theme a chance to alter the attributes for the input field
		$label_tag_attributes = apply_filters( 'gm_form_text_label_attrs', $label_tag_attributes );
		$label_tag_attributes = apply_filters( 'gm_form_label_attrs', $label_tag_attributes );
		$label                = apply_filters( 'gm_form_text_label_text', $label );
		$label                = apply_filters( 'gm_form_label_text', $label );

		if ( ! empty( $label ) ) {

			$html .= '<label ';

			foreach ( $label_tag_attributes as $attribute => $value ) {
				$html .= wp_kses_data( $attribute ) . '="' . esc_attr( $value ) . '" ';
			}
			
			$html .= '>' . wp_kses_data( $label ) . '</label>';

		}

		if ( isset( $field->required ) && $field->required ) {
			$input_tag_attributes['required'] = 'required';
		}

		if ( isset( $field->field_options->size ) ) {

			if ( 'small' === $field->field_options->size ) {
				$input_tag_attributes['size'] = self::FIELD_SIZE_SMALL;
			} else if ( 'medium' === $field->field_options->size ) {
				$input_tag_attributes['size'] = self::FIELD_SIZE_MEDIUM;
			} else if ( 'large' === $field->field_options->size ) {
				$input_tag_attributes['size'] = self::FIELD_SIZE_LARGE;
			} else {
				throw new InvalidArgumentException( sprintf( 'Field %d has an invalid size', $field->cid ) );
			}

		}

		if ( isset( $field->field_options->minlength ) ) {
			$input_tag_attributes['minlength'] = $field->field_options->minlength;
		}

		if ( isset( $field->field_options->maxlength ) ) {
			$input_tag_attributes['maxlength'] = $field->field_options->maxlength;
		}

		// Give the theme a chance to alter the attributes for the input field
		$input_tag_attributes = apply_filters( 'gm_form_text_input_attrs', $input_tag_attributes );
		$input_tag_attributes = apply_filters( 'gm_form_input_attrs', $input_tag_attributes );

		$html .= '<input ';
		foreach ( $input_tag_attributes as $attribute => $value ) {
			$html .= wp_kses_data( $attribute ) . '="' . esc_attr( $value ) . '" ';
		}
		$html .= ' />';

		// Give the theme a chance to alter the attributes for the description
		$description_tag_attributes = apply_filters( 'gm_form_text_description_attrs', $description_tag_attributes );
		$description_tag_attributes = apply_filters( 'gm_form_input_description_attrs', $description_tag_attributes );
		$description                = apply_filters( 'gm_form_text_description_text', $description );
		$description                = apply_filters( 'gm_form_description_text', $description );

		if ( ! empty( $description ) ) {

			$html .= '<p ';

			foreach ( $description_tag_attributes as $attribute => $value ) {
				$html .= wp_kses_data( $attribute ) . '="' . esc_attr( $value ) . '" ';
			}

			$html .= ' >' . wp_kses_data( $description ) . '</p>';
		}

		$html .= self::get_submit_button( 'Enter', null, null, true );

		return $html;

	}

	/**
	 * get_submit_button() from WordPress 4.0
	 * Returns a submit button, with provided text and appropriate class
	 *
	 * @since 3.1.0
	 *
	 * @param string       $text             The text of the button (defaults to 'Save Changes')
	 * @param string       $type             The type of button. One of: primary, secondary, delete
	 * @param string       $name             The HTML name of the submit button. Defaults to "submit". If no id attribute
	 *                                       is given in $other_attributes below, $name will be used as the button's id.
	 * @param bool         $wrap             True if the output button should be wrapped in a paragraph tag,
	 *                                       false otherwise. Defaults to true
	 * @param array|string $other_attributes Other attributes that should be output with the button,
	 *                                       mapping attributes to their values, such as array( 'tabindex' => '1' ).
	 *                                       These attributes will be output as attribute="value", such as tabindex="1".
	 *                                       Defaults to no other attributes. Other attributes can also be provided as a
	 *                                       string such as 'tabindex="1"', though the array format is typically cleaner.
	 *
	 * @return string HTML
	 */
	protected static function get_submit_button( $text = null, $type = 'primary large', $name = 'submit', $wrap = true, $other_attributes = null ) {
		if ( ! is_array( $type ) ) {
			$type = explode( ' ', $type );
		}

		$button_shorthand = array( 'primary', 'small', 'large' );
		$classes          = array( 'button' );
		foreach ( $type as $t ) {
			if ( 'secondary' === $t || 'button-secondary' === $t ) {
				continue;
			}
			$classes[] = in_array( $t, $button_shorthand ) ? 'button-' . $t : $t;
		}
		$class = implode( ' ', array_unique( $classes ) );

		if ( 'delete' === $type ) {
			$class = 'button-secondary delete';
		}

		$text = $text ? $text : __( 'Save Changes' );

		// Default the id attribute to $name unless an id was specifically provided in $other_attributes
		$id = $name;
		if ( is_array( $other_attributes ) && isset( $other_attributes['id'] ) ) {
			$id = $other_attributes['id'];
			unset( $other_attributes['id'] );
		}

		$attributes = '';
		if ( is_array( $other_attributes ) ) {
			foreach ( $other_attributes as $attribute => $value ) {
				$attributes .= $attribute . '="' . esc_attr( $value ) . '" '; // Trailing space is important
			}
		} else if ( ! empty( $other_attributes ) ) { // Attributes provided as a string
			$attributes = $other_attributes;
		}

		$button = '<input type="submit" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="' . esc_attr( $class );
		$button .= '" value="' . esc_attr( $text ) . '" ' . $attributes . ' />';

		if ( $wrap ) {
			$button = '<p class="submit">' . $button . '</p>';
		}

		return $button;
	}

}

GreaterMediaFormbuilderRender::register_actions();
