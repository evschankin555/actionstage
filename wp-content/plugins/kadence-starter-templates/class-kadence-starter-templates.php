<?php
/**
 * Importer class.
 *
 * @package Kadence Starter Templates
 */

namespace Kadence_Starter_Templates;

use function activate_plugin;
use function plugins_api;
use function wp_send_json_error;

/**
 * Block direct access to the main plugin file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Main plugin class with initialization tasks.
 */
class Starter_Templates {
	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * The instance of the Importer class.
	 *
	 * @var object
	 */
	public $importer;

	/**
	 * The resulting page's hook_suffix, or false if the user does not have the capability required.
	 *
	 * @var boolean or string
	 */
	private $plugin_page;

	/**
	 * Holds the verified import files.
	 *
	 * @var array
	 */
	public $import_files;

	/**
	 * The path of the log file.
	 *
	 * @var string
	 */
	public $log_file_path;

	/**
	 * The index of the `import_files` array (which import files was selected).
	 *
	 * @var int
	 */
	private $selected_index;

	/**
	 * The palette for the import.
	 *
	 * @var string
	 */
	private $selected_palette;

	/**
	 * The font for the import.
	 *
	 * @var string
	 */
	private $selected_font;

	/**
	 * The page for the import.
	 *
	 * @var string
	 */
	private $selected_page;

	/**
	 * Import Single Override colors
	 *
	 * @var boolean
	 */
	private $override_colors;

	/**
	 * Import Single Override fonts
	 *
	 * @var boolean
	 */
	private $override_fonts;

	/**
	 * The paths of the actual import files to be used in the import.
	 *
	 * @var array
	 */
	private $selected_import_files;

	/**
	 * Holds any error messages, that should be printed out at the end of the import.
	 *
	 * @var string
	 */
	public $frontend_error_messages = array();

	/**
	 * Was the before content import already triggered?
	 *
	 * @var boolean
	 */
	private $before_import_executed = false;

