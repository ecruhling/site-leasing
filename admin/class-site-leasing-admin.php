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

	public static $isSiteAboutASinglePropertySettingsSectionID = 'rentPress_is_site_about_a_single_property';

	public static $templateOverrideSectionID = 'rentPress_override_setting';

	public static $singleFloorplanTemplateOverrideSectionID = 'sfp_rentPress_override_setting';

	public static $floorplanGridTemplateOverrideSectionID = 'fg_rentPress_override_setting';

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
			'api_token',
			'api_username',
			'is_site_about_a_single_property',
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

			global $submenu, $wpdb;

			if ( isset( $submenu[ Site_Leasing_Admin::$menu_slug ] ) ) {
				$tabs = array_map(
					function ( $item ) {
						return (object) [
							'slug' => $item[2],
							'text' => $item[0],
						];
					},
					$submenu[ Site_Leasing_Admin::$menu_slug ]
				);
			} else {
				$tabs = [];
			}

			?>

            <h2 class="site-leasing-admin-nav">
				<?php foreach ( $tabs as $tab ) : ?>
                    <a href="?page=<?php echo $tab->slug; ?>"
                       class="nav-tab <?php echo $_GET['page'] == $tab->slug ? 'site-leasing-nav-tab-active' : ''; ?>">
						<?php echo $tab->text; ?>
                    </a>
				<?php endforeach; ?>
            </h2>

            <div style="clear: both;"></div>

            <form method="post" action="options.php" enctype="multipart/form-data">
				<?php
				settings_fields( self::$optionGroup );
				do_settings_sections( $this->wp_menu_args->menu_slug );
				submit_button();
				?>
            </form>
        </div>
		<?php
	}

	/**
	 * Create admin sections and fields
	 * Attached to admin_init
	 *
	 * @since    1.0.0
	 */
	public function wp_setting_sections_and_fields() {
		add_settings_section(
			self::$apiCredentialsSettingsSectionID,
			'API Credentials',
			function () {
				echo '<p>Your API Credentials from RENTCafe.</p>';
			},
			$this->wp_menu_args->menu_slug
		);
		add_settings_field(
			$this->fields->api_token->name,
			'API Token',
			[ $this, 'rentcafe_api_token' ],
			$this->wp_menu_args->menu_slug,
			self::$apiCredentialsSettingsSectionID
		);

		add_settings_field(
			$this->fields->api_username->name,
			'Username',
			[ $this, 'rentcafe_api_username' ],
			$this->wp_menu_args->menu_slug,
			self::$apiCredentialsSettingsSectionID
		);

		register_setting( self::$optionGroup, $this->fields->api_token->name );
		register_setting( self::$optionGroup, $this->fields->api_username->name );

		add_settings_section(
			self::$isSiteAboutASinglePropertySettingsSectionID,
			'Single Property Site',
			function () {
				echo '<p>Check this if your website promoting a single property. This will enable built-in templates and can assist with overall site load speeds.</p>';
			},
			$this->wp_menu_args->menu_slug
		);

		add_settings_field(
			$this->fields->is_site_about_a_single_property->name,
			'Single Property Website',
			[ $this, 'rentPress_site_is_about_single_property' ],
			$this->wp_menu_args->menu_slug,
			self::$isSiteAboutASinglePropertySettingsSectionID
		);

		register_setting( self::$optionGroup, $this->fields->is_site_about_a_single_property->name );

		/* Override single-floorplans.php and single-properties.php with plugin default templates */

		add_settings_section(
			self::$templateOverrideSectionID,
			'RentPress Templates',
			[ $this, 'template_override_section_description' ],
			$this->wp_menu_args->menu_slug
		);

		add_settings_field(
			$this->fields->override_single_floorplan_template_file->name,
			'Use Single Floor Plan Template',
			[ $this, 'rentPress_override_single_floorplan_file' ],
			$this->wp_menu_args->menu_slug,
			self::$templateOverrideSectionID
		);

		add_settings_field(
			$this->fields->override_archive_floorplans_template_file->name,
			'Use Floor Plans Grid Template',
			[ $this, 'rentPress_override_archive_floorplans_file' ],
			$this->wp_menu_args->menu_slug,
			self::$templateOverrideSectionID
		);

		add_settings_field(
			$this->fields->templates_accent_color->name,
			'Accent Color',
			[ $this, 'rentPress_templates_accent_color' ],
			$this->wp_menu_args->menu_slug,
			self::$templateOverrideSectionID
		);

		add_settings_field(
			$this->fields->override_unit_visibility->name,
			'Unit Visibility',
			[ $this, 'rentPress_override_unit_visibility' ],
			$this->wp_menu_args->menu_slug,
			self::$templateOverrideSectionID
		);

		add_settings_field(
			$this->fields->hide_floorplan_availability_counter->name,
			'Floor Plan Availability Counter',
			[ $this, 'rentPress_hide_floorplan_availability_counter' ],
			$this->wp_menu_args->menu_slug,
			self::$templateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_field(
			$this->fields->hide_floorplans_without_availability->name,
			'Floor Plans with No Availability',
			[ $this, 'rentPress_hide_floorplans_without_availability' ],
			$this->wp_menu_args->menu_slug,
			self::$templateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_section(
			self::$singleFloorplanTemplateOverrideSectionID,
			'Single Floor Plan Template',
			[ $this, 'single_floorplan_template_section_description' ],
			$this->wp_menu_args->menu_slug
		);
		add_settings_field(
			$this->fields->single_floorplan_content_position->name,
			'Single Floor Plan Content Position',
			[ $this, 'rentPress_single_floorplan_content_position' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_field(
			$this->fields->override_single_floorplan_template_title->name,
			'Floor Plan Title',
			[ $this, 'rentPress_override_single_floorplan_title' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_field(
			$this->fields->override_how_floorplan_pricing_is_display->name,
			'Floor Plan Price',
			[ $this, 'rentPress_override_how_floorplan_pricing_is_display_field' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_field(
			$this->fields->override_apply_links_targets->name,
			"Apply Link Opens In",
			[ $this, 'rentPress_override_apply_links_targets_field' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_field(
			$this->fields->override_request_link->name,
			'"Request More Info" URL',
			[ $this, 'override_request_link_field' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_field(
			$this->fields->single_floorplan_request_more_info_url->name,
			'"Request More Info" URL Override',
			[ $this, 'request_info_url_field' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-2'
			]
		);

		add_settings_field(
			$this->fields->show_waitlist_ctas->name,
			'Show Waitlist CTAs',
			[ $this, 'show_waitlist_ctas_field' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-3'
			]
		);

		add_settings_field(
			$this->fields->show_waitlist_override_url->name,
			'"Show Waitlist CTAs" URL Override',
			[ $this, 'show_waitlist_override_url_field' ],
			$this->wp_menu_args->menu_slug,
			self::$singleFloorplanTemplateOverrideSectionID,
			[
				'class' => 'field-group-6'
			]
		);

		add_settings_section(
			self::$floorplanGridTemplateOverrideSectionID,
			'Floor Plan Grid Template',
			[ $this, 'floorplan_grid_section_description' ],
			$this->wp_menu_args->menu_slug
		);

		add_settings_field(
			$this->fields->archive_floorplans_default_sort->name,
			'Default Sort',
			[ $this, 'rentPress_floorplans_default_sort' ],
			$this->wp_menu_args->menu_slug,
			self::$floorplanGridTemplateOverrideSectionID,
			[
				'class' => 'field-group-4'
			]
		);
		add_settings_field(
			$this->fields->archive_floorplan_content_position->name,
			'Floor Plans Grid Content Position',
			[ $this, 'rentPress_archive_floorplan_content_position' ],
			$this->wp_menu_args->menu_slug,
			self::$floorplanGridTemplateOverrideSectionID,
			[
				'class' => 'field-group-4'
			]
		);


		// add_settings_field(
		// 	$this->fields->override_single_property_template_file->name,
		// 	'Single Property Templates',
		// 	[$this, 'rentPress_override_single_property_file'],
		// 	$this->wp_menu_args->menu_slug,
		// 	self::$templateOverrideSectionID
		// );


		register_setting( self::$optionGroup, $this->fields->template_override->name );
		register_setting( self::$optionGroup, $this->fields->templates_accent_color->name );
		register_setting( self::$optionGroup, $this->fields->override_single_property_template_file->name );
		register_setting( self::$optionGroup, $this->fields->override_single_floorplan_template_file->name );
		register_setting( self::$optionGroup, $this->fields->override_how_floorplan_pricing_is_display->name );
		register_setting( self::$optionGroup, $this->fields->override_apply_links_targets->name );
		register_setting( self::$optionGroup, $this->fields->override_unit_visibility->name );
		register_setting( self::$optionGroup, $this->fields->hide_floorplan_availability_counter->name );
		register_setting( self::$optionGroup, $this->fields->hide_floorplans_without_availability->name );
		register_setting( self::$optionGroup, $this->fields->override_single_floorplan_template_title->name );
		register_setting( self::$optionGroup, $this->fields->override_archive_floorplans_template_file->name );
		register_setting( self::$optionGroup, $this->fields->archive_floorplans_default_sort->name );
		register_setting( self::$optionGroup, $this->fields->single_floorplan_content_position->name );
		register_setting( self::$optionGroup, $this->fields->archive_floorplan_content_position->name );
		register_setting( self::$optionGroup, $this->fields->override_request_link->name );
		register_setting( self::$optionGroup, $this->fields->single_floorplan_request_more_info_url->name );
		register_setting( self::$optionGroup, $this->fields->show_waitlist_ctas->name );
		register_setting( self::$optionGroup, $this->fields->show_waitlist_override_url->name );

	}

	/** General settings field renderings */

	/**
	 * Settings Section: API Credentials
	 * @return string [HTML of settings input]
	 */
	public function rentcafe_api_token() {
		?>
        <input type='text'
               name='<?php echo $this->fields->api_token->name; ?>'
               value='<?php echo $this->fields->api_token->value; ?>'
               placeholder='API Token'>

		<?php
	}

	public function rentcafe_api_username() {
		?>
        <input type='text'
               name='<?php echo $this->fields->api_username->name; ?>'
               value='<?php echo $this->fields->api_username->value; ?>'
               placeholder='API Username'>
		<?php
	}

	public function rentPress_site_is_about_single_property() {

		$isChecked = checked( $this->fields->is_site_about_a_single_property->value, 'true', false );
		?>
        <label>

            <input type='checkbox'
                   name='<?php echo $this->fields->is_site_about_a_single_property->name; ?>'
                   value='true'
				<?php echo $isChecked; ?>>

            This website is for a single property
        </label>
		<?php
	}

	public function rentPress_templates_accent_color() {

		echo "<input type='color' name='{$this->fields->templates_accent_color->name}' value='{$this->fields->templates_accent_color->value}'>";

	}

	// public function rentPress_override_single_property_file()
	// {
	// 	$options=[
	// 		"Use Custom Theme" => 'current-theme',
	// 		'Single Property Template' => RENTPRESS_PLUGIN_DIR . 'templates/Properties/single-property-basic.php',
	// 	];
	//
	// 	echo '<select name="'. $this->fields->override_single_property_template_file->name .'">';
	//
	// 		foreach ($options as $text => $optValue) {
	//
	// 			$isSelected=selected($this->fields->override_single_property_template_file->value, $optValue, false);
	//
	// 			echo '<option value="'. esc_attr($optValue) .'" '. $isSelected .'>'. $text .'</option>';
	//
	// 		}
	//
	// 	echo '</select>';
	// }

	public function rentPress_override_single_floorplan_file() {
		$isChecked = checked( $this->fields->override_single_floorplan_template_file->value, SITELEASING_PLUGIN_DIR . 'templates/FloorPlans/single-floorplan-basic.php', false );
		echo "<input id='rentpress_single_floorplan_setting' type='checkbox' name='" . $this->fields->override_single_floorplan_template_file->name . "' value='" . SITELEASING_PLUGIN_DIR . "templates/FloorPlans/single-floorplan-basic.php' {$isChecked}>";
	}

	public function rentPress_override_how_floorplan_pricing_is_display_field() {
		$options = [
			"Starting At" => 'starting-at',
			'Range'       => 'range',
		];

		echo '<select name="' . $this->fields->override_how_floorplan_pricing_is_display->name . '">';

		foreach ( $options as $text => $optValue ) {

			$isSelected = selected( $this->fields->override_how_floorplan_pricing_is_display->value, $optValue, false );

			echo '<option value="' . esc_attr( $optValue ) . '" ' . $isSelected . '>' . $text . '</option>';

		}

		echo '</select>';


	}

	public function rentPress_override_apply_links_targets_field() {
		$options = [
			"Same Window" => '_self',
			'New Window'  => '_blank',
		];

		echo '<select name="' . $this->fields->override_apply_links_targets->name . '">';

		foreach ( $options as $text => $optValue ) {

			$isSelected = selected( $this->fields->override_apply_links_targets->value, $optValue, false );

			echo '<option value="' . esc_attr( $optValue ) . '" ' . $isSelected . '>' . $text . '</option>';

		}

		echo '</select>';
	}

	public function rentPress_override_single_floorplan_title() {

		$isChecked = checked( $this->fields->override_single_floorplan_template_title->value, 'true', false );
		echo "
			<label>
				<input type='checkbox' name='{$this->fields->override_single_floorplan_template_title->name}' value='true' {$isChecked}>
				Use Floor Plan's Marketing Name
			</label>
		";

	}

	public function rentPress_hide_floorplan_availability_counter() {

		$isChecked = checked( $this->fields->hide_floorplan_availability_counter->value, 'true', false );
		echo "
			<label>
				<input type='checkbox' name='{$this->fields->hide_floorplan_availability_counter->name}' value='true' {$isChecked}>
				Hide floor plan availability counter
			</label>
		";

	}

	public function rentPress_hide_floorplans_without_availability() {

		$isChecked = checked( $this->fields->hide_floorplans_without_availability->value, 'true', false );
		echo "
			<label>
				<input type='checkbox' name='{$this->fields->hide_floorplans_without_availability->name}' value='true' {$isChecked}>
				Hide floor plans with no availability
			</label>
		";

	}

	// public function rentPress_override_unit_visibility() {

	// 	$isChecked = checked( $this->fields->override_unit_visibility->value, 'true', false );
	// 	echo "
	// 		<label>
	// 			<input type='checkbox' name='{$this->fields->override_unit_visibility->name}' value='true' {$isChecked}>
	// 			Show All Units
	// 		</label>
	// 	";

	// 	if ($this->options->getOption('disable_pricing_on_floorplan_with_no_available_units') === 'true') {

	// 		echo '<p><small style="color:red;">Warning: Having This Checked, may conflict with having the disable pricing for floorplans with no available units turned on!</small></p>';

	// 	}

	// }

	public function rentPress_override_unit_visibility() {
		$options = [
			"Show units with date and/or availability status" => 'unit_visibility_1',
			"Show units with availability status"             => 'unit_visibility_2',
			"Show units only available as of today"           => 'unit_visibility_3',
			"Show units available as of today plus Lookahead" => 'unit_visibility_4',
			"Show all units"                                  => 'unit_visibility_5'
		];

		echo '<select name="' . $this->fields->override_unit_visibility->name . '">';

		foreach ( $options as $text => $optValue ) {

			$isSelected = selected( $this->fields->override_unit_visibility->value, $optValue, false );

			echo '<option value="' . esc_attr( $optValue ) . '" ' . $isSelected . '>' . $text . '</option>';

		}

		echo '</select>';
	}

	public function rentPress_override_archive_floorplans_file() {

		$isChecked = checked( $this->fields->override_archive_floorplans_template_file->value, SITELEASING_PLUGIN_DIR . 'templates/FloorPlans/archive-floorplans-basic.php', false );
		echo "<input id='rentPress_archive_floorplan_setting' type='checkbox' name='" . $this->fields->override_archive_floorplans_template_file->name . "' value='" . SITELEASING_PLUGIN_DIR . "templates/FloorPlans/archive-floorplans-basic.php' {$isChecked}>";
	}

	public function rentPress_floorplans_default_sort() {
		$options = [
			"Soonest Available" => 'avail:asc',
			"Rent: Low to High" => 'rent:asc',
			"Rent: High to Low" => 'rent:desc',
			"SQFT: Low To High" => 'sqft:asc',
			"SQFT: High to Low" => 'sqft:desc',
			"Bedrooms"          => 'beds:asc'
		];

		echo '<select name="' . $this->fields->archive_floorplans_default_sort->name . '">';

		foreach ( $options as $text => $optValue ) {

			$isSelected = selected( $this->fields->archive_floorplans_default_sort->value, $optValue, false );

			echo '<option value="' . esc_attr( $optValue ) . '" ' . $isSelected . '>' . $text . '</option>';

		}

		echo '</select>';
	}

	public function rentPress_single_floorplan_content_position() {
		$options = [
			"Top of Page"    => 'single_floorplan_content_top',
			"Bottom of Page" => 'single_floorplan_content_bottom'
		];

		echo '<select name="' . $this->fields->single_floorplan_content_position->name . '">';

		foreach ( $options as $text => $optValue ) {

			$isSelected = selected( $this->fields->single_floorplan_content_position->value, $optValue, false );

			echo '<option value="' . esc_attr( $optValue ) . '" ' . $isSelected . '>' . $text . '</option>';

		}

		echo '</select>';
	}

	public function rentPress_archive_floorplan_content_position() {
		$options = [
			"Top of Page"    => 'archive_floorplan_content_top',
			"Bottom of Page" => 'archive_floorplan_content_bottom'
		];

		echo '<select name="' . $this->fields->archive_floorplan_content_position->name . '">';

		foreach ( $options as $text => $optValue ) {

			$isSelected = selected( $this->fields->archive_floorplan_content_position->value, $optValue, false );

			echo '<option value="' . esc_attr( $optValue ) . '" ' . $isSelected . '>' . $text . '</option>';

		}

		echo '</select>';
	}

	public function template_override_section_description() {
		echo '<p>Toggle on/off the default templates that come with this plugin. Uncheck checkboxes to build custom templates. <a href="https://30lines.com/rentpress/documentation/" target="_blank" rel="noopener" >Click here</a> for documentation.</p>';
	}

	public function single_floorplan_template_section_description() {
		echo '<p>Options specific to the Single Floor Plan template.</p>';
	}

	public function floorplan_grid_section_description() {
		echo '<p>Options specific to the Floor plans Grid template.</p>';
	}

	public function override_request_link_field() {
		$isChecked = checked( $this->fields->override_request_link->value, 'true', false );

		echo '<label for="override_request_link">';
		echo "<input id='override_request_link' type='checkbox' name='{$this->fields->override_request_link->name}' value='true' {$isChecked}>";

		echo '<i>Override default /contact/ link for "Request More Info" button</i>';
		echo '</label>';
	}

	public function request_info_url_field() {

		$default_url = site_url() . '/contact';
		echo "<input class='field-group-2-input' type='url' name='{$this->fields->single_floorplan_request_more_info_url->name}' value='{$this->fields->single_floorplan_request_more_info_url->value}' placeholder='$default_url'>";
		echo '<p><small>Enter the entire URL that you would like the "Request More Info" button to link to.</small></p>';
	}

	public function show_waitlist_ctas_field() {
		$isChecked = checked( $this->fields->show_waitlist_ctas->value, 'true', false );

		echo '<label for="show_waitlist_ctas">';
		echo "<input id='show_waitlist_ctas' type='checkbox' name='{$this->fields->show_waitlist_ctas->name}' value='true' {$isChecked}>";
		echo '</label>';
	}

	public function show_waitlist_override_url_field() {

		$default_url = site_url() . '/waitlist';
		echo "<input class='field-group-2-input' type='url' name='{$this->fields->show_waitlist_override_url->name}' value='{$this->fields->show_waitlist_override_url->value}' placeholder='$default_url'>";
		echo '<p><small>Override default /waitlist/ link for "Join Our Waitlist" button, by entering the entire URL that you would like to link to.</small></p>';
	}

}
