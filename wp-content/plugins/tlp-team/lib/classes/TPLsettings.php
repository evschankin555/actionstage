<?php

/**
 *
 */
class TPLsettings
{

    private $version;

    function __construct() {
        $this->version = defined('WP_DEBUG') && WP_DEBUG ? time() : TLP_TEAM_VERSION;
        add_action('init', array($this, 'tlp_pluginInit'));
        add_action('wp_ajax_tlpTeamSettings', array($this, 'tlpTeamSettings'));
        add_action('admin_menu', array($this, 'tlp_menu_register'));
        add_filter('plugin_action_links_' . TLP_TEAM_PLUGIN_ACTIVE_FILE_NAME, array($this, 'tlp_team_marketing'));
    }

    function tlp_team_marketing($links) {
        $links[] = '<a target="_blank" href="' . esc_url('http://demo.radiustheme.com/wordpress/plugins/tlp-team/') . '">Demo</a>';
        $links[] = '<a target="_blank" href="' . esc_url('https://radiustheme.com/how-to-setup-configure-tlp-team-free-version-for-wordpress/') . '">Documentation</a>';
        $links[] = '<a target="_blank" style="color: #39b54a;font-weight: 700;" href="' . esc_url('https://www.radiustheme.com/downloads/tlp-team-pro-for-wordpress/') . '">Get Pro</a>';

        return $links;
    }

    /**
     *  Ajax response for settings update
     */
    function tlpTeamSettings() {
        $error = true;
        $msg = null;
        if (TLPTeam()->verifyNonce()) {
            $error = false;
            unset($_REQUEST['action']);
            unset($_REQUEST['tlp_nonce']);
            unset($_REQUEST['_wp_http_referer']);
            update_option(TLPTeam()->options['settings'], $_REQUEST);
            flush_rewrite_rules();
            $msg = __('Settings successfully updated', "tlp-team");
        } else {
            $msg = __('Session Error!!', "tlp-team");
        }
        wp_send_json([
                'error' => $error,
                'msg'   => $msg,
                'res'   => $_REQUEST
            ]
        );
    }

    /**
     *  Text domain + image size register
     */
    function tlp_pluginInit() {
        $this->load_plugin_textdomain();
        $settings = get_option(TLPTeam()->options['settings']);
        $width = !empty($settings['feature_img']['width']) ? absint($settings['feature_img']['width']) : 400;
        $height = !empty($settings['feature_img']['height']) ? absint($settings['feature_img']['height']) : 400;
        add_image_size(TLPTeam()->options['feature_img_size'], $width, $height, true);
    }


    /**
     *  TLP menu addition
     */
    function tlp_menu_register() {
        $page = add_submenu_page('edit.php?post_type=team', __('TLP Team Settings', "tlp-team"), __('Settings', "tlp-team"), 'administrator', 'tlp_team_settings', array(
            $this,
            'tlp_team_settings'
        ));
        add_action('admin_print_styles-' . $page, array($this, 'tlp_style'));
        add_action('admin_print_scripts-' . $page, array($this, 'tlp_script'));

    }

    /**
     *  TLP Style addition
     */
    function tlp_style() {
        wp_enqueue_style('tpl_css_settings', TLPTeam()->assetsUrl . 'css/settings.css', '', TLP_TEAM_VERSION);
    }

    /**
     *  Tlp script addition
     */
    function tlp_script() {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('tpl_js_settings', TLPTeam()->assetsUrl . 'js/settings.js', array(
            'jquery',
            'wp-color-picker'
        ), $this->version, true);
        $nonce = wp_create_nonce(TLPTeam()->nonceText());
        wp_localize_script('tpl_js_settings', 'tlp_var', array('tlp_nonce' => $nonce));
    }

    function tlp_team_settings() {
        TLPTeam()->render_view('settings');
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 0.1.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain('tlp-team', false, TLP_TEAM_LANGUAGE_PATH);
    }

}
