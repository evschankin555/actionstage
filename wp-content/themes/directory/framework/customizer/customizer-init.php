<?php
/*
 * This is the file that initializes all the customizer parts.
 */

if (!function_exists('directory_customizer')):
    function directory_customizer()
    {
        $options = array();
        $sections = array();
        $panels = array();
        $options['sections'] = $sections;


        $section = 'directory-general';

        $sections[] = array(
            'id' => $section,
            'title' => __('General Settings', 'directory'),
            'priority' => '6',
            'description' => __('General Settings.', 'directory')
        );

        $options['logo-upload'] = array(
            'id' => 'logo-upload',
            'label' => __('Upload your logo', 'directory'),
            'section' => $section,
            'type' => 'upload',
            'default' => '',
        );
        $options['show-map'] = array(
            'id' => 'show-map',
            'label' => __('Show map in the front page?', 'directory'),
            'section' => $section,
            'type' => 'radio',
            'choices' => array('0' => __('No', 'directory'),
                               '1' => __('Yes', 'directory')),
            'default' => '0'
        );

        $options['google-api-key'] = array(
            'id' => 'google-api-key',
            'label' => __('If the map produces an error please add your Google API KEY here. View the https://developers.google.com/maps/documentation/javascript/get-api-key#key for more details.', 'directory'),
            'section' => $section,
            'type' => 'text',
            'default' => ''
        );

        $options['show-loader'] = array(
            'id' => 'show-loader',
            'label' => __('Show Loader?', 'directory'),
            'section' => $section,
            'type' => 'radio',
            'choices' => array('0' => __('No', 'directory'),
                '1' => __('Yes', 'directory')),
            'default' => '1'
        );

        $options['sticky-menu'] = array(
            'id' => 'sticky-menu',
            'label' => __('Make the navigation menu sticky?', 'directory'),
            'section' => $section,
            'type' => 'radio',
            'choices' => array('0' => __('No', 'directory'),
                '1' => __('Yes', 'directory')),
            'default' => '0'
        );
        // Adds the sections to the $options array
        $options['sections'] = $sections;
        // Adds the panels to the $options array
        $options['panels'] = $panels;
        $customizer_library = Customizer_Library::Instance();
        $customizer_library->add_options($options);
        // To delete custom mods use: customizer_library_remove_theme_mods();
    }
endif;
add_action('init', 'directory_customizer');
