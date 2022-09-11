<?php

class Tabs_Responsive_Addon_Widget extends \Elementor\Widget_Base {
	
	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
		wp_register_style( "wpsm_tab_responsive_bootstrap-front",wpshopmart_tabs_r_directory_url."tabs-responsive-elementor-addon/assets/css/bootstrap-front.css", array(), false, "all" );
		wp_register_style( "wpsm_tab_responsive_animate",wpshopmart_tabs_r_directory_url."tabs-responsive-elementor-addon/assets/css/animate.css", array(), false, "all" );
		wp_enqueue_script('jquery');
		wp_register_script('wpsm_tab_responsive_bootstrap_js',wpshopmart_tabs_r_directory_url ."tabs-responsive-elementor-addon/assets/js/bootstrap.js",array ('jquery'), false, false);		
		
	}
	
	public function get_style_depends() {
		return array( 'wpsm_tab_responsive_bootstrap-front','wpsm_tab_responsive_animate' );
	}
	
	public function get_script_depends() {
		return array('wpsm_tab_responsive_bootstrap_js');
	}
	
	public function get_name() {
		return 'Tabs Responsive';
	}

	
	public function get_title() {
		return __( 'Tabs Responsive', 'wpshopmart_tabs_r_text_domain' );
	}

	
	public function get_icon() {
		return 'eicon-table';
	}

	
	public function get_categories() {
		return [ 'wpshopmart' ];
	}

	
	protected function _register_controls() {		
		
		require_once( wpshopmart_tabs_r_directory_path .'tabs-responsive-elementor-addon/includes/tabs-responsive-addon-controls.php' );
	}

	
	protected function render() {

		$settings = $this->get_settings_for_display();		
		require_once( __DIR__ . '/render-template/design-'.$settings['tabs_select_design'].'.php' );		

	}
	
	
	protected function _content_template()
	{				
		require_once( wpshopmart_tabs_r_directory_path .'tabs-responsive-elementor-addon/includes/tabs-responsive-addon-content.php' );
		
	}
}

?>
