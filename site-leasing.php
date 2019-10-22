<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://resourceatlanta.com
 * @since             1.0.0
 * @package           Site_Leasing
 *
 * @wordpress-plugin
 * Plugin Name:       Resource Site Leasing
 * Plugin URI:        https://resourceatlanta.com
 * Description:       Integrate RENTCafe API provider with interactive site plans
 * Version:           1.0.0
 * Author:            Resource Branding & Design
 * Author URI:        https://resourceatlanta.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       site-leasing
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/** @var string The plugin version number. */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/** @var string The plugin directory, with trailing slash added. */
define( 'SITELEASING_PLUGIN_DIR', dirname( __FILE__ ) . '/' );

/** @var string The plugin filesystem directory path, includes trailing slash. */
define( 'SITELEASING_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

/** @var string The plugin text domain. */
define( 'SITELEASING_TEXT_DOMAIN', 'site-leasing' );

/** @var string The minimal version of PHP for the plugin. */
define( 'SITELEASING_MINPHP_VERSION', '7.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-site-leasing-activator.php
 */
function activate_site_leasing() {
	require_once SITELEASING_PLUGIN_DIR_PATH . 'includes/class-site-leasing-activator.php';
	Site_Leasing_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_site_leasing' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-site-leasing-deactivator.php
 */
function deactivate_site_leasing() {
	require_once SITELEASING_PLUGIN_DIR_PATH . 'includes/class-site-leasing-deactivator.php';
	Site_Leasing_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_site_leasing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require SITELEASING_PLUGIN_DIR_PATH . 'includes/class-site-leasing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_site_leasing() {

	$siteLeasingPlugin = new Site_Leasing();
	$siteLeasingPlugin->run();

}

run_site_leasing(); // start plugin
