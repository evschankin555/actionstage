<?php
/**
 * Plugin Name: HTTP / HTTPS Removal
 * Plugin URI: https://wordpress.org/plugins/http-https-Removal/
 * Description: This Plugin creates protocol relative urls by removing http + https from links.
 * Version: 3.1
 * Author: Steve85b
 * Author URI: -
 * License: GPLv3
 */

require_once 'analyst/main.php';
analyst_init(array(
	'client-id' => 'jl0865yb0z3bgmew',
	'client-secret' => 'f07b52982090022fd84d273455f40dc3a5ccf328',
	'base-dir' => __FILE__
));

if (!defined('ABSPATH'))
	exit;

class HTTP_HTTPS_Removal
{
	public function __construct()
	{
		add_action('wp_loaded', array(
			$this,
			'init'
		), 99, 1);

		/* Plugin Activation Hook */
		register_activation_hook(__FILE__,   array($this, 'jr_default_activation_hook'));
		/* Add admin notice */
		add_action('admin_notices', array($this, 'jr_activation_notice_hook'));
		/* Adding links filter */
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'jr_add_action_links'));
		/* Remove Trans */
		add_action('wp_ajax_nopriv_jr_remove_trans', array($this, 'jr_remove_set_transient'));
		add_action('wp_ajax_jr_remove_trans', array($this, 'jr_remove_set_transient'));
		add_action('admin_init', array($this, 'httpHttpsRegisterSettings'));
		add_action('admin_menu', array($this,'httpHttpsRegisterOptionPage'));
		add_action('admin_head', array($this, 'adminAssets'));
		add_action('activated_plugin', array($this,'httphttpsRemovalRedirect'));
	}

	public function httphttpsRemovalRedirect($plugin) {
		if( $plugin == plugin_basename( __FILE__ ) ) {
			exit(wp_redirect(admin_url('options-general.php?page=httphttpsRemoval')));
		}
	}

	public function httpHttpsRegisterSettings() {
		register_setting('httphttpsRemovalOptionGroup', 'httpHttpsRemovalOptions');

		add_settings_section('httphttpsRemovalSection','','','httphttpsRemovalOptionGroup');
		
		add_settings_field(
			'enableDisable',
			__('Enable/Disable', 'httphttpsRemovalOptionGroup'),
			array($this,'enableDisableCallback'),
			'httphttpsRemovalOptionGroup',
			'httphttpsRemovalSection',
			[
				'label_for' => 'enableDisable'
			]
		);

		add_settings_field(
			'fixGoogleFonts',
			__( 'Fix Google Fonts Issue', 'httphttpsRemovalOptionGroup' ),
			array($this,'fixGoogleFontsCallback'),
			'httphttpsRemovalOptionGroup',
			'httphttpsRemovalSection',
			[
				'label_for' => 'fixGoogleFonts'
			]
		);

		add_settings_field(
			'ignoreURLs',
			__( 'Ignore URLs', 'httphttpsRemovalOptionGroup' ),
			array($this,'ignoreURLsCallback'),
			'httphttpsRemovalOptionGroup',
			'httphttpsRemovalSection',
			[
				'label_for' => 'ignoreURLs',
			]
		);

		add_settings_field(
			'ignoreAdmin',
			__( 'Ignore Admin Links', 'httphttpsRemovalOptionGroup' ),
			array($this,'ignoreAdminCallback'),
			'httphttpsRemovalOptionGroup',
			'httphttpsRemovalSection',
			[
				'label_for' => 'ignoreAdmin'
			]
		);
	}

	public function enableDisableCallback($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('httpHttpsRemovalOptions');
		$checked = "";
		if ( is_array( $options ) && $options['enableDisable'] == '1' ) {
			$checked = 'Checked';
		}
		// output the field
		?>
		<input type="checkbox" name="httpHttpsRemovalOptions[<?php echo esc_attr( $args['label_for'] ); ?>]" value="1" <?php echo $checked;?> id="<?php echo esc_attr( $args['label_for'] ); ?>">
		<span>Enable HTTP/HTTPs Removal</span>
		<p class="description">
			<?php esc_html_e( '(Only when you enable it the http/https removal will be active. Please don\'t forget to click on "Save" below!)', 'httpHttpsRemovalOptions' ); ?>
		</p>
		<?php
	}

	public function fixGoogleFontsCallback($args)
	{
		$options = get_option('httpHttpsRemovalOptions');
		$checked = "";
		if ( is_array( $options ) && $options['fixGoogleFonts'] == '1' ) {
			$checked = 'Checked';
		}
		?>
		<input type="checkbox" name="httpHttpsRemovalOptions[<?php echo esc_attr( $args['label_for'] ); ?>]" value="1" <?php echo $checked;?> id="<?php echo esc_attr( $args['label_for'] ); ?>">
		<span>Fix Google Fonts Issue.</span>
		<p class="description">
			<?php esc_html_e( '(If you don\'t want to remove http/https from Google Fonts URL, please check this option!)', 'httpHttpsRemovalOptions' ); ?>
		</p>
		<?php
	}

	public function ignoreURLsCallback($args)
	{
		$options = get_option('httpHttpsRemovalOptions');
		?>
		<textarea name="httpHttpsRemovalOptions[<?php echo esc_attr( $args['label_for'] ); ?>]" id="<?php echo esc_attr( $args['label_for'] ); ?>" cols="15" rows="10" class="textarea"><?php echo $options['ignoreURLs']?></textarea>
		<p class="description">
			<?php esc_html_e( '(Please add URL of the website here [one URL in one line]). Plugin will ignore those URLs to remove http/https!)', 'httpHttpsRemovalOptions' ); ?>
		</p>
		<?php
	}

	public function ignoreAdminCallback($args)
	{
		$options = get_option('httpHttpsRemovalOptions');
		$checked = "";
		if ( is_array( $options ) && $options['ignoreAdmin'] == '1' ) {
			$checked = 'Checked';
		}
		?>
		<input type="checkbox" name="httpHttpsRemovalOptions[<?php echo esc_attr( $args['label_for'] ); ?>]" value="1" <?php echo $checked;?> id="<?php echo esc_attr( $args['label_for'] ); ?>">
		<span>Ignore dashboard links</span>
		<p class="description">
			<?php esc_html_e( '(If you check this, plugin will ignore links from administrator area!)', 'httpHttpsRemovalOptions' ); ?>
		</p>
		<?php
	}

	public function httpHttpsRegisterOptionPage()
	{
		add_options_page('HTTP/HTTPs Removal Settings', 'HTTP/HTTPs Removal Settings', 'manage_options', 'httphttpsRemoval', array($this,'httphttpsRemovalOptionPage'));
	}

	public function httphttpsRemovalOptionPage()
	{ 
		if (!current_user_can('manage_options')) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'httphttpsRemovalOptionGroup' );
				do_settings_sections( 'httphttpsRemovalOptionGroup' );
				submit_button('Save Settings'); ?>
			</form>
		</div>
		<div class="clear"></div>
		<?php
	}

	public function jr_remove_set_transient()
	{
		delete_transient('jr-wp-admin-notice');
		echo "Transient Deleted!";
		exit;
	}

	/**
	 * Create transient data
	 */
	public function jr_default_activation_hook()
	{
		set_transient('jr-wp-admin-notice', true, 0);
		if(get_option('httpHttpsRemovalOptions') == '') {
			$default = array('enableDisable' => '1');
			update_option('httpHttpsRemovalOptions', $default, true);
		}
	}

	/**
	 * Adding Links
	 */
	function jr_add_action_links($links)
	{
		$updated_links = array(
			'<a target="_blank" class="green" href="https://sellcodes.com/s/Ocs1aK">Get share count recovery</a>',
		);
		return array_merge($links, $updated_links);
	}

	/**
	 * Admin Notice on Activation.
	 */
	public function jr_activation_notice_hook()
	{
		/* Check transient */
		if (get_transient('jr-wp-admin-notice')) { ?>
			<div class="updated notice is-dismissible http_custom_class">
				<p>Plugin "HTTP / HTTPS Removal: SSL Mixed Content Fix” has been successfully installed and activated – hurray!</p>
				<p>
					<strong>Next:</strong> if you don‘t want to lose traffic to your site we strongly recommend to get the <a style="color:green;" target="_blank" href="https://sellcodes.com/s/Ocs1aK">Ultimate Social Media plugin</a> which has a share count recovery feature (so that you don‘t lose any share counts after your switch to https) besides many other cool features.
				</p>
				<div class="notices">
					<div class="notice_links">
						<script type="text/javascript" src="https://sellcodes.com/quick_purchase/XdHlrQnc/embed.js"></script>
						<div class="sellcodes-quick-purchase">
							<a style="color: #008000;font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell, 'Helvetica Neue',sans-serif;font-size: 14px !important;text-decoration: underline !important;" class="sc-button" data-product-id="XdHlrQnc" data-option-id="4HiwuC6Y" data-referral="Ocs1aK">Get plugin now</a>
						</div>
					</div>
					<div class="notice_links">
						<a class="green" target="_blank" href="https://sellcodes.com/s/Ocs1aK">See all plugin features </a>
					</div>
					<div class="notice_links">
						<a id="remove_trans_anchor" class="gray" href="#">I‘m fine with getting less shares & traffic</a>
					</div>
					<div style="clear: both;"></div>
				</div>
			</div>
			<?php
		}
	}

	public function init()
	{
		$options = get_option('httpHttpsRemovalOptions');
		// If plugin settings is not enabled, do not proceed
		if (!is_array($options) || $options['enableDisable'] != '1') {
			return;
		}
		// If ignore admin links
		if ((is_admin() && is_array($options) && $options['ignoreAdmin'] == '1')) {
			return;
		}

		add_filter('script_loader_src', array($this,'removeHttpHttps'));
		add_filter('style_loader_src', array($this,'removeHttpHttps'));
	}

	public function removeHttpHttps($url)
	{
		$options = get_option('httpHttpsRemovalOptions');
		if (is_array($options) && $options['fixGoogleFonts'] == '1') {
			if (strpos($url, 'fonts.googleapis.com') !== false) {
				return $url;
			}
		}
		if (is_array($options) && $options['ignoreURLs'] != '') {
			$ignoredArr = preg_split('/\r\n|[\r\n]/', $options['ignoreURLs']);
			foreach($ignoredArr as $ignoreURLs) {
				$parsedUrl =    parse_url($ignoreURLs);
				if($parsedUrl['path'] != '') {
					$host   =   $parsedUrl['path'];
				}
				else {
					$host   =   $parsedUrl['host'];
				}
				if (strpos($url, $host) !== false) {
					return $url;
				}
			}
		}
		$url = preg_replace('/https?:/i', '', $url);
		return $url;
	}

	public function adminAssets()
	{
		wp_enqueue_style('admin-style', plugin_dir_url( __FILE__ ).'css/style.css', array(), '1.0');
		wp_enqueue_script('admin-js', plugin_dir_url( __FILE__ ).'js/script.js', array(), '1.0');
		wp_localize_script('admin-js', 'script_vars', array('ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
}

$http_https_Removal = new HTTP_HTTPS_Removal();

function jr_upgrade_completed($upgrader_object, $options)
{
	// The path to our plugin's main file
	$our_plugin = plugin_basename(__FILE__);
	// If an update has taken place and the updated type is plugins and the plugins element exists
	if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset($options['plugins'])) {
		// Iterate through the plugins being updated and check if ours is there
		foreach ($options['plugins'] as $plugin) {
			if ($plugin == $our_plugin) {
				// Set a transient to record that our plugin has just been updated
				jr_default_activation_hook();
			}
		}
	}
}
add_action('upgrader_process_complete', 'jr_upgrade_completed', 10, 2);

function jr_temp_function_to_check_checkbox_update_24_to_31()
{
	if( ! function_exists( 'get_plugin_data' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data 				=	get_plugin_data( __FILE__ );
    $current_version_function 	=	$plugin_data["Version"];
    $current_version_database 	=	get_option('jr_plugin_version',0);

    if($current_version_database < $current_version_function){
    	$removalOptions = get_option('httpHttpsRemovalOptions',array());
    	if(!isset($removalOptions["enableDisable"])) {
    		$removalOptions["enableDisable"] = "1";
    	}
    	if(!isset($removalOptions["fixGoogleFonts"])) {
    		$removalOptions["fixGoogleFonts"] = "0";
    	}
    	if(!isset($removalOptions["ignoreURLs"])) {
    		$removalOptions["ignoreURLs"] = "";
    	}
    	if(!isset($removalOptions["ignoreAdmin"])) {
    		$removalOptions["ignoreAdmin"] = "0";
    	}
		update_option('httpHttpsRemovalOptions', $removalOptions);
    	update_option("jr_plugin_version",$current_version_function);
    }
}
add_action("init", "jr_temp_function_to_check_checkbox_update_24_to_31");