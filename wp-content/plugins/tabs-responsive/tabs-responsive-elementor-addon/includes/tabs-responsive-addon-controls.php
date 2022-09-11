<?php
$this->start_controls_section(
	'tabs_design_section',
	[
		'label' => __( 'Design', 'tabs-responsive' ),
		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	]
);

$this->add_control(
	'tabs_select_design',
	[
		'label' => __( 'Select Design', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SELECT,
		'default' => '1',
		'options' => [
			'1'  => __( 'Design-1', 'wpshopmart_tabs_r_text_domain' ),			
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'tabs_content_section',
	[
		'label' => __( 'Content', 'tabs-responsive' ),
		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	]
);

$repeater = new \Elementor\Repeater();

$repeater->add_control(
	'tabs_responsive_title',
	[
		'label' => __( 'Tab Title', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::TEXT,
		'default' => __( 'Sample Title', 'wpshopmart_tabs_r_text_domain' ),
		'placeholder' => __( 'Type your title here', 'wpshopmart_tabs_r_text_domain' ),
	]
);

$repeater->add_control(
	'tabs_responsive_description',
	[
		'label' => __( 'Tab Description', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::WYSIWYG,
		'default' => __( 'Sample Description', 'wpshopmart_tabs_r_text_domain' ),
		'placeholder' => __( 'Type your description here', 'wpshopmart_tabs_r_text_domain' ),
	]
);

$repeater->add_control(
	'tabs_responsive_icon',
	[
		'label' => __( 'Tab Icon', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::ICONS,
		'default' => [
			'value' => 'fa fa-laptop',
			'library' => 'solid',
		],
	]
);

$repeater->add_control(
	'tabs_responsive_show_above_icon',
	[
		'label' => __( 'Display Above Icon', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SWITCHER,
		'label_on' => __( 'Yes', 'tabs-responsive' ),
		'label_off' => __( 'No', 'tabs-responsive' ),
		'return_value' => 'yes',
		'default' => 'yes',
	]
);

$this->add_control(
	'tabs_list',
	[
		'label' => __( 'Add Tabs', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::REPEATER,
		'fields' => $repeater->get_controls(),
		'default' => [
			[
				'tabs_responsive_title' => __( 'Sample Title', 'wpshopmart_tabs_r_text_domain' ),
				'tabs_responsive_description' => __( 'Sample Description', 'wpshopmart_tabs_r_text_domain' ),
				'tabs_responsive_icon' => __('fa fa-laptop','wpshopmart_tabs_r_text_domain'),
				'tabs_responsive_show_above_icon'=>__('yes','wpshopmart_tabs_r_text_domain'),
			],	
			[
				'tabs_responsive_title' => __( 'Sample Title', 'wpshopmart_tabs_r_text_domain' ),
				'tabs_responsive_description' => __( 'Sample Description', 'wpshopmart_tabs_r_text_domain' ),
				'tabs_responsive_icon' => __('fa fa-laptop','wpshopmart_tabs_r_text_domain'),
				'tabs_responsive_show_above_icon'=>__('yes','wpshopmart_tabs_r_text_domain'),
			],
			[
				'tabs_responsive_title' => __( 'Sample Title', 'wpshopmart_tabs_r_text_domain' ),
				'tabs_responsive_description' => __( 'Sample Description', 'wpshopmart_tabs_r_text_domain' ),
				'tabs_responsive_icon' => __('fa fa-laptop','wpshopmart_tabs_r_text_domain'),
				'tabs_responsive_show_above_icon'=>__('yes','wpshopmart_tabs_r_text_domain'),
			],
		],
		'title_field' => '{{{ tabs_responsive_title }}}',
	]
);


$this->end_controls_section();

$this->start_controls_section(
	'tabs_code_section',
	[
		'label' => __( 'Code', 'tabs-responsive' ),
		'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
	]
);

$this->add_control(
	'tabs_custom_css',
	[
		'label' => __( 'Custom Css', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::CODE,
		'language' => 'css',
		'description' =>'Enter Css without using style tag',
		'rows' => 20,
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'tabs_style_section',
	[
		'label' => __( 'Style', 'tabs-responsive' ),
		'tab' => \Elementor\Controls_Manager::TAB_STYLE,
	]
);

$this->add_control(
	'tabs_font_family',
	[
		'label' => __( 'Font Family', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::FONT,
		'default' => "Open Sans",
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .tab-content' => 'font-family: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a' => 'font-family: {{VALUE}}',
		],
	]
);

$this->add_control(
	'tabs_animation',
	[
		'label' => __( 'Tabs Description Animation', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SELECT,
		'default' => 'fadeIn',
		'options' => [
			'fadeIn'  => __( 'Fade Animation', 'wpshopmart_tabs_r_text_domain' ),
			'fadeInUp'  => __( 'Fade In Up Animation', 'wpshopmart_tabs_r_text_domain' ),
			'fadeInDown'  => __( 'Fade In Down Animation', 'wpshopmart_tabs_r_text_domain' ),
			'fadeInLeft'  => __( 'Fade In Left Animation', 'wpshopmart_tabs_r_text_domain' ),
			'fadeInRight'  => __( 'Fade In Right Animation', 'wpshopmart_tabs_r_text_domain' ),
			'6'  => __( 'No Animation', 'wpshopmart_tabs_r_text_domain' ),
		],
	]
);

$this->add_control(
	'tabs_divider_4',
	[
		'type' => \Elementor\Controls_Manager::DIVIDER,
	]
);

// $this->add_control(
	// 'display_tabs_sec_title',
	// [
		// 'label' => __( 'Display Tabs Section Title', 'wpshopmart_tabs_r_text_domain' ),
		// 'type' => \Elementor\Controls_Manager::SWITCHER,
		// 'label_on' => __( 'Yes', 'tabs-responsive' ),
		// 'label_off' => __( 'No', 'tabs-responsive' ),
		// 'return_value' => 'yes',
		// 'default' => 'yes',
	// ]
// );

$this->add_control(
	'display_tabs_border',
	[
		'label' => __( 'Display Tabs Border', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SWITCHER,
		'label_on' => __( 'Yes', 'tabs-responsive' ),
		'label_off' => __( 'No', 'tabs-responsive' ),
		'return_value' => 'yes',
		'default' => 'yes',
	]
);

$this->add_control(
	'tabs_position',
	[
		'label' => __( 'Tabs Position', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SWITCHER,
		'label_on' => __( 'Left', 'tabs-responsive' ),
		'label_off' => __( 'Right', 'tabs-responsive' ),
		'return_value' => 'yes',
		'default' => 'yes',
	]
);

$this->add_control(
	'tabs_margin_bw_two',
	[
		'label' => __( 'Margin Between Two Tabs', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SWITCHER,
		'label_on' => __( 'Yes', 'tabs-responsive' ),
		'label_off' => __( 'No', 'tabs-responsive' ),
		'return_value' => 'yes',
		'default' => 'no',
	]
);

$this->add_control(
	'margin_bw_tabs_content',
	[
		'label' => __( 'Margin Between Tabs And Content', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SWITCHER,
		'label_on' => __( 'Yes', 'tabs-responsive' ),
		'label_off' => __( 'No', 'tabs-responsive' ),
		'return_value' => 'yes',
		'default' => 'no',
	]
);

$this->add_control(
	'tabs_divider_3',
	[
		'type' => \Elementor\Controls_Manager::DIVIDER,
	]
);

$this->add_control(
	'dis_op_title_icon',
	[
		'label' => __( 'Display Option For Title and icon', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::CHOOSE,
		'options' => [
			'1' => [
				'title' => __( 'Show Tabs Title + Icon (both)', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'2' => [
				'title' => __( 'Show Only Tabs Title', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'3' => [
				'title' => __( 'Show Only Icon', 'wpshopmart_tabs_r_text_domain' ),
				
			],
		],
		'default' => '1',
		// 'toggle' => true,
	]
);

$this->add_control(
	'tabs_ic_pt_align',
	[
		'label' => __( 'Tabs Icon Position Alignment', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::CHOOSE,
		'options' => [
			'1' => [
				'title' => __( 'Before Tab Title', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'2' => [
				'title' => __( 'After Tab Title', 'wpshopmart_tabs_r_text_domain' ),
				
			],			
		],
		'default' => '1',
		// 'toggle' => true,
	]
);

$this->add_control(
	'tabs_style',
	[
		'label' => __( 'Tabs Styles', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::CHOOSE,
		'options' => [
			'1' => [
				'title' => __( 'Default', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'2' => [
				'title' => __( 'Soft', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'3' => [
				'title' => __( 'Noise', 'wpshopmart_tabs_r_text_domain' ),
				
			],
		],
		'default' => '1',
		// 'toggle' => true,
	]
);

$this->add_control(
	'tabs_alignment',
	[
		'label' => __( 'Tabs Alignment', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::CHOOSE,
		'options' => [
			'1' => [
				'title' => __( 'Horizontal', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'2' => [
				'title' => __( 'Vertical', 'wpshopmart_tabs_r_text_domain' ),
				
			],			
		],
		'default' => '1',
		// 'toggle' => true,
	]
);

$this->add_control(
	'tabs_mobile_dis_setting',
	[
		'label' => __( 'Tabs Mobile display Settings', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::CHOOSE,
		'options' => [
			'1' => [
				'title' => __( 'Display Both Title + Icon', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'2' => [
				'title' => __( ' Display only Icon', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'3' => [
				'title' => __( 'Display Only Title', 'wpshopmart_tabs_r_text_domain' ),
				
			],
		],
		'default' => '1',
		// 'toggle' => true,
	]
);

$this->add_control(
	'title_dis_mode_mobile',
	[
		'label' => __( 'Title Display Mode In Mobile', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::CHOOSE,
		'options' => [
			'1' => [
				'title' => __( 'Display As a tabs', 'wpshopmart_tabs_r_text_domain' ),
				
			],
			'2' => [
				'title' => __( 'Display As A vertical Button', 'wpshopmart_tabs_r_text_domain' ),
				
			],			
		],
		'default' => '2',
		// 'toggle' => true,
	]
);

$this->add_control(
	'tabs_divider_2',
	[
		'type' => \Elementor\Controls_Manager::DIVIDER,
	]
);

$this->add_control(
	'title_bg_color',
	[
		'label' => __( 'Tabs Title Background Color', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#e8e8e8',
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > .tabs-anchor-class' => 'background-color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a:hover' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a:focus' => 'background-color: {{VALUE}}',	
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > .tabs-anchor-class:hover' => 'border-color: {{VALUE}}',	
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > .tabs-anchor-class:focus' => 'border-color: {{VALUE}}',	
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .tab-content-border' => 'border-color: {{VALUE}}',
			
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > .tabs-anchor-class' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > .tabs-anchor-class:hover' => 'border-color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > .tabs-anchor-class:focus' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'tabs_title_icon_ft_color',
	[
		'label' => __( 'Tabs Title/Icon Font Color', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#000000',
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a' => 'color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a:hover' => 'color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a:focus' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'select_title_bg_color',
	[
		'label' => __( 'Selected Tabs Title Background Color', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#ffffff',
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > a' => 'background-color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > a:hover' => 'background-color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > a:focus' => 'background-color: {{VALUE}}',			
			
		],
	]
);

$this->add_control(
	'select_title_icon_ft_color',
	[
		'label' => __( 'Selected Tabs Title/Icon Font Color', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#000000',
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > a' => 'color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > a:hover' => 'color: {{VALUE}}',
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li.active > a:focus' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'tabs_descrip_bg_color',
	[
		'label' => __( 'Tabs Description Background Color', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#ffffff',
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .tab-content' => 'background-color: {{VALUE}}',
			
		],
	]
);

$this->add_control(
	'tabs_descrip_ft_color',
	[
		'label' => __( 'Tabs Description Font Color', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'scheme' => [
			'type' => \Elementor\Scheme_Color::get_type(),
			'value' => \Elementor\Scheme_Color::COLOR_1,
		],
		'default' => '#000000',
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .tab-content' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'tabs_divider_1',
	[
		'type' => \Elementor\Controls_Manager::DIVIDER,
	]
);	

$this->add_control(
	'title_icon_font_size',
	[
		'label' => __( 'Tabs Title/Icon Font Size', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SLIDER,
		'size_units' => [ 'px'],
		'range' => [
			'px' => [
				'min' => 8,
				'max' => 22,
				'step' => 1,
			],
			
		],
		'default' => [
			'unit' => 'px',
			'size' => 14,
		],
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .wpsm_nav-tabs > li > a' => 'font-size: {{SIZE}}{{UNIT}};',			
			
		],
	]
);

$this->add_control(
	'tabs_description_font_size',
	[
		'label' => __( 'Tabs Description Font Size', 'wpshopmart_tabs_r_text_domain' ),
		'type' => \Elementor\Controls_Manager::SLIDER,
		'size_units' => [ 'px'],
		'range' => [
			'px' => [
				'min' => 5,
				'max' => 30,
				'step' => 1,
			],
			
		],
		'default' => [
			'unit' => 'px',
			'size' => 16,
		],
		'selectors' => [
			'{{WRAPPER}} #tab_container_'.get_the_ID().' .tab-content' => 'font-size: {{SIZE}}{{UNIT}};',			
			
		],
	]
);

$this->end_controls_section();
?>