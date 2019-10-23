<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Site_Leasing' ) ) :

	/**
	 * The file that defines the core plugin class
	 *
	 * A class definition that includes attributes and functions used across both the
	 * public-facing side of the site and the admin area.
	 *
	 * @link       https://resourceatlanta.com
	 * @since      1.0.0
	 *
	 * @package    Site_Leasing
	 * @subpackage Site_Leasing/includes
	 */

	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    Site_Leasing
	 * @subpackage Site_Leasing/includes
	 * @author     Erik Ruhling <erik@resourceatlanta.com>
	 */
	class Site_Leasing {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Site_Leasing_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $site_leasing The string used to uniquely identify this plugin.
		 */
		protected $site_leasing;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
				$this->version = PLUGIN_NAME_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->site_leasing = 'site-leasing';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Site_Leasing_Loader. Orchestrates the hooks of the plugin.
		 * - Site_Leasing_i18n. Defines internationalization functionality.
		 * - Site_Leasing_Admin. Defines all hooks for the admin area.
		 * - Site_Leasing_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-site-leasing-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-site-leasing-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-site-leasing-admin.php';

			/**
			 * The class responsible for admin options.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-site-leasing-admin-options.php';

			/**
			 * The class responsible for notifications.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-site-leasing-notifications.php';

			/**
			 * The base class logger.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-site-leasing-logger.php';

			/**
			 * The class responsible for creating log files (extends logger).
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-site-leasing-logging.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-site-leasing-public.php';

			/**
			 * Helper functions
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/api-helpers.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/site-leasing-field-functions.php';

			/**
			 * WP Pluggable functions (wp_create_nonce)
			 */
			require_once( ABSPATH . 'wp-includes/pluggable.php' );

			$this->loader = new Site_Leasing_Loader();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Site_Leasing_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$siteLeasingPlugin_i18n = new Site_Leasing_i18n();

			$this->loader->add_action( 'plugins_loaded', $siteLeasingPlugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$siteLeasingPlugin_admin = new Site_Leasing_Admin( $this->get_site_leasing(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $siteLeasingPlugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $siteLeasingPlugin_admin, 'enqueue_scripts' );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			$siteLeasingPlugin_public = new Site_Leasing_Public( $this->get_site_leasing(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $siteLeasingPlugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $siteLeasingPlugin_public, 'enqueue_scripts' );

		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @return    string    The name of the plugin.
		 * @since     1.0.0
		 */
		public function get_site_leasing() {
			return $this->site_leasing;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @return    Site_Leasing_Loader    Orchestrates the hooks of the plugin.
		 * @since     1.0.0
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @return    string    The version number of the plugin.
		 * @since     1.0.0
		 */
		public function get_version() {
			return $this->version;
		}

	}

endif; // class_exists check
