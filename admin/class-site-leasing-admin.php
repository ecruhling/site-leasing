<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://resourceatlanta.com
 * @since      1.0.0
 *
 * @package    Site_Leasing
 * @subpackage Site_Leasing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Site_Leasing
 * @subpackage Site_Leasing/admin
 * @author     Erik Ruhling <erik@resourceatlanta.com>
 */
class Site_Leasing_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $site_leasing    The ID of this plugin.
	 */
	private $site_leasing;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $site_leasing       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $site_leasing, $version ) {

		$this->site_leasing = $site_leasing;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Site_Leasing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Site_Leasing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->site_leasing, plugin_dir_url( __FILE__ ) . 'css/site-leasing-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Site_Leasing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Site_Leasing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->site_leasing, plugin_dir_url( __FILE__ ) . 'js/site-leasing-admin.js', array( 'jquery' ), $this->version, false );

	}

}
