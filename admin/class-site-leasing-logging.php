<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Site_Leasing_Logging_Log' ) ) :

	/**
	 * Logs Site Leasing Events, Errors, and General Process Information
	 */
	class Site_Leasing_Logging_Log extends Site_Leasing_Base_Logger {
		public function event( $message ) {
			$this->log( $message, 'event' );
		}

		public function info( $message ) {
			$this->log( $message, 'info' );
		}

		public function error( $message ) {
			$this->log( $message, 'error' );
		}

		public function warning( $message ) {
			$this->log( $message, 'warning' );
		}

	}

endif; // class_exists check
