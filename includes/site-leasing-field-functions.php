<?php

/**
 * site_leasing_render_field
 *
 * Render the input element for a given field.
 *
 * @param array $field The field array.
 *
 * @return    void
 * @since    1.0
 *
 */
function site_leasing_render_field( $field ) {

	// Ensure field is complete (adds all settings).
	$field = site_leasing_validate_field( $field );

	// Prepare field for input (modifies settings).
	$field = site_leasing_prepare_field( $field );

	// Allow filters to cancel render.
	if ( ! $field ) {
		return;
	}

}

/**
 * site_leasing_validate_field
 *
 * Ensures the given field is valid.
 *
 * @since	1.0
 *
 * @param	array $field The field array.
 * @return	array
 */
function site_leasing_validate_field( $field = array() ) {

	// Bail early if already valid.
	if( is_array($field) && !empty($field['_valid']) ) {
		return $field;
	}

	// Apply defaults.
	$field = wp_parse_args($field, array(
		'ID'				=> 0,
		'key'				=> '',
		'label'				=> '',
		'name'				=> '',
		'prefix'			=> '',
		'type'				=> 'text',
		'value'				=> null,
		'menu_order'		=> 0,
		'instructions'		=> '',
		'required'			=> false,
		'id'				=> '',
		'class'				=> '',
		'conditional_logic'	=> false,
		'parent'			=> 0,
		'wrapper'			=> array(),
	));

	// Convert types.
	$field['ID'] = (int) $field['ID'];
	$field['menu_order'] = (int) $field['menu_order'];

	// Add backwards compatibility for wrapper attributes.
	// Todo: Remove need for this.
	$field['wrapper'] = wp_parse_args($field['wrapper'], array(
		'width'				=> '',
		'class'				=> '',
		'id'				=> ''
	));

	// Store backups.
	$field['_name'] = $field['name'];
	$field['_valid'] = 1;

	/**
	 * Filters the $field array to validate settings.
	 *
	 * @date	12/02/2014
	 * @since	5.0.0
	 *
	 * @param	array $field The field array.
	 */
	$field = apply_filters( "validate_field", $field );

	// return
	return $field;
}

/**
 * site_leasing_prepare_field
 *
 * Prepare a field for input.
 *
 * @date	20/1/19
 * @since	5.7.10
 *
 * @param	array $field The field array.
 * @return	array
 */
function site_leasing_prepare_field( $field ) {

	// Bail early if already prepared.
	if( !empty($field['_prepare']) ) {
		return $field;
	}

	// Use field key to override input name.
	if( $field['key'] ) {
		$field['name'] = $field['key'];
	}

	// Use field prefix to modify input name.
	if( $field['prefix'] ) {
		$field['name'] = "{$field['prefix']}[{$field['name']}]";
	}

	// Generate id attribute from name.
	$field['id'] = acf_idify( $field['name'] );

	// Add state to field.
	$field['_prepare'] = true;

	/**
	 * Filters the $field array.
	 *
	 * Allows developers to modify field settings or return false to remove field.
	 *
	 * @date	12/02/2014
	 * @since	5.0.0
	 *
	 * @param	array $field The field array.
	 */
	$field = apply_filters( "prepare_field", $field );

	// return
	return $field;
}
