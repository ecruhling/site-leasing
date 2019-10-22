<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Site_Leasing_Notifications' ) ) :

	/**
	 * Construct notifications
	 */
	class Site_Leasing_Notifications {
		protected $type;

		public function __construct( $type = 'success' ) {
			$this->type = $type;
			$this->log  = new Site_Leasing_Logging_Log();
		}

		public function successResponse( $message ) {
			if ( is_admin() || is_super_admin() ) {
				return $this->notification( $message, 'success' );
			}
		}

		public function errorResponse( $message, $public = false, $kill = true ) {
			if ( $public ) {
				echo $this->notification( $message, 'error' );
			} else if ( ( is_admin() || is_super_admin() ) ) {
				$this->log->error( $message ); // Log all the errors
				if ( $kill ) {
					return $this->notification( $message, 'error' );
				}
			}
		}

		public function notification( $message, $type ) {
			$background = $this->fetchNotificationBackgroundColor( $type );

			return "<div style='background:{$background};padding:20px;color:white;'>" .
			       __( $message, SITELEASING_TEXT_DOMAIN ) . "</div>";
		}

		public function fetchNotificationBackgroundColor( $type ) {
			switch ( $type ) {
				case 'success':
					return '#0099cc';
				case 'error':
					return '#d32f2f';
				default:
					return '#efefef';
			}
		}

		/**
		 * Sets the value of type.
		 *
		 * @param mixed $type the type
		 *
		 * @return self
		 */
		protected function setType( $type ) {
			$this->type = $type;

			return $this;
		}
	}

endif; // class_exists check
