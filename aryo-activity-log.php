<?php
/*
Plugin Name: ARYO Activity Log
Plugin URI: http://wordpress.org/plugins/aryo-activity-log/
Description: Get aware of any activities that are taking place on your dashboard! Imagine it like a black-box for your WordPress site. e.g. post was deleted, plugin was activated, user logged in or logged out - it’s all these for you to see.
Author: Yakir Sitbon, Maor Chasen, Ariel Klikstein
Author URI: http://www.aryodigital.com
Version: 2.0.5
Text Domain: aryo-aal
Domain Path: /languages/
License: GPLv2 or later


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ACTIVITY_LOG__FILE__', __FILE__ );
define( 'ACTIVITY_LOG_BASE', plugin_basename( ACTIVITY_LOG__FILE__ ) );

include( 'classes/class-aal-maintenance.php' );
include( 'classes/class-aal-activity-log-list-table.php' );
include( 'classes/class-aal-admin-ui.php' );
include( 'classes/class-aal-settings.php' );
include( 'classes/class-aal-api.php' );
include( 'classes/class-aal-hooks.php' );

// Integrations
include( 'classes/class-aal-integration-woocommerce.php' );

// Probably we should put this in a separate file
class AAL_Main {

	/**
	 * @var AAL_Main The one true AAL_Main
	 * @since 2.0.5
	 */
	private static $_instance = null;

	/**
	 * @var AAL_Admin_Ui
	 * @since 1.0.0
	 */
	public $ui;

	/**
	 * @var AAL_Hooks
	 */
	public $hooks;

	/**
	 * @var AAL_Settings
	 * @since 1.0.0
	 */
	public $settings;

	/**
	 * @var AAL_API
	 * @since 2.0.5
	 */
	public $api;
	
	public function load_textdomain() {
		load_plugin_textdomain( 'aryo-aal', false, basename( dirname( __FILE__ ) ) . '/language' );
	}

	public function __construct() {
		global $wpdb;

		$this->ui       = new AAL_Admin_Ui();
		$this->hooks    = new AAL_Hooks();
		$this->settings = new AAL_Settings();
		$this->api      = new AAL_API();

		// set up our DB name
		$wpdb->activity_log = $wpdb->prefix . 'aryo_activity_log';
		
		add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ) );
	}

	/**
	 * @return AAL_Main
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new AAL_Main();
		return self::$_instance;
	}
	
}
AAL_Main::instance();

// EOF