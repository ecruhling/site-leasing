<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Site_Leasing_Admin' ) ) :

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
		 * @var      string $site_leasing The ID of this plugin.
		 */
		private $site_leasing;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string $version The current version of this plugin.
		 */
		private $version;

		/**
		 * Menu slug
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string
		 */
		public static $menu_slug = 'site-leasing-settings';

		/**
		 * Option group
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string
		 */
		public static $optionGroup = 'site_leasing_wordpress_option_group';

		/**
		 * Section ID for API Credentials
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string
		 */
		public static $apiCredentialsSettingsSectionID = 'site_leasing_api_credentials';

		/**
		 * Section ID for RENTCafe Data
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string
		 */
		public static $rentCafeDataSettingsSectionID = 'site_leasing_rentcafe_data';

		/**
		 * @var object
		 */
		private $wp_menu_args;

		/**
		 * @var array
		 */
		private $fields_keys;

		/**
		 * @var object
		 */
		private $fields;

		/**
		 * @var site_leasing_Options
		 */
		private $options;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @param string $site_leasing The name of this plugin.
		 * @param string $version The version of this plugin.
		 *
		 * @since    1.0.0
		 */
		public function __construct( $site_leasing, $version ) {
			$this->options = new Site_Leasing_Options();

			$this->site_leasing = $site_leasing;
			$this->version      = $version;

			$this->wp_menu_args = (object) [
				'parent_slug' => 'options-site-leasing.php',
				'page_title'  => 'Site Leasing',
				'menu_title'  => 'Site Leasing',
				'capability'  => 'manage_options',
				'menu_slug'   => 'site-leasing-settings',
			];

			$this->fields_keys = [
				'rentcafe_api_token',
				'rentcafe_property_code',
				'template_override',
				'templates_accent_color',
				'override_single_property_template_file',
				'override_single_floorplan_template_file',
				'override_unit_visibility',
				'override_single_floorplan_template_title',
				'hide_floorplan_availability_counter',
				'hide_floorplans_without_availability',
				'override_archive_floorplans_template_file',
				'archive_floorplans_default_sort',
				'single_floorplan_content_position',
				'archive_floorplan_content_position',
				'override_how_floorplan_pricing_is_display',
				'override_apply_links_targets',
				'override_request_link',
				'single_floorplan_request_more_info_url',
				'show_waitlist_ctas',
				'show_waitlist_override_url',
			];

			$this->fields = [];

			if ( is_array( $this->fields_keys ) ) {
				foreach ( $this->fields_keys as $field_key ) {
					$this->fields[ $field_key ] = (object) [
						'name'  => $this->options->prefix( $field_key ),
						'value' => $this->options->getOption( $field_key ),
					];
				}
			}

			$this->fields = (object) $this->fields;

			add_action( 'admin_menu', [ $this, 'generate_main_options_page' ], 20 );
			add_action( 'admin_init', [ $this, 'wp_setting_sections_and_fields' ], 20 );

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

		/**
		 * Create plugin options page in the WP admin menu
		 * Attached to admin_menu hook
		 *
		 * @since    1.0.0
		 */
		public function generate_main_options_page() {
			add_menu_page(
				$this->wp_menu_args->page_title,
				$this->wp_menu_args->menu_title,
				$this->wp_menu_args->capability,
				$this->wp_menu_args->menu_slug,
				[ $this, 'render_settings_page' ],
				'dashicons-admin-multisite',
				5
			);
		}

		/**
		 * Create settings page and fields
		 *
		 * @since    1.0.0
		 */
		public function render_settings_page() {
			?>
            <div id="site-leasing-settings-page-wrapper">
                <h1>Resource Site Leasing: General Settings</h1>
				<?php
				settings_errors();
				?>

                <form method="post" action="options.php" enctype="multipart/form-data">
					<?php
					settings_fields( self::$optionGroup );
					do_settings_sections( $this->wp_menu_args->menu_slug );
					submit_button( 'Save API Credentials' );
					?>
                </form>
            </div>
			<?php
		}

		/**
		 * Create admin sections and fields
		 * Attached to admin_init hook
		 *
		 * @since    1.0.0
		 */
		public function wp_setting_sections_and_fields() {

			/**
			 *  RENTCafe API Credentials Section
			 */
			add_settings_section(
				self::$apiCredentialsSettingsSectionID,
				'RENTCafe API Credentials',
				function () {
					echo '<p>Your API Credentials from RENTCafe.</p>';
				},
				$this->wp_menu_args->menu_slug
			);

			add_settings_field(
				$this->fields->rentcafe_api_token->name,
				'RENTCafe API Token',
				[ $this, 'rentcafe_api_token' ],
				$this->wp_menu_args->menu_slug,
				self::$apiCredentialsSettingsSectionID
			);

			add_settings_field(
				$this->fields->rentcafe_property_code->name,
				'RENTCafe Property Code',
				[ $this, 'rentcafe_property_code' ],
				$this->wp_menu_args->menu_slug,
				self::$apiCredentialsSettingsSectionID
			);

			$args = array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => null,
			);

			register_setting( self::$optionGroup, $this->fields->rentcafe_api_token->name, $args );
			register_setting( self::$optionGroup, $this->fields->rentcafe_property_code->name, $args );

			/**
			 *  RENTCafe Data Section
			 */
			add_settings_section(
				self::$rentCafeDataSettingsSectionID,
				'RENTCafe Data',
				function () {
					echo '<p>Data from RENTCafe below:</p>';
				},
				$this->wp_menu_args->menu_slug
			);

		}

		/**
		 * Settings Section: API Credentials
		 * @return string [HTML of settings input]
		 */
		public function rentcafe_api_token() { ?>
            <label for="rentcafe-api-token" style="display:none;">RENTCafe API Token</label>
            <input id="rentcafe-api-token" type="text"
                   name="<?php echo $this->fields->rentcafe_api_token->name; ?>"
                   value="<?php echo $this->fields->rentcafe_api_token->value; ?>"
                   placeholder="RENTCafe API Token"
                   class="regular-text">
            <p class="description" id="tagline-description">Format is: XXXXXXXX-XXXXXXXXXXXXXX</p>
			<?php
			return;
		}

		public function rentcafe_property_code() { ?>
            <label for="rentcafe-property-code" style="display:none;">RENTCafe Property Code</label>
            <input id="rentcafe-property-code" type="text"
                   name="<?php echo $this->fields->rentcafe_property_code->name; ?>"
                   value="<?php echo $this->fields->rentcafe_property_code->value; ?>"
                   placeholder="RENTCafe Property Code"
                   class="regular-text">
            <p class="description" id="tagline-description">Format is: pXXXXXXX</p>
			<?php
			return;
		}

	}

endif; // class_exists check