	/**
	 * Make plugin page options available to other methods.
	 *
	 * @var array
	 */
	private $plugin_page_setup = array();

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Construct function
	 */
	public function __construct() {
		// Set plugin constants.
		$this->set_plugin_constants();
		$this->include_plugin_files();
		add_action( 'init', array( $this, 'init_config' ) );
		add_action( 'init', array( $this, 'load_api_settings' ) );
		if ( is_admin() ) {
			// Ajax Calls.
			add_action( 'wp_ajax_kadence_import_demo_data', array( $this, 'import_demo_data_ajax_callback' ) );
			add_action( 'wp_ajax_kadence_import_install_plugins', array( $this, 'install_plugins_ajax_callback' ) );
			add_action( 'wp_ajax_kadence_import_customizer_data', array( $this, 'import_customizer_data_ajax_callback' ) );
			add_action( 'wp_ajax_kadence_after_import_data', array( $this, 'after_all_import_data_ajax_callback' ) );
			add_action( 'wp_ajax_kadence_import_single_data', array( $this, 'import_demo_single_data_ajax_callback' ) );
			add_action( 'wp_ajax_kadence_remove_past_import_data', array( $this, 'remove_past_data_ajax_callback' ) );
		}
		add_action( 'init', array( $this, 'setup_plugin_with_filter_data' ) );
		// Text Domain.
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		// Filters durning Import.
		add_filter( 'kadence-starter-templates/import_files', array( $this, 'kadence_import_kadence_theme_files' ) );
		add_action( 'kadence-starter-templates/after_import', array( $this, 'kadence_kadence_theme_after_import' ), 10, 3 );

		add_action( 'kadence-starter-templates/after_import', array( $this, 'kadence_elementor_after_import' ), 20, 3 );

		add_filter( 'plugin_action_links_kadence-starter-templates/kadence-starter-templates.php', array( $this, 'add_settings_link' ) );
	}
	/**
	 * Add a little css for submenu items.
	 */
	public function basic_css_menu_support() {
		wp_register_style( 'kadence-import-admin', false );
		wp_enqueue_style( 'kadence-import-admin' );
		$css = '#menu-appearance .wp-submenu a[href^="themes.php?page=kadence-"]:before {content: "\21B3";margin-right: 0.5em;opacity: 0.5;}';
		wp_add_inline_style( 'kadence-import-admin', $css );
	}
	/**
	 * Kadence Import
	 */
	public function init_config() {
		if ( class_exists( 'Kadence\Theme' ) && defined( 'KADENCE_VERSION' ) && version_compare( KADENCE_VERSION, '0.8.0', '>=' ) ) {
			add_action( 'kadence_theme_admin_menu', array( $this, 'create_admin_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'basic_css_menu_support' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'create_admin_page' ) );
		}
	}
	/**
	 * Kadence After Import functions.
	 *
	 * @param array $selected_import the selected import.
	 */
	public function kadence_kadence_theme_after_import( $selected_import, $selected_palette, $selected_font ) {
		if ( 'agency' === $selected_import['import_file_name'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Agency Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'agency_free' === $selected_import['import_file_name'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Agency Menu', 'nav_menu' );
			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Blog' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'food' === $selected_import['import_file_name'] ) {

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Food Primary', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
					'footer'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Recipes' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'shopping' === $selected_import['import_file_name'] ) {
			// Assign Woo Pages.
			if ( class_exists( 'woocommerce' ) ) {
				$this->import_demo_woocommerce();
			}

			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Shop Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Shop News' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'saas' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Saas Menu', 'nav_menu' );
			$footer_menu = get_term_by( 'name', 'Saas Footer', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
					'footer'  => $footer_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'yoga' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Yoga Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'ldcourse' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'e01' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Main Real Estate', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'footer'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Blog' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'e02' === $selected_import['import_file_name'] ) {

			// Assign Woo Pages.
			if ( class_exists( 'woocommerce' ) ) {
				$this->import_demo_woocommerce();
			}
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Store Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
			}

		} elseif ( 'e03' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Course Main', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'mobile' => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Blog' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'e06' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Control Menu', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Blog' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		} elseif ( 'g01' === $selected_import['import_file_name'] ) {
			// Assign menus to their locations.
			$main_menu = get_term_by( 'name', 'Main Real Estate', 'nav_menu' );

			set_theme_mod(
				'nav_menu_locations',
				array(
					'primary' => $main_menu->term_id,
					'footer'  => $main_menu->term_id,
				)
			);

			// Assign front page.
			$homepage = get_page_by_title( 'Home' );
			if ( isset( $homepage ) && $homepage->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $homepage->ID ); // Front Page.
				$blogpage = get_page_by_title( 'Blog' );
				update_option( 'page_for_posts', $blogpage->ID ); // Blog Page.
			}

		}
		if ( $selected_palette && ! empty( $selected_palette ) ) {
			$palette_presets = json_decode( '{"base":[{"color":"#2B6CB0"},{"color":"#265E9A"},{"color":"#222222"},{"color":"#3B3B3B"},{"color":"#515151"},{"color":"#626262"},{"color":"#E1E1E1"},{"color":"#F7F7F7"},{"color":"#ffffff"}],"bright":[{"color":"#255FDD"},{"color":"#00F2FF"},{"color":"#1A202C"},{"color":"#2D3748"},{"color":"#4A5568"},{"color":"#718096"},{"color":"#EDF2F7"},{"color":"#F7FAFC"},{"color":"#ffffff"}],"darkmode":[{"color":"#3296ff"},{"color":"#003174"},{"color":"#ffffff"},{"color":"#f7fafc"},{"color":"#edf2f7"},{"color":"#cbd2d9"},{"color":"#2d3748"},{"color":"#252c39"},{"color":"#1a202c"}],"orange":[{"color":"#e47b02"},{"color":"#ed8f0c"},{"color":"#1f2933"},{"color":"#3e4c59"},{"color":"#52606d"},{"color":"#7b8794"},{"color":"#f3f4f7"},{"color":"#f9f9fb"},{"color":"#ffffff"}],"pinkish":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#040037"},{"color":"#032075"},{"color":"#514d7c"},{"color":"#666699"},{"color":"#deddeb"},{"color":"#efeff5"},{"color":"#f8f9fa"}],"pinkishdark":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#f8f9fa"},{"color":"#efeff5"},{"color":"#deddeb"},{"color":"#c3c2d6"},{"color":"#514d7c"},{"color":"#221e5b"},{"color":"#040037"}],"green":[{"color":"#049f82"},{"color":"#008f72"},{"color":"#222222"},{"color":"#353535"},{"color":"#454545"},{"color":"#676767"},{"color":"#eeeeee"},{"color":"#f7f7f7"},{"color":"#ffffff"}],"fire":[{"color":"#dd6b20"},{"color":"#cf3033"},{"color":"#27241d"},{"color":"#423d33"},{"color":"#504a40"},{"color":"#625d52"},{"color":"#e8e6e1"},{"color":"#faf9f7"},{"color":"#ffffff"}],"mint":[{"color":"#2cb1bc"},{"color":"#13919b"},{"color":"#0f2a43"},{"color":"#133453"},{"color":"#587089"},{"color":"#829ab1"},{"color":"#e0fcff"},{"color":"#f5f7fa"},{"color":"#ffffff"}],"rich":[{"color":"#295CFF"},{"color":"#0E94FF"},{"color":"#1C0D5A"},{"color":"#3D3D3D"},{"color":"#57575D"},{"color":"#636363"},{"color":"#E1EBEE"},{"color":"#EFF7FB"},{"color":"#ffffff"}],"fem":[{"color":"#D86C97"},{"color":"#282828"},{"color":"#282828"},{"color":"#333333"},{"color":"#4d4d4d"},{"color":"#646464"},{"color":"#f7dede"},{"color":"#F6F2EF"},{"color":"#ffffff"}],"hot":[{"color":"#FF5698"},{"color":"#000000"},{"color":"#020202"},{"color":"#020202"},{"color":"#4E4E4E"},{"color":"#808080"},{"color":"#FDEDEC"},{"color":"#FDF6EE"},{"color":"#ffffff"}],"bold":[{"color":"#000000"},{"color":"#D1A155"},{"color":"#000000"},{"color":"#010101"},{"color":"#111111"},{"color":"#282828"},{"color":"#F6E7BC"},{"color":"#F9F7F7"},{"color":"#ffffff"}],"teal":[{"color":"#7ACFC4"},{"color":"#044355"},{"color":"#000000"},{"color":"#010101"},{"color":"#111111"},{"color":"#282828"},{"color":"#F5ECE5"},{"color":"#F9F7F7"},{"color":"#ffffff"}]}', true );
			if ( isset( $palette_presets[ $selected_palette ] ) ) {
				$default = json_decode( '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}', true );
				$default['palette'][0]['color'] = $palette_presets[ $selected_palette ][0]['color'];
				$default['palette'][1]['color'] = $palette_presets[ $selected_palette ][1]['color'];
				$default['palette'][2]['color'] = $palette_presets[ $selected_palette ][2]['color'];
				$default['palette'][3]['color'] = $palette_presets[ $selected_palette ][3]['color'];
				$default['palette'][4]['color'] = $palette_presets[ $selected_palette ][4]['color'];
				$default['palette'][5]['color'] = $palette_presets[ $selected_palette ][5]['color'];
				$default['palette'][6]['color'] = $palette_presets[ $selected_palette ][6]['color'];
				$default['palette'][7]['color'] = $palette_presets[ $selected_palette ][7]['color'];
				$default['palette'][8]['color'] = $palette_presets[ $selected_palette ][8]['color'];
				update_option( 'kadence_global_palette', json_encode( $default ) );
			}
		}
		if ( class_exists( 'Kadence\Theme' ) ) {
			if ( $selected_font && ! empty( $selected_font ) ) {
				switch ( $selected_font ) {
					case 'montserrat':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Montserrat';
						$current['google']  = true;
						$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Source Sans Pro';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
					case 'playfair':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Playfair Display';
						$current['google']  = true;
						$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
						set_theme_mod( 'heading_font', $current );
						$h1_font = \Kadence\kadence()->option( 'h1_font' );
						$h1_font['weight'] = 'normal';
						$h1_font['variant'] = 'regualar';
						set_theme_mod( 'h1_font', $h1_font );
						$h2_font = \Kadence\kadence()->option( 'h2_font' );
						$h2_font['weight'] = 'normal';
						$h2_font['variant'] = 'regualar';
						set_theme_mod( 'h2_font', $h2_font );
						$h3_font = \Kadence\kadence()->option( 'h3_font' );
						$h3_font['weight'] = 'normal';
						$h3_font['variant'] = 'regualar';
						set_theme_mod( 'h3_font', $h3_font );
						$h4_font = \Kadence\kadence()->option( 'h4_font' );
						$h4_font['weight'] = 'normal';
						$h4_font['variant'] = 'regualar';
						set_theme_mod( 'h4_font', $h4_font );
						$h5_font = \Kadence\kadence()->option( 'h5_font' );
						$h5_font['weight'] = 'normal';
						$h5_font['variant'] = 'regualar';
						set_theme_mod( 'h5_font', $h5_font );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Raleway';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
					case 'oswald':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Oswald';
						$current['google']  = true;
						$current['variant'] = array( '200', '300', 'regular', '500', '600', '700' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Open Sans';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
					case 'antic':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Antic Didone';
						$current['google']  = true;
						$current['variant'] = array( 'regular' );
						set_theme_mod( 'heading_font', $current );
						$h1_font = \Kadence\kadence()->option( 'h1_font' );
						$h1_font['weight'] = 'normal';
						$h1_font['variant'] = 'regualar';
						set_theme_mod( 'h1_font', $h1_font );
						$h2_font = \Kadence\kadence()->option( 'h2_font' );
						$h2_font['weight'] = 'normal';
						$h2_font['variant'] = 'regualar';
						set_theme_mod( 'h2_font', $h2_font );
						$h3_font = \Kadence\kadence()->option( 'h3_font' );
						$h3_font['weight'] = 'normal';
						$h3_font['variant'] = 'regualar';
						set_theme_mod( 'h3_font', $h3_font );
						$h4_font = \Kadence\kadence()->option( 'h4_font' );
						$h4_font['weight'] = 'normal';
						$h4_font['variant'] = 'regualar';
						set_theme_mod( 'h4_font', $h4_font );
						$h5_font = \Kadence\kadence()->option( 'h5_font' );
						$h5_font['weight'] = 'normal';
						$h5_font['variant'] = 'regualar';
						set_theme_mod( 'h5_font', $h5_font );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Raleway';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
					case 'gilda':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Gilda Display';
						$current['google']  = true;
						$current['variant'] = array( 'regular' );
						set_theme_mod( 'heading_font', $current );
						$h1_font = \Kadence\kadence()->option( 'h1_font' );
						$h1_font['weight'] = 'normal';
						$h1_font['variant'] = 'regualar';
						set_theme_mod( 'h1_font', $h1_font );
						$h2_font = \Kadence\kadence()->option( 'h2_font' );
						$h2_font['weight'] = 'normal';
						$h2_font['variant'] = 'regualar';
						set_theme_mod( 'h2_font', $h2_font );
						$h3_font = \Kadence\kadence()->option( 'h3_font' );
						$h3_font['weight'] = 'normal';
						$h3_font['variant'] = 'regualar';
						set_theme_mod( 'h3_font', $h3_font );
						$h4_font = \Kadence\kadence()->option( 'h4_font' );
						$h4_font['weight'] = 'normal';
						$h4_font['variant'] = 'regualar';
						set_theme_mod( 'h4_font', $h4_font );
						$h5_font = \Kadence\kadence()->option( 'h5_font' );
						$h5_font['weight'] = 'normal';
						$h5_font['variant'] = 'regualar';
						set_theme_mod( 'h5_font', $h5_font );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Raleway';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
					case 'cormorant':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Cormorant Garamond';
						$current['google']  = true;
						$current['variant'] = array( '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Proza Libre';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
					case 'libre':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Libre Franklin';
						$current['google']  = true;
						$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Libre Baskerville';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;

					case 'lora':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Lora';
						$current['google']  = true;
						$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
						set_theme_mod( 'heading_font', $current );
						$h1_font = \Kadence\kadence()->option( 'h1_font' );
						$h1_font['weight'] = 'normal';
						$h1_font['variant'] = 'regualar';
						set_theme_mod( 'h1_font', $h1_font );
						$h2_font = \Kadence\kadence()->option( 'h2_font' );
						$h2_font['weight'] = 'normal';
						$h2_font['variant'] = 'regualar';
						set_theme_mod( 'h2_font', $h2_font );
						$h3_font = \Kadence\kadence()->option( 'h3_font' );
						$h3_font['weight'] = 'normal';
						$h3_font['variant'] = 'regualar';
						set_theme_mod( 'h3_font', $h3_font );
						$h4_font = \Kadence\kadence()->option( 'h4_font' );
						$h4_font['weight'] = 'normal';
						$h4_font['variant'] = 'regualar';
						set_theme_mod( 'h4_font', $h4_font );
						$h5_font = \Kadence\kadence()->option( 'h5_font' );
						$h5_font['weight'] = 'normal';
						$h5_font['variant'] = 'regualar';
						set_theme_mod( 'h5_font', $h5_font );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Merriweather';
						$body['google'] = true;
						$body['weight'] = '300';
						$body['variant'] = '300';
						set_theme_mod( 'base_font', $body );
						break;

					case 'proza':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Proza Libre';
						$current['google']  = true;
						$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Open Sans';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;

					case 'worksans':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Work Sans';
						$current['google']  = true;
						$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Work Sans';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;

					case 'josefin':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Josefin Sans';
						$current['google']  = true;
						$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Lato';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;

					case 'nunito':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Nunito';
						$current['google']  = true;
						$current['variant'] = array( '200', '200italic', '300', '300italic', 'regular', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Roboto';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
					case 'rubik':
						$current = \Kadence\kadence()->option( 'heading_font' );
						$current['family']  = 'Rubik';
						$current['google']  = true;
						$current['variant'] = array( '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
						set_theme_mod( 'heading_font', $current );
						$body = \Kadence\kadence()->option( 'base_font' );
						$body['family'] = 'Karla';
						$body['google'] = true;
						set_theme_mod( 'base_font', $body );
						break;
				}
			}
		}
	}
	/**
	 * Kadence Import function.
	 */
	public function import_demo_woocommerce( $shop = 'Shop', $cart = 'Cart', $checkout = 'Checkout', $myaccount = 'My Account' ) {
		$woopages = array(
			'woocommerce_shop_page_id'      => $shop,
			'woocommerce_cart_page_id'      => $cart,
			'woocommerce_checkout_page_id'  => $checkout,
			'woocommerce_myaccount_page_id' => $myaccount,
		);
		foreach ( $woopages as $woo_page_name => $woo_page_title ) {
			$woopage = get_page_by_title( $woo_page_title );
			if ( isset( $woopage ) && $woopage->ID ) {
				update_option( $woo_page_name, $woopage->ID );
			}
		}

		// We no longer need to install pages.
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );

		// Flush rules after install.
		flush_rewrite_rules();
	}
	/**
	 * Kadence Import function.
	 */
	public function kadence_import_kadence_theme_files() {
		$woocommerce = array(
			'base'         => 'woocommerce',
			'slug'         => 'woocommerce',
			'path'         => 'woocommerce/woocommerce.php',
			'title'        => 'Woocommerce',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'woocommerce/woocommerce.php' ),
		);
		$elementor = array(
			'base'         => 'elementor',
			'slug'         => 'elementor',
			'path'         => 'elementor/elementor.php',
			'title'        => 'Elementor',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'elementor/elementor.php' ),
		);
		$fluentform = array(
			'base'         => 'fluentform',
			'slug'         => 'fluentform',
			'path'         => 'fluentform/fluentform.php',
			'title'        => 'Fluent Forms',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'fluentform/fluentform.php' ),
		);
		$kadence_blocks = array(
			'base'         => 'kadence-blocks',
			'slug'         => 'kadence-blocks',
			'path'         => 'kadence-blocks/kadence-blocks.php',
			'title'        => 'Kadence Blocks',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'kadence-blocks/kadence-blocks.php' ),
		);
		$kadence_blocks_pro = array(
			'base'         => 'kadence-blocks-pro',
			'slug'         => 'kadence-blocks-pro',
			'path'         => 'kadence-blocks-pro/kadence-blocks-pro.php',
			'title'        => 'Kadence Block Pro',
			'src'          => 'bundle',
			'state'        => Plugin_Check::active_check( 'kadence-blocks-pro/kadence-blocks-pro.php' ),
		);
		$wpzoom_recipe_card = array(
			'base'         => 'recipe-card-blocks-by-wpzoom',
			'slug'         => 'wpzoom-recipe-card',
			'path'         => 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php',
			'title'        => 'Recipe Card Blocks by WPZOOM',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php' ),
		);
		$learn_dash = array(
			'base'         => 'sfwd-lms',
			'slug'         => 'sfwd_lms',
			'path'         => 'sfwd-lms/sfwd_lms.php',
			'title'        => 'LearnDash',
			'src'          => 'thirdparty',
			'state'        => Plugin_Check::active_check( 'sfwd-lms/sfwd_lms.php' ),
		);
		$demos = array(
			'agency' => array(
				'import_file_name'           => 'agency',
				'categories'                 => array( 'Kadence Blocks Pro' ),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/og01pro/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/og01pro/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-agency/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/og01pro/imagespreview-image.jpg',
				'import_notice'              => '',
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01pro/home_content.xml',
						'meta'    => 'clear',
					),
					'services' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01pro/services_content.xml',
						'meta'    => 'clear',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01pro/about_content.xml',
						'meta'    => 'clear',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01pro/contact_content.xml',
						'meta'    => 'clear',
					),
				),
				'plugins'                    => array(
					$kadence_blocks,
					$kadence_blocks_pro,
				),
			),
			'agency_free' => array(
				'import_file_name'           => 'agency_free',
				'categories'                 => array(),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/og01/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/og01/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/agency-free/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/og01/images/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01/home_content.xml',
						'meta'    => 'clear',
					),
					'services' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01/services_content.xml',
						'meta'    => 'clear',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01/about_content.xml',
						'meta'    => 'clear',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og01/contact_content.xml',
						'meta'    => 'clear',
					),
				),
			),
			'food' => array(
				'import_file_name'           => 'food',
				'categories'                 => array(),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/og06/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/og06/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/food/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/og06/image/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
					$wpzoom_recipe_card,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og06/home_content.xml',
						'meta'    => 'clear',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og06/about_content.xml',
						'meta'    => 'onherit',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og06/contact_content.xml',
						'meta'    => 'inherit',
					),
				),
			),
			'shopping' => array(
				'import_file_name'           => 'shopping',
				'categories'                 => array( 'Woocommerce' ),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/og04/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/og04/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/og04/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-store/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/og04/images/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
					$woocommerce,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og04/home_content.xml',
						'meta'    => 'clear',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og04/about_content.xml',
						'meta'    => 'clear',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og04/contact_content.xml',
						'meta'    => 'clear',
					),
				),
			),
			'yoga' => array(
				'import_file_name'           => 'yoga',
				'categories'                 => array( 'Business' ),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/og05/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/og05/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/og05/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-active/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/og05/images/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og05/home_content.xml',
						'meta'    => 'clear',
					),
					'gallery' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og05/gallery_content.xml',
						'meta'    => 'clear',
					),
					'pricing' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og05/pricing_content.xml',
						'meta'    => 'clear',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og05/about_content.xml',
						'meta'    => 'clear',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og05/contact_content.xml',
						'meta'    => 'clear',
					),
				),
			),
			'saas' => array(
				'import_file_name'           => 'saas',
				'categories'                 => array( 'Business' ),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/og03/demo_content.xml',
				'import_widget_file_url'     => '',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/og03/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/blocks-saas/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/og03/images/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og03/home_content.xml',
						'meta'    => 'clear',
					),
					'features' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og03/features_content.xml',
						'meta'    => 'clear',
					),
					'pricing' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og03/pricing_content.xml',
						'meta'    => 'clear',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og03/about_content.xml',
						'meta'    => 'clear',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og03/contact_content.xml',
						'meta'    => 'clear',
					),
				),
			),
			'ldcourse' => array(
				'import_file_name'           => 'ldcourse',
				'categories'                 => array( 'Business' ),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/og02/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/og02/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/og02/theme_options.json',
				'preview_url'                => 'https://demos.kadencewp.com/course/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/og02/preview-image.jpg',
				'import_notice'              => '',
				'plugins'                    => array(
					$kadence_blocks,
					$learn_dash,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og02/home_content.xml',
						'meta'    => 'clear',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og02/about_content.xml',
						'meta'    => 'clear',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/og02/contact_content.xml',
						'meta'    => 'clear',
					),
				),
			),
			'e01' => array(
				'import_file_name'           => 'e01',
				'categories'                 => array(),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/e01/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/e01/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/e01/theme_options.json',
				'import_forms_file_url'      => 'https://startertemplatecloud.com/importer-content/e01/form_data.json',
				'preview_url'                => 'https://startertemplatecloud.com/e01/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/e01/preview-image.jpg',
				'import_notice'              => '',
				'type'                       => 'elementor',
				'width'                      => 'large',
				'plugins'                    => array(
					$elementor,
					$fluentform,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e01/home_content.xml',
						'meta'    => 'inherit',
					),
					'services' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e01/services_content.xml',
						'meta'    => 'inherit',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e01/about_content.xml',
						'meta'    => 'inherit',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e01/contact_content.xml',
						'meta'    => 'inherit',
						'form'    => true,
					),
					'brokerage' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e01/brokerage_content.xml',
						'meta'    => 'inherit',
					),
					'norman' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e01/norman_content.xml',
						'meta'    => 'inherit',
					),
				),
			),
			'e02' => array(
				'import_file_name'           => 'e02',
				'categories'                 => array(),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/e02/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/e02/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/e02/theme_options.json',
				'import_forms_file_url'      => 'https://startertemplatecloud.com/importer-content/e02/form_data.json',
				'preview_url'                => 'https://startertemplatecloud.com/e02/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/e02/preview-image.jpg',
				'import_notice'              => '',
				'type'                       => 'elementor',
				'plugins'                    => array(
					$elementor,
					$fluentform,
					$woocommerce,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e02/home_content.xml',
						'meta'    => 'inherit',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e02/about_content.xml',
						'meta'    => 'inherit',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e02/contact_content.xml',
						'meta'    => 'inherit',
						'form'    => true,
					),
				),
			),
			'e03' => array(
				'import_file_name'           => 'e03',
				'categories'                 => array(),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/e03/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/e03/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/e03/theme_options.json',
				'import_forms_file_url'      => 'https://startertemplatecloud.com/importer-content/e03/form_data.json',
				'preview_url'                => 'https://startertemplatecloud.com/e03/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/e03/images/preview-image.jpg',
				'import_notice'              => '',
				'type'                       => 'elementor',
				'width' => 'large',
				'plugins'                    => array(
					$elementor,
					$fluentform,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e03/home_content.xml',
						'meta'    => 'inherit',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e03/about_content.xml',
						'meta'    => 'inherit',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e03/contact_content.xml',
						'meta'    => 'inherit',
						'form'    => true,
					),
					'courses' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e03/courses_content.xml',
						'meta'    => 'inherit',
					),
					'course' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e03/course_content.xml',
						'meta'    => 'inherit',
					),
				),
			),
			'e06' => array(
				'import_file_name'           => 'e06',
				'categories'                 => array(),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/e06/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/e06/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/e06/theme_options.json',
				'import_forms_file_url'      => '',
				'preview_url'                => 'https://startertemplatecloud.com/e06/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/e06/images/preview-image.jpg',
				'import_notice'              => '',
				'type'                       => 'elementor',
				'plugins'                    => array(
					$elementor,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e06/home_content.xml',
						'meta'    => 'inherit',
					),
					'features' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e06/features_content.xml',
						'meta'    => 'inherit',
					),
					'pricing' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e06/pricing_content.xml',
						'meta'    => 'inherit',
					),
					'support' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e06/support_content.xml',
						'meta'    => 'inherit',
					),
					'templates' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/e06/templates_content.xml',
						'meta'    => 'inherit',
					),
				),
			),
			'g01' => array(
				'import_file_name'           => 'g01',
				'categories'                 => array(),
				'import_file_url'            => 'https://startertemplatecloud.com/importer-content/g01/demo_content.xml',
				'import_widget_file_url'     => 'https://startertemplatecloud.com/importer-content/g01/widget_data.json',
				'import_customizer_file_url' => 'https://startertemplatecloud.com/importer-content/g01/theme_options.json',
				'preview_url'                => 'https://startertemplatecloud.com/g01/',
				'import_preview_image_url'   => 'https://startertemplatecloud.com/importer-content/g01/preview-image.jpg',
				'import_notice'              => '',
				'type'                       => 'blocks',
				'plugins'                    => array(
					$kadence_blocks,
				),
				'pages'    => array(
					'home' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/g01/home_content.xml',
						'meta'    => 'inherit',
					),
					'services' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/g01/services_content.xml',
						'meta'    => 'inherit',
					),
					'about' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/g01/about_content.xml',
						'meta'    => 'inherit',
					),
					'contact' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/g01/contact_content.xml',
						'meta'    => 'inherit',
					),
					'brokerage' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/g01/brokerage_content.xml',
						'meta'    => 'inherit',
					),
					'norman' => array(
						'content' => 'https://startertemplatecloud.com/importer-content/g01/norman_content.xml',
						'meta'    => 'inherit',
					),
				),
			),
		);
		return $demos;
	}
	/**
	 * Private clone method to prevent cloning of the instance of the *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone() {}


	/**
	 * Private unserialize method to prevent unserializing of the *Singleton* instance.
	 *
	 * @return void
	 */
	private function __wakeup() {}

	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'KADENCE_STARTER_TEMPLATES_PATH' ) ) {
			define( 'KADENCE_STARTER_TEMPLATES_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'KADENCE_STARTER_TEMPLATES_URL' ) ) {
			define( 'KADENCE_STARTER_TEMPLATES_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		}
		if ( ! defined( 'KADENCE_STARTER_TEMPLATES_VERSION' ) ) {
			define( 'KADENCE_STARTER_TEMPLATES_VERSION', '1.1.0' );
		}
	}
	/**
	 * Include all plugin files.
	 */
	private function include_plugin_files() {
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-author-meta.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-import-export-option.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-plugin-check.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-helpers.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-import-actions.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-widget-importer.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-logger.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-logger-cli.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-importer.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-downloader.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-customizer-importer.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-import-elementor.php';
		require_once KADENCE_STARTER_TEMPLATES_PATH . 'inc/class-import-fluent.php';
	}

	/**
	 * Add settings link
	 *
	 * @param array $links holds plugin links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'themes.php?page=kadence-starter-templates' ) . '">' . __( 'View Template Library', 'kadence-starter-templates' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Creates the plugin page and a submenu item in WP Appearance menu.
	 */
	public function create_admin_page() {
		$page = add_theme_page(
			esc_html__( 'Starter Templates by Kadence WP', 'kadence-starter-templates' ),
			esc_html__( 'Starter Templates', 'kadence-starter-templates' ),
			'import',
			'kadence-starter-templates',
			array( $this, 'render_admin_page' )
		);
		add_action( 'admin_print_styles-' . $page, array( $this, 'scripts' ) );
	}

	/**
	 * Plugin page display.
	 * Output (HTML) is in another file.
	 */
	public function render_admin_page() {
		?>
		<div class="wrap kadence_theme_starter_dash">
			<div class="kadence_theme_starter_dashboard">
				<h2 class="notices" style="display:none;"></h2>
				<?php settings_errors(); ?>
				<div class="page-grid">
					<div class="kadence_starter_dashboard_main">
					</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Loads admin style sheets and scripts
	 */
	public function scripts() {
		$woocommerce = array(
			'title'        => 'Woocommerce',
			'state'        => Plugin_Check::active_check( 'woocommerce/woocommerce.php' ),
			'src'          => 'repo',
		);
		$elementor = array(
			'title'        => 'Elementor',
			'state'        => Plugin_Check::active_check( 'elementor/elementor.php' ),
			'src'          => 'repo',
		);
		$kadence_blocks = array(
			'title'        => 'Kadence Blocks',
			'state'        => Plugin_Check::active_check( 'kadence-blocks/kadence-blocks.php' ),
			'src'          => 'repo',
		);
		$kadence_blocks_pro = array(
			'title'        => 'Kadence Block Pro',
			'state'        => Plugin_Check::active_check( 'kadence-blocks-pro/kadence-blocks-pro.php' ),
			'src'          => 'bundle',
		);
		$fluentform = array(
			'title'        => 'Fluent Forms',
			'src'          => 'repo',
			'state'        => Plugin_Check::active_check( 'fluentform/fluentform.php' ),
		);
		$wpzoom_recipe_card = array(
			'title'        => 'Recipe Card Blocks by WPZOOM',
			'state'        => Plugin_Check::active_check( 'recipe-card-blocks-by-wpzoom/wpzoom-recipe-card.php' ),
			'src'          => 'repo',
		);
		$learn_dash = array(
			'title'        => 'LearnDash',
			'state'        => Plugin_Check::active_check( 'sfwd-lms/sfwd_lms.php' ),
			'src'          => 'thirdparty',
		);
		if ( 'notactive' !== $kadence_blocks_pro['state'] ) {
			$agency = array(
				'key'      => 0,
				'slug'     => 'agency',
				'name'     => __( 'Agency', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'portfolio', 'kadence-starter-templates' ),
					__( 'services', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
					__( 'transparent', 'kadence-starter-templates' ),
				),
				'url'        => 'https://demos.kadencewp.com/blocks-agency/',
				'categories' => array( 'business' ),
				'plugins'    => array(
					$kadence_blocks,
					$kadence_blocks_pro,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/home.jpg',
					),
					'services' => array(
						'id'    => 'services',
						'title' => __( 'Services', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/services/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/services.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/og01/images/preview-image.jpg',
			);
		} else {
			$agency = array(
				'key'      => 0,
				'slug'     => 'agency_free',
				'name'     => __( 'Agency', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'portfolio', 'kadence-starter-templates' ),
					__( 'services', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
					__( 'transparent', 'kadence-starter-templates' ),
				),
				'url'        => 'https://demos.kadencewp.com/agency-free/',
				'categories' => array( 'business' ),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/home.jpg',
					),
					'services' => array(
						'id'    => 'services',
						'title' => __( 'Services', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/services/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/services.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/agency-free/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/og01/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/og01/images/preview-image.jpg',
				'plugins'    => array(
					$kadence_blocks,
				),
			);
		}
		$templates = array(
			$agency,
			array(
				'key'  => 1,
				'slug' => 'g01',
				'name' => __( 'Real Estate', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'reale state', 'kadence-starter-templates' ),
					__( 'house', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://startertemplatecloud.com/g01/',
				'categories' => array( 'business' ),
				'type' => 'blocks',
				'plugins'  => array(
					$kadence_blocks,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/g01/',
						'image' => 'https://startertemplatecloud.com/importer-content/g01/images/home.jpg',
					),
					'services' => array(
						'id'    => 'services',
						'title' => __( 'Services', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/g01/services/',
						'image' => 'https://startertemplatecloud.com/importer-content/g01/images/services.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/g01/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/g01/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/g01/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/g01/images/contact.jpg',
					),
					'brokerage' => array(
						'id'    => 'brokerage',
						'title' => __( 'Brokerage', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/g01/brokerage/',
						'image' => 'https://startertemplatecloud.com/importer-content/g01/images/brokerage.jpg',
					),
					'norman' => array(
						'id'    => 'norman',
						'title' => __( '418 Norman Street', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/g01/418-norman-street/',
						'image' => 'https://startertemplatecloud.com/importer-content/g01/images/norman.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/g01/images/preview-image.jpg',
			),
			array(
				'key'  => 2,
				'slug' => 'ldcourse',
				'name' => __( 'LearnDash Course', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'course', 'kadence-starter-templates' ),
					__( 'learndash', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/course/',
				'categories' => array( 'business' ),
				'plugins'  => array(
					$kadence_blocks,
					$learn_dash,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/course/',
						'image' => 'https://startertemplatecloud.com/importer-content/og02/images/home.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/course/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/og02/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/course/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/og02/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/og02/images/preview-image.jpg',
			),
			array(
				'key'  => 3,
				'slug' => 'food',
				'name' => __( 'Recipe Blog', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'blog', 'kadence-starter-templates' ),
					__( 'food', 'kadence-starter-templates' ),
					__( 'recipe', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/food/',
				'categories' => array( 'blog' ),
				'plugins'  => array(
					$kadence_blocks,
					$wpzoom_recipe_card,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/food/',
						'image' => 'https://startertemplatecloud.com/importer-content/og06/images/home.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/food/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/og06/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/food/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/og06/images/contact.jpg',
					),
				),
				'image' => 'https://kadence.design/importer/kadence/recipe_blog/preview-image.jpg',
			),
			array(
				'key'  => 4,
				'slug' => 'yoga',
				'name' => __( 'Yoga Studio', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'yoga', 'kadence-starter-templates' ),
					__( 'gym', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/blocks-active/',
				'categories' => array( 'business' ),
				'plugins'  => array(
					$kadence_blocks,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-active/',
						'image' => 'https://startertemplatecloud.com/importer-content/og05/images/home.jpg',
					),
					'gallery' => array(
						'id'    => 'gallery',
						'title' => __( 'Gallery', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-active/yoga-studio-gallery/',
						'image' => 'https://startertemplatecloud.com/importer-content/og05/images/gallery.jpg',
					),
					'pricing' => array(
						'id'    => 'pricing',
						'title' => __( 'Pricing', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-active/yoga-pricing/',
						'image' => 'https://startertemplatecloud.com/importer-content/og05/images/pricing.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-active/yoga-about/',
						'image' => 'https://startertemplatecloud.com/importer-content/og05/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-active/yoga-contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/og05/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/og05/images/preview-image.jpg',
			),
			array(
				'key'  => 5,
				'slug' => 'saas',
				'name' => __( 'Saas', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'saas', 'kadence-starter-templates' ),
					__( 'pricing', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/blocks-saas/',
				'categories' => array( 'business' ),
				'plugins'  => array(
					$kadence_blocks,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-saas/',
						'image' => 'https://startertemplatecloud.com/importer-content/og03/images/home.jpg',
					),
					'features' => array(
						'id'    => 'features',
						'title' => __( 'Features', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-saas/features/',
						'image' => 'https://startertemplatecloud.com/importer-content/og03/images/services.jpg',
					),
					'pricing' => array(
						'id'    => 'pricing',
						'title' => __( 'Pricing', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-saas/pricing/',
						'image' => 'https://startertemplatecloud.com/importer-content/og03/images/pricing.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-saas/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/og03/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-saas/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/og03/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/og03/images/preview-image.jpg',
			),
			array(
				'key'  => 6,
				'slug' => 'shopping',
				'name' => __( 'Shopping', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'ecommerce', 'kadence-starter-templates' ),
					__( 'shopping', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://demos.kadencewp.com/blocks-store/',
				'categories' => array( 'ecommerce' ),
				'plugins'  => array(
					$kadence_blocks,
					$woocommerce,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-store/',
						'image' => 'https://startertemplatecloud.com/importer-content/og04/images/home.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-store/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/og04/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://demos.kadencewp.com/blocks-store/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/og04/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/og04/images/preview-image.jpg',
			),
		);
		$etemplates = array(
			array(
				'key'  => 0,
				'slug' => 'e01',
				'name' => __( 'Real Estate', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'reale state', 'kadence-starter-templates' ),
					__( 'house', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://startertemplatecloud.com/e01/',
				'categories' => array( 'business' ),
				'type' => 'elementor',
				'width' => 'large',
				'plugins'  => array(
					$fluentform,
					$elementor,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e01/',
						'image' => 'https://startertemplatecloud.com/importer-content/e01/images/home.jpg',
					),
					'services' => array(
						'id'    => 'services',
						'title' => __( 'Services', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e01/services/',
						'image' => 'https://startertemplatecloud.com/importer-content/e01/images/services.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e01/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/e01/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e01/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/e01/images/contact.jpg',
					),
					'brokerage' => array(
						'id'    => 'brokerage',
						'title' => __( 'Brokerage', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e01/brokerage/',
						'image' => 'https://startertemplatecloud.com/importer-content/e01/images/brokerage.jpg',
					),
					'norman' => array(
						'id'    => 'norman',
						'title' => __( '418 Norman Street', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e01/418-norman-street/',
						'image' => 'https://startertemplatecloud.com/importer-content/e01/images/norman.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/e01/images/preview-image.jpg',
			),
			array(
				'key'  => 1,
				'slug' => 'e02',
				'name' => __( 'Print Shop', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'store', 'kadence-starter-templates' ),
					__( 'ecommerce', 'kadence-starter-templates' ),
					__( 'woocommerce', 'kadence-starter-templates' ),
				),
				'url' => 'https://startertemplatecloud.com/e02/',
				'categories' => array( 'business', 'ecommerce' ),
				'type' => 'elementor',
				'plugins'  => array(
					$fluentform,
					$elementor,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e02/',
						'image' => 'https://startertemplatecloud.com/importer-content/e02/images/home.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e02/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/e02/images/about.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e02/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/e02/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/e02/images/preview-image.jpg',
			),
			array(
				'key'  => 2,
				'slug' => 'e03',
				'name' => __( 'Course', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'lms', 'kadence-starter-templates' ),
					__( 'course', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://startertemplatecloud.com/e03/',
				'categories' => array( 'business', 'course' ),
				'type' => 'elementor',
				'plugins'  => array(
					$fluentform,
					$elementor,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e03/',
						'image' => 'https://startertemplatecloud.com/importer-content/e03/images/home.jpg',
					),
					'about' => array(
						'id'    => 'about',
						'title' => __( 'About', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e03/about/',
						'image' => 'https://startertemplatecloud.com/importer-content/e03/images/about.jpg',
					),
					'courses' => array(
						'id'    => 'courses',
						'title' => __( 'Courses', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e03/courses/',
						'image' => 'https://startertemplatecloud.com/importer-content/e03/images/courses.jpg',
					),
					'course' => array(
						'id'    => 'course',
						'title' => __( 'Course', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e03/course/',
						'image' => 'https://startertemplatecloud.com/importer-content/e03/images/course.jpg',
					),
					'contact' => array(
						'id'    => 'contact',
						'title' => __( 'Contact', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e03/contact/',
						'image' => 'https://startertemplatecloud.com/importer-content/e03/images/contact.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/e03/images/preview-image.jpg',
			),
			array(
				'key'  => 3,
				'slug' => 'e06',
				'name' => __( 'Control B', 'kadence-starter-templates' ),
				'keywords' => array(
					__( 'saas', 'kadence-starter-templates' ),
					__( 'software', 'kadence-starter-templates' ),
					__( 'business', 'kadence-starter-templates' ),
				),
				'url' => 'https://startertemplatecloud.com/e06/',
				'categories' => array( 'business' ),
				'type' => 'elementor',
				'plugins'  => array(
					$elementor,
				),
				'pages'    => array(
					'home' => array(
						'id'    => 'home',
						'title' => __( 'Home', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e06/',
						'image' => 'https://startertemplatecloud.com/importer-content/e06/images/home.jpg',
					),
					'features' => array(
						'id'    => 'features',
						'title' => __( 'Features', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e06/features/',
						'image' => 'https://startertemplatecloud.com/importer-content/e06/images/features.jpg',
					),
					'pricing' => array(
						'id'    => 'pricing',
						'title' => __( 'Pricing', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e06/price/',
						'image' => 'https://startertemplatecloud.com/importer-content/e06/images/pricing.jpg',
					),
					'support' => array(
						'id'    => 'support',
						'title' => __( 'Support', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e06/support/',
						'image' => 'https://startertemplatecloud.com/importer-content/e06/images/support.jpg',
					),
					'templates' => array(
						'id'    => 'templates',
						'title' => __( 'Templates', 'kadence-starter-templates' ),
						'url'   => 'https://startertemplatecloud.com/e06/templates/',
						'image' => 'https://startertemplatecloud.com/importer-content/e06/images/templates.jpg',
					),
				),
				'image' => 'https://startertemplatecloud.com/importer-content/e06/images/preview-image.jpg',
			),
		);
		$palettes = array(
			array(
				'palette' => 'base',
				'colors' => array(
					'#2B6CB0',
					'#3B3B3B',
					'#E1E1E1',
					'#F7F7F7',
					'#ffffff',
				),
			),
			array(
				'palette' => 'orange',
				'colors' => array(
					'#e47b02',
					'#3E4C59',
					'#F3F4F7',
					'#F9F9FB',
					'#ffffff',
				),
			),
			array(
				'palette' => 'pinkish',
				'colors' => array(
					'#E21E51',
					'#032075',
					'#DEDDEB',
					'#EFEFF5',
					'#ffffff',
				),
			),
			array(
				'palette' => 'mint',
				'colors' => array(
					'#2cb1bc',
					'#133453',
					'#e0fcff',
					'#f5f7fa',
					'#ffffff',
				),
			),
			array(
				'palette' => 'green',
				'colors' => array(
					'#049f82',
					'#353535',
					'#EEEEEE',
					'#F7F7F7',
					'#ffffff',
				),
			),
			array(
				'palette' => 'rich',
				'colors' => array(
					'#295CFF',
					'#1C0D5A',
					'#E1EBEE',
					'#EFF7FB',
					'#ffffff',
				),
			),
			array(
				'palette' => 'fem',
				'colors' => array(
					'#D86C97',
					'#282828',
					'#f7dede',
					'#F6F2EF',
					'#ffffff',
				),
			),
			array(
				'palette' => 'teal',
				'colors' => array(
					'#7ACFC4',
					'#000000',
					'#F6E7BC',
					'#F9F7F7',
					'#ffffff',
				),
			),
			array(
				'palette' => 'bold',
				'colors' => array(
					'#000000',
					'#000000',
					'#F6E7BC',
					'#F9F7F7',
					'#ffffff',
				),
			),
			array(
				'palette' => 'hot',
				'colors' => array(
					'#FF5698',
					'#000000',
					'#FDEDEC',
					'#FDF6EE',
					'#ffffff',
				),
			),
			array(
				'palette' => 'darkmode',
				'colors' => array(
					'#3296ff',
					'#F7FAFC',
					'#2D3748',
					'#252C39',
					'#1a202c',
				),
			),
			array(
				'palette' => 'pinkishdark',
				'colors' => array(
					'#E21E51',
					'#EFEFF5',
					'#514D7C',
					'#221E5B',
					'#040037',
				),
			),
		);
		$fonts = array(
			array(
				'name' => 'Montserrat & Source Sans Pro',
				'font' => 'montserrat',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/montserrat.jpg',
			),
			array(
				'name' => 'Libre Franklin & Libre Baskerville',
				'font' => 'libre',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/libre.jpg',
			),
			array(
				'name' => 'Proza Libre & Open Sans',
				'font' => 'proza',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/proza.jpg',
			),
			array(
				'name' => 'Work Sans & Work Sans',
				'font' => 'worksans',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/worksans.jpg',
			),
			array(
				'name' => 'Josefin Sans & Lato',
				'font' => 'josefin',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/josefin.jpg',
			),
			array(
				'name' => 'Oswald & Open Sans',
				'font' => 'oswald',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/oswald.jpg',
			),
			array(
				'name' => 'Nunito & Roboto',
				'font' => 'nunito',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/nunito.jpg',
			),
			array(
				'name' => 'Rubik & Karla',
				'font' => 'rubik',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/rubik.jpg',
			),
			array(
				'name' => 'Lora & Merriweather',
				'font' => 'lora',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/lora.jpg',
			),
			array(
				'name' => 'Playfair Display & Raleway',
				'font' => 'playfair',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/playfair.jpg',
			),
			array(
				'name' => 'Antic Didone & Raleway',
				'font' => 'antic',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/antic.jpg',
			),
			array(
				'name' => 'Gilda Display & Raleway',
				'font' => 'gilda',
				'img'  => KADENCE_STARTER_TEMPLATES_URL . 'assets/images/fonts/gilda.jpg',
			),
		);
		$old_data = get_option( '_kadence_starter_templates_last_import_data', array() );
		$has_content = false;
		if ( ! empty( $old_data ) ) {
			$has_content  = true;
			$has_previous = true;
		}
		// Check for multiple posts.
		if ( false === $has_content ) {
			$has_content = ( 1 < wp_count_posts()->publish ? true : false );
		}
		if ( false === $has_content ) {
			// Check for multiple pages.
			$has_content = ( 1 < wp_count_posts( 'page' )->publish ? true : false );
		}
		if ( false === $has_content ) {
			// Check for multiple images.
			$has_content = ( 0 < wp_count_posts( 'attachment' )->inherit ? true : false );
		}
		wp_enqueue_style( 'kadence-starter-templates', KADENCE_STARTER_TEMPLATES_URL . 'assets/css/starter-templates.css', array( 'wp-components' ), KADENCE_STARTER_TEMPLATES_VERSION );
		wp_enqueue_script( 'kadence-starter-templates', KADENCE_STARTER_TEMPLATES_URL . 'assets/js/starter-templates.js', array( 'jquery', 'wp-i18n', 'wp-element', 'wp-plugins', 'wp-components', 'wp-api', 'wp-hooks', 'wp-edit-post', 'lodash', 'wp-block-library', 'wp-block-editor', 'wp-editor' ), KADENCE_STARTER_TEMPLATES_VERSION, true );
		wp_localize_script(
			'kadence-starter-templates',
			'kadenceStarterParams',
			array(
				'ajax_url'             => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'           => wp_create_nonce( 'kadence-ajax-verification' ),
				'pro'                  => false,
				'isKadence'            => class_exists( 'Kadence\Theme' ),
				'templates'            => $templates,
				'etemplates'           => $etemplates,
				'palettes'             => $palettes,
				'fonts'                => $fonts,
				'logo'                 => esc_attr( KADENCE_STARTER_TEMPLATES_URL . 'assets/images/kadence_logo.png' ),
				'has_content'          => $has_content,
				'has_previous'         => $has_previous,
				'starterSettings'      => get_option( 'kadence_starter_templates_config' ),
				'notice'               => esc_html__( 'Please Note: Full site importing is designed for new/empty sites with no content. Your site customizer settings, widgets, menus will all be overridden.', 'kadence-starter-templates' ),
				'notice_previous'      => esc_html( 'Please Note: Full site importing is designed for new/empty sites with no content. Your site customizer settings, widgets, menus will all be overridden. It is recommended that you enable "Delete Previously Imported Posts and Images" if you are testing out different starter templates.'),
				'remove_progress'      => esc_html__( 'Removing Past Imported Content', 'kadence-starter-templates' ),
				'plugin_progress'      => esc_html__( 'Checking/Installing/Activating Required Plugins', 'kadence-starter-templates' ),
				'content_progress'     => esc_html__( 'Importing Demo Content...', 'kadence-starter-templates' ),
				'content_new_progress' => esc_html__( 'Importing Demo Content... Still Importing.', 'kadence-starter-templates' ),
				'widgets_progress'      => esc_html__( 'Importing Menus/Widgets...', 'kadence-starter-templates' ),
				'customizer_progress'  => esc_html__( 'Importing Customizer Settings...', 'kadence-starter-templates' ),
			)
		);
	}
	/**
	 * Register settings
	 */
	public function load_api_settings() {
		register_setting(
			'kadence_starter_templates_config',
			'kadence_starter_templates_config',
			array(
				'type'              => 'string',
				'description'       => __( 'Config Kadence Starter Templates', 'kadence-blocks' ),
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => '',
			)
		);
	}
	/**
	 * AJAX callback to install a plugin.
	 */
	public function install_plugins_ajax_callback() {
		Helpers::verify_ajax_call();

		if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['selected'] ) ) {
			wp_send_json_error();
		}
		// Get selected file index or set it to 0.
		$selected_index = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
		//error_log( print_r( $this->import_files[ $selected_index ], true ) );
		$info = $this->import_files[ $selected_index ];
		$install = true;

		if ( isset( $info['plugins'] ) && ! empty( $info['plugins'] ) ) {

			if ( ! function_exists( 'plugins_api' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
			}
			if ( ! class_exists( 'WP_Upgrader' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			}

			foreach( $info['plugins'] as $key => $plugin ) {
				if ( 'notactive' === $plugin['state'] && 'thirdparty' !== $plugin['src'] ) {
					$api = plugins_api(
						'plugin_information',
						array(
							'slug' => $plugin['base'],
							'fields' => array(
								'short_description' => false,
								'sections' => false,
								'requires' => false,
								'rating' => false,
								'ratings' => false,
								'downloaded' => false,
								'last_updated' => false,
								'added' => false,
								'tags' => false,
								'compatibility' => false,
								'homepage' => false,
								'donate_link' => false,
							),
						)
					);
					if ( ! is_wp_error( $api ) ) {

						// Use AJAX upgrader skin instead of plugin installer skin.
						// ref: function wp_ajax_install_plugin().
						$upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );

						$installed = $upgrader->install( $api->download_link );
						if ( $installed ) {
							$activate = activate_plugin( $plugin['path'], '', false, true );
							if ( is_wp_error( $activate ) ) {
								$install = false;
							}
						} else {
							$install = false;
						}
					} else {
						$install = false;
					}
				} elseif ( 'installed' === $plugin['state'] ) {
					$activate = activate_plugin( $plugin['path'], '', false, true );
					if ( is_wp_error( $activate ) ) {
						$install = false;
					}
				}
			}
		}

		if ( false === $install ) {
			wp_send_json_error();
		} else {
			wp_send_json( array( 'status' => 'pluginSuccess' ) );
		}
	}
	/**
	 * AJAX callback to remove past content..
	 */
	public function remove_past_data_ajax_callback() {
		Helpers::verify_ajax_call();

		if ( ! current_user_can( 'customize' ) ) {
			wp_send_json_error();
		}
		global $wpdb;

		$removed_content = true;

		$post_ids = $wpdb->get_col( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_kadence_starter_templates_imported_post'" );
		$term_ids = $wpdb->get_col( "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key='_kadence_starter_templates_imported_term'" );
		if ( isset( $post_ids ) && is_array( $post_ids ) ) {
			foreach ( $post_ids as $post_id ) {
				$worked = wp_delete_post( $post_id, true );
				if ( false === $worked ) {
					$removed_content = false;
				}
			}
		}
		if ( isset( $term_ids ) && is_array( $term_ids ) ) {
			foreach ( $term_ids as $term_id ) {
				$term = get_term( $term_id );
				if ( ! is_wp_error( $term ) ) {
					wp_delete_term( $term_id, $term->taxonomy );
				}
			}
		}

		if ( false === $removed_content ) {
			wp_send_json_error();
		} else {
			wp_send_json( array( 'status' => 'removeSuccess' ) );
		}
	}
	/**
	 * Main AJAX callback function for:
	 * 1). prepare import files (uploaded or predefined via filters)
	 * 2). execute 'before content import' actions (before import WP action)
	 * 3). import content
	 * 4). execute 'after content import' actions (before widget import WP action, widget import, customizer import, after import WP action)
	 */
	public function import_demo_single_data_ajax_callback() {
		// Try to update PHP memory limit (so that it does not run out of it).
		ini_set( 'memory_limit', apply_filters( 'kadence-starter-templates/import_memory_limit', '350M' ) );

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();
		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->use_existing_importer_data();

		if ( ! $use_existing_importer_data ) {

			// Create a date and time string to use for demo and log file names.
			Helpers::set_demo_import_start_time();

			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Define log file path.
				$this->log_file_path = Helpers::get_log_path();
			} else {
				$this->log_file_path = '';
			}
			// Get selected file index or set it to 0.
			$this->selected_index = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
			$this->selected_page = empty( $_POST['page_id'] ) ? '' : sanitize_text_field( $_POST['page_id'] );
			$this->override_colors = 'true' === $_POST['override_colors'] ? true : false;
			$this->override_fonts = 'true' === $_POST['override_fonts'] ? true : false;
			$this->selected_palette = empty( $_POST['palette'] ) ? '' : sanitize_text_field( $_POST['palette'] );
			$this->selected_font    = empty( $_POST['font'] ) ? '' : sanitize_text_field( $_POST['font'] );
			/**
			 * 1). Prepare import files.
			 * Predefined import files via filter: kadence-starter-templates/import_files
			 */
			if ( ! empty( $this->import_files[ $this->selected_index ] ) && ! empty( $this->selected_page ) && isset( $this->import_files[ $this->selected_index ]['pages'] ) && isset( $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ] ) ) { // Use predefined import files from wp filter: kadence-starter-templates/import_files.

				// Download the import files (content, widgets and customizer files).
				$this->selected_import_files = Helpers::download_import_file( $this->import_files[ $this->selected_index ], $this->selected_page );

				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {
					// Write error to log file and send an AJAX response with the error.
					Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'kadence-starter-templates' )
					);
				}
				if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
					// Add this message to log file.
					$log_added = Helpers::append_to_file(
						sprintf(
							__( 'The import files for: %s were successfully downloaded!', 'kadence-starter-templates' ),
							$this->import_files[ $this->selected_index ]['import_file_name']
						) . Helpers::import_file_info( $this->selected_import_files ),
						$this->log_file_path,
						esc_html__( 'Downloaded files' , 'kadence-starter-templates' )
					);
				}
			} else {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
		}

		// Save the initial import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );

		// If elementor make sure the defaults are off.
		if ( isset( $this->import_files[ $this->selected_index ]['type'] ) && 'elementor' === $this->import_files[ $this->selected_index ]['type'] ) {
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
			if ( class_exists( 'Kadence\Theme' ) ) {
				$component = \Kadence\Theme::instance()->components['elementor'];
				if ( $component ) {
					$component->elementor_add_theme_colors();
				}
			}
		}

		/**
		 * 3). Import content (if the content XML file is set for this import).
		 * Returns any errors greater then the "warning" logger level, that will be displayed on front page.
		 */
		$new_post = '';
		if ( ! empty( $this->selected_import_files['content'] ) ) {
			$meta = ( ! empty( $this->import_files[ $this->selected_index ] ) && ! empty( $this->selected_page ) && isset( $this->import_files[ $this->selected_index ]['pages'] ) && isset( $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ] ) && isset( $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ]['meta'] ) ? $this->import_files[ $this->selected_index ]['pages'][ $this->selected_page ]['meta'] : 'inherit' );
			$logger = $this->importer->import_content( $this->selected_import_files['content'], true, $meta );
			if ( is_object( $logger ) && $logger->error_output ) {
				$this->append_to_frontend_error_messages( $logger->error_output );
			} elseif ( is_object( $logger ) && $logger->messages ) {
				$messages = $logger->messages;
				if ( isset( $messages[1] ) && isset( $messages[1]['level'] ) && 'debug' == $messages[1]['level'] && isset( $messages[1]['message'] ) && ! empty( $messages[1]['message'] ) ) {
					$pieces   = explode( ' ', $messages[1]['message'] );
					$new_post = array_pop( $pieces );
				}
			}
		}

		if ( $this->override_colors ) {
			if ( $this->selected_palette && ! empty( $this->selected_palette ) ) {
				$palette_presets = json_decode( '{"base":[{"color":"#2B6CB0"},{"color":"#265E9A"},{"color":"#222222"},{"color":"#3B3B3B"},{"color":"#515151"},{"color":"#626262"},{"color":"#E1E1E1"},{"color":"#F7F7F7"},{"color":"#ffffff"}],"bright":[{"color":"#255FDD"},{"color":"#00F2FF"},{"color":"#1A202C"},{"color":"#2D3748"},{"color":"#4A5568"},{"color":"#718096"},{"color":"#EDF2F7"},{"color":"#F7FAFC"},{"color":"#ffffff"}],"darkmode":[{"color":"#3296ff"},{"color":"#003174"},{"color":"#ffffff"},{"color":"#f7fafc"},{"color":"#edf2f7"},{"color":"#cbd2d9"},{"color":"#2d3748"},{"color":"#252c39"},{"color":"#1a202c"}],"orange":[{"color":"#e47b02"},{"color":"#ed8f0c"},{"color":"#1f2933"},{"color":"#3e4c59"},{"color":"#52606d"},{"color":"#7b8794"},{"color":"#f3f4f7"},{"color":"#f9f9fb"},{"color":"#ffffff"}],"pinkish":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#040037"},{"color":"#032075"},{"color":"#514d7c"},{"color":"#666699"},{"color":"#deddeb"},{"color":"#efeff5"},{"color":"#f8f9fa"}],"pinkishdark":[{"color":"#E21E51"},{"color":"#4d40ff"},{"color":"#f8f9fa"},{"color":"#efeff5"},{"color":"#deddeb"},{"color":"#c3c2d6"},{"color":"#514d7c"},{"color":"#221e5b"},{"color":"#040037"}],"green":[{"color":"#049f82"},{"color":"#008f72"},{"color":"#222222"},{"color":"#353535"},{"color":"#454545"},{"color":"#676767"},{"color":"#eeeeee"},{"color":"#f7f7f7"},{"color":"#ffffff"}],"fire":[{"color":"#dd6b20"},{"color":"#cf3033"},{"color":"#27241d"},{"color":"#423d33"},{"color":"#504a40"},{"color":"#625d52"},{"color":"#e8e6e1"},{"color":"#faf9f7"},{"color":"#ffffff"}],"mint":[{"color":"#2cb1bc"},{"color":"#13919b"},{"color":"#0f2a43"},{"color":"#133453"},{"color":"#587089"},{"color":"#829ab1"},{"color":"#e0fcff"},{"color":"#f5f7fa"},{"color":"#ffffff"}],"rich":[{"color":"#295CFF"},{"color":"#0E94FF"},{"color":"#1C0D5A"},{"color":"#3D3D3D"},{"color":"#57575D"},{"color":"#636363"},{"color":"#E1EBEE"},{"color":"#EFF7FB"},{"color":"#ffffff"}],"fem":[{"color":"#D86C97"},{"color":"#282828"},{"color":"#282828"},{"color":"#333333"},{"color":"#4d4d4d"},{"color":"#646464"},{"color":"#f7dede"},{"color":"#F6F2EF"},{"color":"#ffffff"}],"hot":[{"color":"#FF5698"},{"color":"#000000"},{"color":"#020202"},{"color":"#020202"},{"color":"#4E4E4E"},{"color":"#808080"},{"color":"#FDEDEC"},{"color":"#FDF6EE"},{"color":"#ffffff"}],"bold":[{"color":"#000000"},{"color":"#D1A155"},{"color":"#000000"},{"color":"#010101"},{"color":"#111111"},{"color":"#282828"},{"color":"#F6E7BC"},{"color":"#F9F7F7"},{"color":"#ffffff"}],"teal":[{"color":"#7ACFC4"},{"color":"#044355"},{"color":"#000000"},{"color":"#010101"},{"color":"#111111"},{"color":"#282828"},{"color":"#F5ECE5"},{"color":"#F9F7F7"},{"color":"#ffffff"}]}', true );
				if ( isset( $palette_presets[ $this->selected_palette ] ) ) {
					$default = json_decode( '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}', true );
					$default['palette'][0]['color'] = $palette_presets[ $this->selected_palette ][0]['color'];
					$default['palette'][1]['color'] = $palette_presets[ $this->selected_palette ][1]['color'];
					$default['palette'][2]['color'] = $palette_presets[ $this->selected_palette ][2]['color'];
					$default['palette'][3]['color'] = $palette_presets[ $this->selected_palette ][3]['color'];
					$default['palette'][4]['color'] = $palette_presets[ $this->selected_palette ][4]['color'];
					$default['palette'][5]['color'] = $palette_presets[ $this->selected_palette ][5]['color'];
					$default['palette'][6]['color'] = $palette_presets[ $this->selected_palette ][6]['color'];
					$default['palette'][7]['color'] = $palette_presets[ $this->selected_palette ][7]['color'];
					$default['palette'][8]['color'] = $palette_presets[ $this->selected_palette ][8]['color'];
					update_option( 'kadence_global_palette', json_encode( $default ) );
				}
			} else {
				/**
				 * Execute the customizer import actions.
				 */
				do_action( 'kadence-starter-templates/customizer_import_color_only_execution', $this->selected_import_files );
			}
		}
		if ( $this->override_fonts ) {
			if ( class_exists( 'Kadence\Theme' ) ) {
				if ( $this->selected_font && ! empty( $this->selected_font ) ) {
					switch ( $this->selected_font ) {
						case 'montserrat':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Montserrat';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Source Sans Pro';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'playfair':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Playfair Display';
							$current['google']  = true;
							$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Raleway';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'oswald':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Oswald';
							$current['google']  = true;
							$current['variant'] = array( '200', '300', 'regular', '500', '600', '700' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Open Sans';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'antic':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Antic Didone';
							$current['google']  = true;
							$current['variant'] = array( 'regular' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Raleway';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'gilda':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Gilda Display';
							$current['google']  = true;
							$current['variant'] = array( 'regular' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Raleway';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'cormorant':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Cormorant Garamond';
							$current['google']  = true;
							$current['variant'] = array( '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Proza Libre';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'libre':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Libre Franklin';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Libre Baskerville';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
	
						case 'lora':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Lora';
							$current['google']  = true;
							$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
							set_theme_mod( 'heading_font', $current );
							$h1_font = \Kadence\kadence()->option( 'h1_font' );
							$h1_font['weight'] = 'normal';
							$h1_font['variant'] = 'regualar';
							set_theme_mod( 'h1_font', $h1_font );
							$h2_font = \Kadence\kadence()->option( 'h2_font' );
							$h2_font['weight'] = 'normal';
							$h2_font['variant'] = 'regualar';
							set_theme_mod( 'h2_font', $h2_font );
							$h3_font = \Kadence\kadence()->option( 'h3_font' );
							$h3_font['weight'] = 'normal';
							$h3_font['variant'] = 'regualar';
							set_theme_mod( 'h3_font', $h3_font );
							$h4_font = \Kadence\kadence()->option( 'h4_font' );
							$h4_font['weight'] = 'normal';
							$h4_font['variant'] = 'regualar';
							set_theme_mod( 'h4_font', $h4_font );
							$h5_font = \Kadence\kadence()->option( 'h5_font' );
							$h5_font['weight'] = 'normal';
							$h5_font['variant'] = 'regualar';
							set_theme_mod( 'h5_font', $h5_font );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Merriweather';
							$body['google'] = true;
							$body['weight'] = '300';
							$body['variant'] = '300';
							set_theme_mod( 'base_font', $body );
							break;
	
						case 'proza':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Proza Libre';
							$current['google']  = true;
							$current['variant'] = array( 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Open Sans';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
	
						case 'worksans':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Work Sans';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Work Sans';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
	
						case 'josefin':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Josefin Sans';
							$current['google']  = true;
							$current['variant'] = array( '100', '100italic', '200', '200italic', '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Lato';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
	
						case 'nunito':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Nunito';
							$current['google']  = true;
							$current['variant'] = array( '200', '200italic', '300', '300italic', 'regular', 'italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Roboto';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
						case 'rubik':
							$current = \Kadence\kadence()->option( 'heading_font' );
							$current['family']  = 'Rubik';
							$current['google']  = true;
							$current['variant'] = array( '300', '300italic', 'regular', 'italic', '500', '500italic', '600', '600italic', '700', '700italic', '800', '800italic', '900', '900italic' );
							set_theme_mod( 'heading_font', $current );
							$body = \Kadence\kadence()->option( 'base_font' );
							$body['family'] = 'Karla';
							$body['google'] = true;
							set_theme_mod( 'base_font', $body );
							break;
					}
				} else {
					/**
					 * Execute the customizer import actions.
					 */
					do_action( 'kadence-starter-templates/customizer_import_font_only_execution', $this->selected_import_files );
				}
				foreach ( array( 'h1_font', 'h2_font', 'h3_font', 'h4_font', 'h5_font', 'h6_font', 'h5_font', 'title_above_font' ) as $value ) {
					$font_settings = \Kadence\kadence()->option( $value );
					$font_settings['family'] = 'inherit';
					$font_settings['google'] = false;
					set_theme_mod( $value, $font_settings );
				}
			}
		}

		// If elementor make sure the defaults are off.
		if ( isset( $this->import_files[ $this->selected_index ]['type'] ) && 'elementor' === $this->import_files[ $this->selected_index ]['type'] ) {
			if ( class_exists( 'Elementor\Plugin' ) ) {
				\Elementor\Plugin::instance()->files_manager->clear_cache();
			}
		}

		// Send a JSON response with final report.
		$this->final_response( $new_post );
	}
	/**
	 * Main AJAX callback function for:
	 * 1). prepare import files (uploaded or predefined via filters)
	 * 2). execute 'before content import' actions (before import WP action)
	 * 3). import content
	 * 4). execute 'after content import' actions (before widget import WP action, widget import, customizer import, after import WP action)
	 */
	public function import_demo_data_ajax_callback() {
		// Try to update PHP memory limit (so that it does not run out of it).
		ini_set( 'memory_limit', apply_filters( 'kadence-starter-templates/import_memory_limit', '350M' ) );

		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Is this a new AJAX call to continue the previous import?
		$use_existing_importer_data = $this->use_existing_importer_data();

		if ( ! $use_existing_importer_data ) {
			// Create a date and time string to use for demo and log file names.
			Helpers::set_demo_import_start_time();

			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				// Define log file path.
				$this->log_file_path = Helpers::get_log_path();
			} else {
				$this->log_file_path = '';
			}

			// Get selected file index or set it to 0.
			$this->selected_index   = empty( $_POST['selected'] ) ? '' : sanitize_text_field( $_POST['selected'] );
			$this->selected_palette = empty( $_POST['palette'] ) ? '' : sanitize_text_field( $_POST['palette'] );
			$this->selected_font    = empty( $_POST['font'] ) ? '' : sanitize_text_field( $_POST['font'] );
			/**
			 * 1). Prepare import files.
			 * Predefined import files via filter: kadence-starter-templates/import_files
			 */
			if ( ! empty( $this->import_files[ $this->selected_index ] ) ) { // Use predefined import files from wp filter: kadence-starter-templates/import_files.

				// Download the import files (content, widgets and customizer files).
				$this->selected_import_files = Helpers::download_import_files( $this->import_files[ $this->selected_index ] );

				// Check Errors.
				if ( is_wp_error( $this->selected_import_files ) ) {
					// Write error to log file and send an AJAX response with the error.
					Helpers::log_error_and_send_ajax_response(
						$this->selected_import_files->get_error_message(),
						$this->log_file_path,
						esc_html__( 'Downloaded files', 'kadence-starter-templates' )
					);
				}
				if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
					// Add this message to log file.
					$log_added = Helpers::append_to_file(
						sprintf(
							__( 'The import files for: %s were successfully downloaded!', 'kadence-starter-templates' ),
							$this->import_files[ $this->selected_index ]['import_file_name']
						) . Helpers::import_file_info( $this->selected_import_files ),
						$this->log_file_path,
						esc_html__( 'Downloaded files' , 'kadence-starter-templates' )
					);
				}
			} else {
				// Send JSON Error response to the AJAX call.
				wp_send_json( esc_html__( 'No import files specified!', 'kadence-starter-templates' ) );
			}
		}
		// If elementor make sure the defaults are off.
		if ( isset( $this->import_files[ $this->selected_index ]['type'] ) && 'elementor' === $this->import_files[ $this->selected_index ]['type'] ) {
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
		}
		// Save the initial import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );

		if ( ! $this->before_import_executed ) {
			$this->before_import_executed = true;

			/**
			 * Save Current Theme mods for a potential undo.
			 */
			update_option( '_kadence_starter_templates_old_customizer', get_option( 'theme_mods_' . get_option( 'stylesheet' ) ) );
			/**
			 * 2). Execute the actions hooked to the 'kadence-starter-templates/before_content_import_execution' action:
			 *
			 * Default actions:
			 * 1 - Before content import WP action (with priority 10).
			 */
			/**
			 * Clean up default contents.
			 */
			wp_delete_post( 1, true ); // Hello World.
			wp_delete_post( 2, true ); // Sample Page.
			wp_delete_comment( 1, true ); // WordPress comment.
			// Move All active widgets into inactive.
			$sidebars = wp_get_sidebars_widgets();
			if ( is_array( $sidebars ) ) {
				foreach ( $sidebars as $sidebar_id => $sidebar_widgets ) {
					if ( 'wp_inactive_widgets' === $sidebar_id ) {
						continue;
					}
					if ( is_array( $sidebar_widgets ) && ! empty( $sidebar_widgets ) ) {
						foreach ( $sidebar_widgets as $i => $single_widget ) {
							$sidebars['wp_inactive_widgets'][] = $single_widget;
							unset( $sidebars[ $sidebar_id ][ $i ] );
						}
					}
				}
			}
			wp_set_sidebars_widgets( $sidebars );
			// Reset to default settings values.
			delete_option( 'theme_mods_' . get_option( 'stylesheet' ) );
			// Reset Global Palette
			update_option( 'kadence_global_palette', '{"palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"second-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"third-palette":[{"color":"#3182CE","slug":"palette1","name":"Palette Color 1"},{"color":"#2B6CB0","slug":"palette2","name":"Palette Color 2"},{"color":"#1A202C","slug":"palette3","name":"Palette Color 3"},{"color":"#2D3748","slug":"palette4","name":"Palette Color 4"},{"color":"#4A5568","slug":"palette5","name":"Palette Color 5"},{"color":"#718096","slug":"palette6","name":"Palette Color 6"},{"color":"#EDF2F7","slug":"palette7","name":"Palette Color 7"},{"color":"#F7FAFC","slug":"palette8","name":"Palette Color 8"},{"color":"#ffffff","slug":"palette9","name":"Palette Color 9"}],"active":"palette"}' );
			do_action( 'kadence-starter-templates/before_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette, $this->selected_font );
		}

		/**
		 * 3). Import content (if the content XML file is set for this import).
		 * Returns any errors greater then the "warning" logger level, that will be displayed on front page.
		 */
		if ( ! empty( $this->selected_import_files['content'] ) ) {
			$this->append_to_frontend_error_messages( $this->importer->import_content( $this->selected_import_files['content'] ) );
		}

		/**
		 * 4). Execute the actions hooked to the 'kadence-starter-templates/after_content_import_execution' action:
		 *
		 * Default actions:
		 * 1 - Before widgets import setup (with priority 10).
		 * 2 - Import widgets (with priority 20).
		 * 3 - Import Redux data (with priority 30).
		 */
		do_action( 'kadence-starter-templates/after_content_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette, $this->selected_font );
		// Save the import data as a transient, so other import parts (in new AJAX calls) can use that data.
		Helpers::set_import_data_transient( $this->get_current_importer_data() );
		// Save Import data for use if we need to reset it.
		update_option( '_kadence_starter_templates_last_import_data', $this->selected_import_files );
		// Request the customizer import AJAX call.
		if ( ! empty( $this->selected_import_files['customizer'] ) ) {
			wp_send_json( array( 'status' => 'customizerAJAX' ) );
		}

		// Request the after all import AJAX call.
		if ( false !== has_action( 'kadence-starter-templates/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}

	/**
	 * After import run elementor stuff.
	 */
	public function kadence_elementor_after_import( $selected_import, $selected_palette, $selected_font ) {
		// If elementor make sure we set things up and clear cache.
		if ( isset( $selected_import['type'] ) && 'elementor' === $selected_import['type'] ) {
			if ( class_exists( 'Elementor\Plugin' ) ) {
				if ( class_exists( 'Kadence\Theme' ) ) {
					$component = \Kadence\Theme::instance()->components['elementor'];
					if ( $component ) {
						$component->elementor_add_theme_colors();
					}
				}
				if ( isset( $selected_import['width'] ) && 'large' === $selected_import['width'] ) {
					$container_width = array(
						'unit' => 'px',
						'size' => 1242,
						'sizes' => array(),
					);
					$container_width_tablet = array(
						'unit' => 'px',
						'size' => 700,
						'sizes' => array(),
					);
					if ( method_exists( \Elementor\Plugin::$instance->kits_manager, 'update_kit_settings_based_on_option' ) ) {
						\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'container_width', $container_width );
						\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'container_width_tablet', $container_width_tablet );
					}
				} else {
					$container_width = array(
						'unit' => 'px',
						'size' => 1140,
						'sizes' => array(),
					);
					$container_width_tablet = array(
						'unit' => 'px',
						'size' => 700,
						'sizes' => array(),
					);
					if ( method_exists( \Elementor\Plugin::$instance->kits_manager, 'update_kit_settings_based_on_option' ) ) {
						\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'container_width', $container_width );
						\Elementor\Plugin::$instance->kits_manager->update_kit_settings_based_on_option( 'container_width_tablet', $container_width_tablet );
					}
				}
				\Elementor\Plugin::instance()->files_manager->clear_cache();
			}
		}
	}


	/**
	 * AJAX callback for importing the customizer data.
	 * This request has the wp_customize set to 'on', so that the customizer hooks can be called
	 * (they can only be called with the $wp_customize instance). But if the $wp_customize is defined,
	 * then the widgets do not import correctly, that's why the customizer import has its own AJAX call.
	 */
	public function import_customizer_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Get existing import data.
		if ( $this->use_existing_importer_data() ) {
			/**
			 * Execute the customizer import actions.
			 *
			 * Default actions:
			 * 1 - Customizer import (with priority 10).
			 */
			do_action( 'kadence-starter-templates/customizer_import_execution', $this->selected_import_files );
		}

		// Request the after all import AJAX call.
		if ( false !== has_action( 'kadence-starter-templates/after_all_import_execution' ) ) {
			wp_send_json( array( 'status' => 'afterAllImportAJAX' ) );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}


	/**
	 * AJAX callback for the after all import action.
	 */
	public function after_all_import_data_ajax_callback() {
		// Verify if the AJAX call is valid (checks nonce and current_user_can).
		Helpers::verify_ajax_call();

		// Get existing import data.
		if ( $this->use_existing_importer_data() ) {
			/**
			 * Execute the after all import actions.
			 *
			 * Default actions:
			 * 1 - after_import action (with priority 10).
			 */
			do_action( 'kadence-starter-templates/after_all_import_execution', $this->selected_import_files, $this->import_files, $this->selected_index, $this->selected_palette, $this->selected_font );
		}

		// Send a JSON response with final report.
		$this->final_response();
	}


	/**
	 * Send a JSON response with final report.
	 */
	private function final_response( $extra = '' ) {
		// Delete importer data transient for current import.
		delete_transient( 'kadence_importer_data' );

		// Display final messages (success or error messages).
		if ( empty( $this->frontend_error_messages ) && ! empty( $extra ) ) {
			$response['message'] = '';

			$response['message'] .= sprintf(
				__( '%1$sFinished! View your page%2$s', 'kadence-starter-templates' ),
				'<div class="finshed-notice-success"><p><a href="' . esc_url( get_permalink( $extra ) ) . '" class="button-primary button kadence-starter-templates-finish-button">',
				'</a></p></div>'
			);
		} elseif ( empty( $this->frontend_error_messages ) ) {
			$response['message'] = '';

			$response['message'] .= sprintf(
				__( '%1$sFinished! View your site%2$s', 'kadence-starter-templates' ),
				'<div class="finshed-notice-success"><p><a href="' . esc_url( home_url( '/' ) ) . '" class="button-primary button kadence-starter-templates-finish-button">',
				'</a></p></div>'
			);
		} else {
			$response['message'] = $this->frontend_error_messages_display() . '<br>';
			if ( apply_filters( 'kadence_starter_templates_save_log_files', false ) ) {
				$response['message'] .= sprintf(
					__( '%1$sThe demo import has finished, but there were some import errors.%2$sMore details about the errors can be found in this %3$s%5$slog file%6$s%4$s%7$s', 'kadence-starter-templates' ),
					'<div class="notice  notice-warning"><p>',
					'<br>',
					'<strong>',
					'</strong>',
					'<a href="' . Helpers::get_log_url( $this->log_file_path ) .'" target="_blank">',
					'</a>',
					'</p></div>'
				);
			} else {
				$response['message'] .= sprintf(
					__( '%1$sThe demo import has finished, but there were some import errors.%2$sPlease check your php error logs if site is incomplete.%3$s', 'kadence-starter-templates' ),
					'<div class="notice  notice-warning"><p>',
					'<br>',
					'</p></div>'
				);
			}
		}

		wp_send_json( $response );
	}


	/**
	 * Get content importer data, so we can continue the import with this new AJAX request.
	 *
	 * @return boolean
	 */
	private function use_existing_importer_data() {
		if ( $data = get_transient( 'kadence_importer_data' ) ) {
			$this->frontend_error_messages = empty( $data['frontend_error_messages'] ) ? array() : $data['frontend_error_messages'];
			$this->log_file_path           = empty( $data['log_file_path'] ) ? '' : $data['log_file_path'];
			$this->selected_index          = empty( $data['selected_index'] ) ? 0 : $data['selected_index'];
			$this->selected_palette        = empty( $data['selected_palette'] ) ? '' : $data['selected_palette'];
			$this->selected_font           = empty( $data['selected_font'] ) ? '' : $data['selected_font'];
			$this->selected_import_files   = empty( $data['selected_import_files'] ) ? array() : $data['selected_import_files'];
			$this->import_files            = empty( $data['import_files'] ) ? array() : $data['import_files'];
			$this->before_import_executed  = empty( $data['before_import_executed'] ) ? false : $data['before_import_executed'];
			$this->importer->set_importer_data( $data );

			return true;
		}
		return false;
	}


	/**
	 * Get the current state of selected data.
	 *
	 * @return array
	 */
	public function get_current_importer_data() {
		return array(
			'frontend_error_messages' => $this->frontend_error_messages,
			'log_file_path'           => $this->log_file_path,
			'selected_index'          => $this->selected_index,
			'selected_palette'        => $this->selected_palette,
			'selected_font'           => $this->selected_font,
			'selected_import_files'   => $this->selected_import_files,
			'import_files'            => $this->import_files,
			'before_import_executed'  => $this->before_import_executed,
		);
	}


	/**
	 * Getter function to retrieve the private log_file_path value.
	 *
	 * @return string The log_file_path value.
	 */
	public function get_log_file_path() {
		return $this->log_file_path;
	}


	/**
	 * Setter function to append additional value to the private frontend_error_messages value.
	 *
	 * @param string $additional_value The additional value that will be appended to the existing frontend_error_messages.
	 */
	public function append_to_frontend_error_messages( $text ) {
		$lines = array();

		if ( ! empty( $text ) ) {
			$text = str_replace( '<br>', PHP_EOL, $text );
			$lines = explode( PHP_EOL, $text );
		}

		foreach ( $lines as $line ) {
			if ( ! empty( $line ) && ! in_array( $line , $this->frontend_error_messages ) ) {
				$this->frontend_error_messages[] = $line;
			}
		}
	}


	/**
	 * Display the frontend error messages.
	 *
	 * @return string Text with HTML markup.
	 */
	public function frontend_error_messages_display() {
		$output = '';

		if ( ! empty( $this->frontend_error_messages ) ) {
			foreach ( $this->frontend_error_messages as $line ) {
				$output .= esc_html( $line );
				$output .= '<br>';
			}
		}

		return $output;
	}


	/**
	 * Load the plugin textdomain, so that translations can be made.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'kadence-starter-templates', false, plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/languages' );
	}


	/**
	 * Get data from filters, after the theme has loaded and instantiate the importer.
	 */
	public function setup_plugin_with_filter_data() {
		if ( ! ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) ) {
			return;
		}
		// Get info of import data files and filter it.
		$this->import_files = apply_filters( 'kadence-starter-templates/import_files', array() );
		//$this->import_files = Helpers::validate_import_file_info( apply_filters( 'kadence-starter-templates/import_files', array() ) );
		/**
		 * Register all default actions (before content import, widget, customizer import and other actions)
		 * to the 'before_content_import_execution' and the 'kadence-starter-templates/after_content_import_execution' action hook.
		 */
		$import_actions = new ImportActions();
		$import_actions->register_hooks();

		// Importer options array.
		$importer_options = apply_filters( 'kadence-starter-templates/importer_options', array(
			'fetch_attachments' => true,
		) );

		// Logger options for the logger used in the importer.
		$logger_options = apply_filters( 'kadence-starter-templates/logger_options', array(
			'logger_min_level' => 'warning',
		) );

		// Configure logger instance and set it to the importer.
		$logger            = new Logger();
		$logger->min_level = $logger_options['logger_min_level'];

		// Create importer instance with proper parameters.
		$this->importer = new Importer( $importer_options, $logger );
	}
}
Starter_Templates::get_instance();
