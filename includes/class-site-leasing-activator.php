<?php

/**
 * Fired during plugin activation
 *
 * @link       https://resourceatlanta.com
 * @since      1.0.0
 *
 * @package    Site_Leasing
 * @subpackage Site_Leasing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Site_Leasing
 * @subpackage Site_Leasing/includes
 * @author     Erik Ruhling <erik@resourceatlanta.com>
 */
class Site_Leasing_Activator {

	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		/**
		 * Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
		 *
		 * @since    1.0.0
		 * @url      https://codex.wordpress.org/Function_Reference/deactivate_plugins
		 */
		if ( version_compare( phpversion(), SITELEASING_MINPHP_VERSION ) < 0 ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'This plugin requires PHP Version ' . SITELEASING_MINPHP_VERSION . '.' );
		}

	}

}




