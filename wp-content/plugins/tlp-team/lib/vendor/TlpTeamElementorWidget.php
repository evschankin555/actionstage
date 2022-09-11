<?php

class TlpTeamElementorWidget extends \Elementor\Widget_Base
{

    public function get_name() {
        return 'tlp-team';
    }

    public function get_title() {
        return __('Tlp Team', 'tlp-team');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'setting_section',
            [
                'label' => __('Settings', 'tlp-team'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'layout',
                'label'   => __('Layout', 'tlp-team'),
                'options' => TLPTeam()->scLayouts()
            )
        );
        $this->add_control(
            'col',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'col',
                'label'   => __('Column / Number to Display at slider', 'tlp-team'),
                'options' => TLPTeam()->scColumns()
            )
        );
        $this->add_control(
            'orderby',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'orderby',
                'label'   => __('Order By', 'tlp-team'),
                'options' => TLPTeam()->scOrderBy()
            )
        );
        $this->add_control(
            'order',
            array(
                'type'    => \Elementor\Controls_Manager::SELECT2,
                'id'      => 'order',
                'label'   => __('Order', 'tlp-team'),
                'options' => TLPTeam()->scOrder()
            )
        );
        $this->add_control(
            'id',
            array(
                'type'        => \Elementor\Controls_Manager::TEXT,
                'id'          => 'id',
                'label'       => __('Include Member Only', 'tlp-team'),
                'description' => __("List of member IDs (comma-separated values, for example: 1,2,3)", "tlp-team")
            )
        );
        $this->add_control(
            'member',
            array(
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'id'          => 'member',
                'label'       => __('Total Member', 'tlp-team'),
                'step'        => 1,
                'default'     => '',
                'description' => __("Leave it blank to display all. (Only number is allowed)", "tlp-team")
            )
        );
        $this->add_control(
            'class',
            array(
                'label' => __('Wrapper Class', 'tlp-team'),
                'type'  => \Elementor\Controls_Manager::TEXT,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'tlp-team'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'name-color',
            array(
                'label'     => __('Name color', 'tlp-team'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'scheme'    => array(
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'designation-color',
            array(
                'label'   => __('Designation color', 'tlp-team'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'scheme'    => array(
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'sd-color',
            array(
                'label'   => __('Short description color', 'tlp-team'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'scheme'    => array(
                    'type'  => \Elementor\Scheme_Color::get_type(),
                    'value' => \Elementor\Scheme_Color::COLOR_1,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .title' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $shortcode = '[tlpteam';
        if (isset($settings['layout']) && !empty($settings['layout'])) {
            $shortcode .= ' layout="' . $settings['layout'] . '"';
        }
        if (isset($settings['col']) && !empty($settings['col'])) {
            $shortcode .= ' col="' . $settings['col'] . '"';
        }
        if (isset($settings['orderby']) && !empty($settings['orderby'])) {
            $shortcode .= ' orderby="' . $settings['orderby'] . '"';
        }
        if (isset($settings['order']) && !empty($settings['order'])) {
            $shortcode .= ' order="' . $settings['order'] . '"';
        }
        if (isset($settings['member']) && !empty($settings['member'])) {
            $shortcode .= ' member="' . $settings['member'] . '"';
        }
        if (isset($settings['id']) && !empty($settings['id'])) {
            $shortcode .= ' id="' . $settings['id'] . '"';
        }
        if (isset($settings['name-color']) && !empty($settings['name-color'])) {
            $shortcode .= ' name-color="' . $settings['name-color'] . '"';
        }
        if (isset($settings['designation-color']) && !empty($settings['designation-color'])) {
            $shortcode .= ' designation-color="' . $settings['designation-color'] . '"';
        }
        if (isset($settings['sd-color']) && !empty($settings['sd-color'])) {
            $shortcode .= ' sd-color="' . $settings['sd-color'] . '"';
        }
        if (isset($settings['class']) && !empty($settings['class'])) {
            $shortcode .= ' class="' . $settings['class'] . '"';
        }
        $shortcode .= ']';

        echo do_shortcode($shortcode);
    }
}