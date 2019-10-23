<?php

/**
 * site_leasing_get_path
 *
 * Returns the plugin path to a specified file.
 *
 * @param string $filename The specified file.
 *
 * @return    string
 * @since    1.0
 *
 */
function site_leasing_get_path( $filename = '' ) {
	return SITELEASING_PLUGIN_DIR_PATH . ltrim( $filename, '/' );
}
