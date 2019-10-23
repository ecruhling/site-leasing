<?php

/**
 * site_leasing_render_field
 *
 * Render the input element for a given field.
 *
 * @date    21/1/19
 *
 * @param array $field The field array.
 *
 * @return    void
 * @since    5.7.10
 *
 */
function site_leasing_render_field( $field ) {

	// Ensure field is complete (adds all settings).
	$field = acf_validate_field( $field );

	// Prepare field for input (modifies settings).
	$field = acf_prepare_field( $field );

	// Allow filters to cancel render.
	if ( ! $field ) {
		return;
	}

}
