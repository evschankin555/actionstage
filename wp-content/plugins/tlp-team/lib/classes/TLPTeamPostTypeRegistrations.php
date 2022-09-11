<?php

if (!class_exists('TLPTeamPostTypeRegistrations')):

    class TLPTeamPostTypeRegistrations
    {
        public $version;

        public function __construct() {

            $this->version = defined('WP_DEBUG') && WP_DEBUG ? time() : TLP_TEAM_VERSION;
            // Add the team post type and taxonomies
            add_action('init', array($this, 'register'));
        }

        /**
         * Initiate registrations of post type and taxonomies.
         *
         * @uses TEAM_Post_Type_Registrations::register_post_type()
         */
        public function register() {
            $this->register_post_type();
            $this->register_scPT();
            $this->registerScriptStyle();
        }

        /**
         * Register the custom post type.
         *
         * @link http://codex.wordpress.org/Function_Reference/register_post_type
         */
        protected function register_post_type() {
            $team_labels = array(
                'name'               => _x('Team', "tlp-team"),
                'singular_name'      => _x('Member', "tlp-team"),
                'menu_name'          => __('Team', "tlp-team"),
                'name_admin_bar'     => __('Member', "tlp-team"),
                'parent_item_colon'  => __('Parent Member:', "tlp-team"),
                'all_items'          => __('All Members', "tlp-team"),
                'add_new_item'       => __('Add New Member', "tlp-team"),
                'add_new'            => __('Add Member', "tlp-team"),
                'new_item'           => __('New Member', "tlp-team"),
                'edit_item'          => __('Edit Member', "tlp-team"),
                'update_item'        => __('Update Member', "tlp-team"),
                'view_item'          => __('View Member', "tlp-team"),
                'search_items'       => __('Search Member', "tlp-team"),
                'not_found'          => __('Not found', "tlp-team"),
                'not_found_in_trash' => __('Not found in Trash', "tlp-team"),
            );
            $team_args = array(
                'label'               => __('Team', "tlp-team"),
                'description'         => __('Member', "tlp-team"),
                'labels'              => $team_labels,
                'supports'            => array('title', 'editor', 'thumbnail', 'page-attributes'),
                'taxonomies'          => array(),
                'hierarchical'        => false,
                'public'              => true,
                'rewrite'             => array('slug' => TLPTeam()->post_type_slug),
                'show_ui'             => true,
                'show_in_menu'        => true,
                'menu_position'       => 20,
                'menu_icon'           => TLPTeam()->assetsUrl . 'images/team.png',
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'page',
            );
            register_post_type(TLPTeam()->post_type, $team_args);
            flush_rewrite_rules();
        }

        protected function register_scPT() {

            $sc_args = array(
                'label'               => __('ShortCode', 'tlp-team'),
                'description'         => __('TLP Team ShortCode generator', 'tlp-team'),
                'labels'              => array(
                    'all_items'          => __('ShortCodes', 'tlp-team'),
                    'menu_name'          => __('ShortCode', 'tlp-team'),
                    'singular_name'      => __('ShortCode', 'tlp-team'),
                    'edit_item'          => __('Edit ShortCode', 'tlp-team'),
                    'new_item'           => __('New ShortCode', 'tlp-team'),
                    'view_item'          => __('View ShortCode', 'tlp-team'),
                    'search_items'       => __('ShortCode Locations', 'tlp-team'),
                    'not_found'          => __('No ShortCode found.', 'tlp-team'),
                    'not_found_in_trash' => __('No ShortCode found in trash.', 'tlp-team')
                ),
                'supports'            => array('title'),
                'public'              => false,
                'rewrite'             => false,
                'show_ui'             => true,
                'show_in_menu'        => 'edit.php?post_type=' . TLPTeam()->post_type,
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => false,
                'publicly_queryable'  => false,
                'capability_type'     => 'page',
            );
            register_post_type(TLPTeam()->getScPostType(), apply_filters('tlp-team-register-sc-args', $sc_args));
        }

        private function registerScriptStyle() {

            // register team scripts and styles
            $scripts = array();
            $styles = array();

            $scripts['tlp-owl-carousel'] = array(
                'src'    => TLPTeam()->assetsUrl . "vendor/owl-carousel/owl.carousel.min.js",
                'deps'   => array('jquery', 'imagesloaded'),
                'footer' => true
            );
            $scripts['tlp-isotope'] = array(
                'src'    => TLPTeam()->assetsUrl . "vendor/isotope/isotope.pkgd.min.js",
                'deps'   => array('jquery', 'imagesloaded'),
                'footer' => true
            );
            $scripts['tlp-team-block'] = array(
                'src'    => TLPTeam()->assetsUrl . "js/tlp-team-blocks.min.js",
                'deps'   => array('jquery'),
                'footer' => true
            );
            $scripts['tlp-team'] = array(
                'src'    => TLPTeam()->assetsUrl . "js/tlpteam.js",
                'deps'   => array('jquery'),
                'footer' => true
            );
            // register acf styles
            $styles['tlp-fontawsome'] = TLPTeam()->assetsUrl . 'vendor/font-awesome/css/font-awesome.min.css';
            $styles['tlp-owl-carousel'] = TLPTeam()->assetsUrl . 'vendor/owl-carousel/assets/owl.carousel.min.css';
            $styles['tlp-owl-carousel-theme'] = TLPTeam()->assetsUrl . 'vendor/owl-carousel/assets/owl.theme.default.min.css';
            $styles['tlp-team'] = TLPTeam()->assetsUrl . 'css/tlpteam.css';
            $styles['rt-team-sc'] = TLPTeam()->assetsUrl . 'css/sc.css';

            if (is_admin()) {
                $scripts['tlp-team-admin'] = array(
                    'src'    => TLPTeam()->assetsUrl . "js/settings.js",
                    'deps'   => array('jquery'),
                    'footer' => true
                );
                $styles['tlp-team-admin'] = TLPTeam()->assetsUrl . 'css/settings.css';
            }
            foreach ($scripts as $handle => $script) {
                wp_register_script($handle, $script['src'], $script['deps'], $this->version, $script['footer']);
            }

            foreach ($styles as $k => $v) {
                wp_register_style($k, $v, false, $this->version);
            }
        }
    }

endif;
