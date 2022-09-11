<?php
defined( 'ABSPATH' ) OR exit;
if ( ! class_exists( 'pantherius_wp_charts_settings' ) ) {
	class pantherius_wp_charts_settings extends pantherius_wp_charts {
	/**
	* Construct the plugin object
	**/
		public function __construct() {
			/**
			* register actions, hook into WP's admin_init action hook
			**/
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
		}
		/**
		* include custom scripts and style to the admin page
		**/
		function enqueue_admin_custom_scripts_and_styles() {
			wp_enqueue_style( 'pantherius_wp_charts_admin_style', plugins_url( '/assets/css/pantherius_wp_charts_settings.css', __FILE__ ) );
			wp_enqueue_style( 'pantherius_wp_charts_admin_colorpicker_style', plugins_url( '/assets/css/colorpicker.css', __FILE__ ) );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'pantherius_wp_charts_admin_chart', plugins_url( '/assets/js/Chart.min.js', __FILE__ ), array( 'pantherius_wp_charts_admin' ), '100018', false );
			wp_enqueue_script( 'pantherius_wp_charts_script', plugins_url( '/assets/js/pantherius_wp_charts.js', __FILE__ ), array( 'jquery', 'pantherius_wp_charts_admin_chart' ), '1.0' );
			wp_enqueue_script( "pantherius_wp_charts_admin_colorpicker_script", plugins_url('/assets/js/colorpicker.js', __FILE__ ), array( 'jquery' ) );
			wp_register_script('pantherius_wp_charts_admin', plugins_url( '/assets/js/pantherius_wp_charts_admin.js', __FILE__ ) , array( 'jquery' ), '100018', false );
			wp_localize_script( 'pantherius_wp_charts_admin', 'sspa_params', array( 'plugin_url' => plugins_url( '', __FILE__ ), 'admin_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'pantherius_wp_charts_admin' );
		}
		/**
		* initialize datas on wp admin
		**/
		public function admin_init() {
			$settings_page = '';
			if ( isset( $_REQUEST[ 'page' ] ) ) {
				$settings_page = $_REQUEST[ 'page' ];
			}
			if ( $settings_page == 'pantherius_wp_charts' ) {
				add_action( 'admin_head', array( &$this, 'enqueue_admin_custom_scripts_and_styles' ) );
			}
			// Possibly do additional admin_init tasks
		}
		/**
		* add a menu
		**/		
		public function add_menu() {
			// Add a page to manage this plugin's settings
			add_menu_page( 'Charts and Graphs', 'Charts and Graphs', 'manage_options', 'pantherius_wp_charts', array( &$this, 'plugin_settings_page' ), 'dashicons-chart-bar', '65.014' );
		}
		/**
		* Menu Callback
		**/		
		public function plugin_settings_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', PWPC_CHARTS_TEXT_DOMAIN ) );
			}
			// Render the settings template
			include( sprintf( "%s/templates/settings.php", dirname( __FILE__ ) ) );
		}
		public function settings_section_wp_sap() {
		
		}
	}
}
?>