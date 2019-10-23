<?php

/**
 * site_leasing_esc_html
 *
 * Encodes <script> tags for safe HTML output.
 *
 * @param string $string
 *
 * @return    string
 * @since    1.0
 *
 */
function site_leasing_esc_html( $string = '' ) {
	$string = strval( $string );

	// Encode "<script" tags to invalidate DOM elements.
	if ( strpos( $string, '<script' ) !== false ) {
		$string = str_replace( '<script', htmlspecialchars( '<script' ), $string );
		$string = str_replace( '</script', htmlspecialchars( '</script' ), $string );
	}

	return $string;
}
