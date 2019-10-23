<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Site Leasing Admin Options Manager
 */

if ( ! class_exists( 'Site_Leasing_Options' ) ) :

	class Site_Leasing_Options {
		/**
		 * Site Leasing Options Constants
		 * These constants are used for Site Leasing core system indicators for checking whether the plugin is installed, 
		 * what version it is, and some other helpful things
		 * string literals
		 */
		const OPTION_INSTALLED = '_installed';
		const OPTION_VERSION = '_version';
		const MANAGE_OPTIONS = 'manage_options';

		/**
		 * Option keys as string literals
		 * @var string
		 */
		public $disablePricing_key = 'disable_pricing';
		public $disablePricingUrl_key = 'disable_pricing_url';
		public $disablePricingMessage_key = 'disable_pricing_message';

		/**
		 * Default option values
		 * @var [type]
		 */
		public $defaultOptionValues = [
			'google_js_default_map_center_latitude'     => 33.748815,
			'google_js_default_map_center_longitude'    => -84.391097,
		];

		/**
		 * @var Site_Leasing_Notifications
		 */
		private $notifications;

		public function __construct() {
			$this->notifications = new Site_Leasing_Notifications();
		}

		public function saveOrUpdate() {
			foreach ( $_REQUEST as $optionName => $optionValue ) {

				if ( $optionName == 'action' ) { // Make sure we don't try to add the action as a meta value
					continue;
				}

				$this->updateOption( $optionName, $optionValue );
			}
			echo $this->notifications->successResponse( 'Successfully updated Site Leasing settings!' );
			die();
		}

		/**
		 * Query MySQL DB for its version
		 * @return string|false
		 */
		public function getMySqlVersion() {
			global $wpdb;
			$rows = $wpdb->get_results( 'select version() as mysqlversion' );
			if ( ! empty( $rows ) ) {
				return $rows[0]->mysqlversion;
			}

			return false;
		}

		/**
		 * Cleanup: remove all options from the DB
		 * @return void
		 */
		protected function deleteSavedOptions() {
			$optionMetaData = $this->getOptionMetaData();
			if ( is_array( $optionMetaData ) ) {
				foreach ( $optionMetaData as $aOptionKey => $aOptionMeta ) {
					$prefixedOptionName = $this->prefix( $aOptionKey ); // how it is stored in DB
					delete_option( $prefixedOptionName );
				}
			}
		}

		/**
		 * @return string display name of the plugin to show as a name/title in HTML.
		 * Just returns the class name. Override this method to return something more readable
		 */
		public function getPluginDisplayName() {
			return 'Resource Site Leasing';
		}

		/**
		 * Get the prefixed version input $name suitable for storing in WP options
		 * Idempotent: if $optionName is already prefixed, it is not prefixed again, it is returned without change
		 *
		 * @param  $name string option name to prefix. Defined in settings.php and set as keys of $this->optionMetaData
		 *
		 * @return string
		 */
		public function prefix( $name ) {
			$optionNamePrefix = $this->getOptionNamePrefix();
			if ( strpos( $name, $optionNamePrefix ) === 0 ) { // 0 but not false
				return $name; // already prefixed
			}

			return $optionNamePrefix . $name;
		}

		/**
		 * Remove the prefix from the input $name.
		 * Idempotent: If no prefix found, just returns what was input.
		 *
		 * @param  $name string
		 *
		 * @return string $optionName without the prefix.
		 */
		public function &unPrefix( $name ) {
			$optionNamePrefix = $this->getOptionNamePrefix();
			if ( strpos( $name, $optionNamePrefix ) === 0 ) {
				return substr( $name, strlen( $optionNamePrefix ) );
			}

			return $name;
		}

		/**
		 * A wrapper function delegating to WP get_option() but it prefixes the input $optionName
		 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
		 *
		 * @param $optionName string defined in settings.php and set as keys of $this->optionMetaData
		 * @param $default string default value to return if the option is not set
		 *
		 * @return string the value from delegated call to get_option(), or optional default value
		 * if option is not set.
		 */
		public function getOption( $optionName, $default = null ) {

			$prefixedOptionName = $this->prefix( $optionName ); // how it is stored in DB
			$retVal             = get_option( $prefixedOptionName );

			if ( ! $retVal && $default ) {
				$retVal = $default;
			}

			return $retVal;
		}

		/**
		 * A wrapper function delegating to WP delete_option() but it prefixes the input $optionName
		 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
		 *
		 * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
		 *
		 * @return bool from delegated call to delete_option()
		 */
		public function deleteOption( $optionName ) {
			$prefixedOptionName = $this->prefix( $optionName ); // how it is stored in DB

			return delete_option( $prefixedOptionName );
		}

		/**
		 * A wrapper function delegating to WP add_option() but it prefixes the input $optionName
		 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
		 *
		 * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
		 * @param  $value mixed the new value
		 *
		 * @return null from delegated call to delete_option()
		 */
		public function addOption( $optionName, $value ) {

			$prefixedOptionName = $this->prefix( $optionName ); // how it is stored in DB

			return add_option( $prefixedOptionName, $value );
		}

		/**
		 * A wrapper function delegating to WP add_option() but it prefixes the input $optionName
		 * to enforce "scoping" the options in the WP options table thereby avoiding name conflicts
		 *
		 * @param  $optionName string defined in settings.php and set as keys of $this->optionMetaData
		 * @param  $value mixed the new value
		 *
		 * @return null from delegated call to delete_option()
		 */
		public function updateOption( $optionName, $value ) {

			$prefixedOptionName = $this->prefix( $optionName ); // how it is stored in DB

			return update_option( $prefixedOptionName, $value );
		}

		/**
		 * A Role Option is an option defined in getOptionMetaData() as a choice of WP standard roles, e.g.
		 * 'CanDoOperationX' => array('Can do Operation X', 'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber')
		 * The idea is use an option to indicate what role level a user must minimally have in order to do some operation.
		 * So if a Role Option 'CanDoOperationX' is set to 'Editor' then users which role 'Editor' or above should be
		 * able to do Operation X.
		 * Also see: canUserDoRoleOption()
		 *
		 * @param  $optionName
		 *
		 * @return string role name
		 */
		public function getRoleOption( $optionName ) {
			$roleAllowed = $this->getOption( $optionName );
			if ( ! $roleAllowed || $roleAllowed == '' ) {
				$roleAllowed = 'Administrator';
			}

			return $roleAllowed;
		}

		/**
		 * Given a WP role name, return a WP capability which only that role and roles above it have
		 * http://codex.wordpress.org/Roles_and_Capabilities
		 *
		 * @param  $roleName
		 *
		 * @return string a WP capability or '' if unknown input role
		 */
		protected function roleToCapability( $roleName ) {
			$realRoleName = '';
			switch ( $roleName ) {
				case 'Super Admin': // Will use the same as the Administrator option
				case 'Administrator':
					$realRoleName = self::MANAGE_OPTIONS;
					break;
				case 'Editor':
					$realRoleName = 'publish_pages';
					break;
				case 'Author':
					$realRoleName = 'publish_posts';
					break;
				case 'Contributor':
					$realRoleName = 'edit_posts';
					break;
				case 'Subscriber': // Will use the same as the Subscriber option
				case 'Anyone':
					$realRoleName = 'read';
					break;
				default:
					$realRoleName = '';
			}

			return $realRoleName;
		}

		/**
		 * @param $roleName string a standard WP role name like 'Administrator'
		 *
		 * @return bool
		 */
		public function isUserRoleEqualOrBetterThan( $roleName ) {
			if ( 'Anyone' == $roleName ) {
				return true;
			}
			$capability = $this->roleToCapability( $roleName );

			return current_user_can( $capability );
		}

		/**
		 * @param  $optionName string name of a Role option (see comments in getRoleOption())
		 *
		 * @return bool indicates if the user has adequate permissions
		 */
		public function canUserDoRoleOption( $optionName ) {
			$roleAllowed = $this->getRoleOption( $optionName );
			if ( 'Anyone' == $roleAllowed ) {
				return true;
			}

			return $this->isUserRoleEqualOrBetterThan( $roleAllowed );
		}

		public function getOptionNamePrefix() {
			return 'siteLeasing_';
		}

		/**
		 * @return bool indicating if the plugin is installed already
		 */
		public function isInstalled() {
			return $this->getOption( self::OPTION_INSTALLED );
		}

		/**
		 * Note in DB that the plugin is installed
		 * @return null
		 */
		public function markAsInstalled() {
			return $this->updateOption( self::OPTION_INSTALLED, true );
		}

		/**
		 * Note in DB that the plugin is uninstalled
		 * @return bool returned form delete_option.
		 * true implies the plugin was installed at the time of this call,
		 * false implies it was not.
		 */
		public function markAsUnInstalled() {
			return $this->deleteOption( self::OPTION_INSTALLED );
		}

		/**
		 * __get() is triggered when trying to access a property of the class that may or may not exist
		 *
		 * @param  [string] $name [Property key]
		 *
		 * @return void [mixed]       [Value of desired property]
		 */
		public function __get( $name ) {
			if ( property_exists( $this, $name ) ) {
				return $this->$name;
			}

			return;
		}

	}

endif; // class_exists check
