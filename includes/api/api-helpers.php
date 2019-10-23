<?php

/**
 *  site_leasing_nonce_input
 *
 *  This function will create a basic nonce input
 *
 * @since    1.0
 *
 */
function site_leasing_nonce_input( $nonce = '' ) {

	echo '<input type="hidden" name="_site_leasing_nonce" value="' . wp_create_nonce( $nonce ) . '" />';

	return;
}

/**
 *  site_leasing_get_view
 *
 *  This function will load in a file from the 'admin/views' folder and allow variables to be passed through
 *
 * @param    $path (string)
 * @param    $args (array)
 *
 * @return   null
 *
 * @since    1.0
 */

function site_leasing_get_view( $path = '' ) {

	// allow view file name shortcut
	if ( substr( $path, - 4 ) !== '.php' ) {

		$path = site_leasing_get_path( "includes/admin/views/{$path}.php" );

	}

	// include
	if ( file_exists( $path ) ) {

//		extract( $args );
		include( $path );

	}

	return;
}
