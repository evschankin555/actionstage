<?php
/**
 * Plugin Name: About Author
 * Version: 1.5.0
 * Description: Display Blog Author Information In Style
 * Author: Weblizar
 * Author URI: https://weblizar.com/plugins/about-author-pro/
 * Plugin URI: https://weblizar.com/plugins/about-author-pro/
 * Text Domain: WEBLIZAR_ABOUT_DOMAIN
 */

define("WEBLIZAR_ABOUT_ME_PLUGIN_URL", plugin_dir_url(__FILE__));
define('WEBLIZAR_ABOUT_DOMAIN', 'WEBLIZAR_ABOUT_DOMAIN');
add_filter('widget_text', 'do_shortcode');

add_action('plugins_loaded', 'WEBLIZAR_ABOUT_DOMAIN');
/**
 * Load plugin textdomain.
 */
function WEBLIZAR_ABOUT_DOMAIN()
{
	load_plugin_textdomain('WEBLIZAR_ABOUT_DOMAIN', false, plugin_basename(dirname(__FILE__)) . '/languages');
}

add_action('admin_menu', 'aa_submenu_settings_page');
function aa_submenu_settings_page()
{
	$settings = add_submenu_page('edit.php?post_type=about_author', esc_html__('Author Settings', WEBLIZAR_ABOUT_DOMAIN ), esc_html__('Author Settings', WEBLIZAR_ABOUT_DOMAIN ), 'administrator', 'author-settings', 'author_settings_function');

	$help = add_submenu_page('edit.php?post_type=about_author', esc_html__('Help and Support', WEBLIZAR_ABOUT_DOMAIN ), esc_html__('Help and Support', WEBLIZAR_ABOUT_DOMAIN ), 'administrator', 'help-and-support', 'about_author_help_suppot_function');
}

add_action('admin_enqueue_scripts', 'aa_color_picker');
function aa_color_picker($hook)
{
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
}
function about_author_help_suppot_function()
{
	require_once("help-and-support.php");
	wp_register_style('aap-custom-css', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/aap-custom-css.css');
	wp_enqueue_style('aap-custom-css');
}

function author_settings_function()
{
	require_once("author-settings.php");
	wp_register_style('aap-custom-css', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/aap-custom-css.css');
	wp_enqueue_style('aap-custom-css');
	wp_register_style('font-awesome', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/all.min.css');
	wp_enqueue_style('font-awesome');
}

// Add settings link on plugin page
function author_settings_link($links, $author_plugin_file)
{
	static $author_plugin_name;
	if (!isset($author_plugin_name))
		$author_plugin_name = plugin_basename(__FILE__);
	if ($author_plugin_name == $author_plugin_file) {
		$author_settings_link = array('Settings' => '<a href="edit.php?post_type=about_author&page=author-settings">' . esc_html__('Settings', 'General', 'WEBLIZAR_ABOUT_DOMAIN') . '</a>');
		$author_pro_link = array('Get Pro' => '<a style="font-weight:700; color:#e35400" href="https://weblizar.com/plugins/about-author-pro/" target="_blank">Get Premium</a>');
		$links = array_merge($author_settings_link, $links);
		$links = array_merge($author_pro_link, $links);
	}
	return $links;
}
add_filter('plugin_action_links', 'author_settings_link', 10, 5);

class About_Author_Shorcode_And_Widget
{
	public function __construct()
	{
		if (is_admin()) {
			add_action('init', array(&$this, 'AboutmeShortcode'));
			add_action('add_meta_boxes', array(&$this, 'Add_all_About_m_e_meta_boxes'), 1);
			add_action('admin_enqueue_scripts', array(&$this, 'my_about_me_style_files'), 1);
			add_action('about_me_save_post', array(&$this, 'About_me_Save_Settings'), 1);
			add_action('save_post', array(&$this, 'Weblizar_Save_fag_meta_box_save'), 9, 1);
		}
	}

	public function my_about_me_style_files($hook)
	{
		if ($hook != 'post.php' && $hook != 'post-new.php') {
			return;
		}
		wp_enqueue_script('jquery');
		wp_register_script('popper', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'js/popper.min.js', array('jquery'));
		wp_enqueue_script('popper');
		wp_register_script('bootstrap', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'js/bootstrap.min.js', array('jquery'));
		wp_enqueue_script('bootstrap');
		wp_enqueue_script('theme-preview');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('upload_media_widget', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'js/upload-media.js', array('jquery'));
		wp_enqueue_style('thickbox');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');


		// code-mirror css & js for custom css section
		wp_enqueue_style('font-awesome', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/all.min.css');
		wp_enqueue_style('author_codemirror-css', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/codemirror/codemirror.css');
		wp_register_style('author_blackboard', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/codemirror/blackboard.css');
		wp_enqueue_style('author_blackboard');
		wp_register_style('author_show-hint-css', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/codemirror/show-hint.css');
		wp_enqueue_style('author_show-hint-css');
		wp_enqueue_style('bootstrap', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/bootstrap.min.css');

		wp_register_script('author_codemirror-js', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/codemirror/codemirror.js', array('jquery'));
		wp_enqueue_script('author_codemirror-js');
		wp_register_script('author_css-js', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/codemirror/aa-css.js', array('jquery'));
		wp_enqueue_script('author_css-js');
		wp_register_script('author_css-hint-js', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/codemirror/css-hint.js', array('jquery'));
		wp_enqueue_script('author_css-hint-js');
	}

	// Register Custom Post Type
	public function AboutmeShortcode()
	{
		$labels = array(
			'name' 					=> esc_html_x( 'About Author', 'Taxonomy General Name', WEBLIZAR_ABOUT_DOMAIN ),
			'singular_name' 		=> esc_html_x( 'About Author', 'Post Type Singular Name', WEBLIZAR_ABOUT_DOMAIN ),
			'menu_name' 			=> esc_html__( 'About Author', WEBLIZAR_ABOUT_DOMAIN ),
			'add_new' 				=> esc_html__( 'Add New', WEBLIZAR_ABOUT_DOMAIN ),
			'add_new_item' 			=> esc_html__( 'Add New', WEBLIZAR_ABOUT_DOMAIN ),
			'edit_item' 			=> esc_html__( 'Edit About Author', WEBLIZAR_ABOUT_DOMAIN ),
			'new_item' 				=> esc_html__( 'New About Author', WEBLIZAR_ABOUT_DOMAIN ),
			'view_item' 			=> esc_html__( 'View About Author', WEBLIZAR_ABOUT_DOMAIN ),
			'search_items' 			=> esc_html__( 'Search About Author', WEBLIZAR_ABOUT_DOMAIN ),
			'not_found' 			=> esc_html__( 'No About Author Shortcode Found', WEBLIZAR_ABOUT_DOMAIN ),
			'not_found_in_trash' 	=> esc_html__( 'No About Author Shortcode in Trash', WEBLIZAR_ABOUT_DOMAIN ),
			'parent_item_colon' 	=> esc_html__( 'Parent About Author:', WEBLIZAR_ABOUT_DOMAIN ),
			'all_items' 			=> esc_html__( 'All Shortcodes', WEBLIZAR_ABOUT_DOMAIN ),
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array('title'),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 65,
			'menu_icon'           => 'dashicons-id',
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => false,
			'capability_type'     => 'post'
		);
		register_post_type('about_author', $args);
		add_filter('manage_edit-about_author_columns', array(&$this, 'about_author_columns'));
		add_action('manage_about_author_posts_custom_column', array(&$this, 'about_author_manage_columns'), 10, 2);
	}

	function about_author_columns($columns)
	{
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => esc_html__('About Author', 'WEBLIZAR_ABOUT_DOMAIN'),
			'shortcode' => esc_html__('About Author Shortcode', 'WEBLIZAR_ABOUT_DOMAIN'),
			'author'    => esc_html__('Author', 'WEBLIZAR_ABOUT_DOMAIN'),
			'date' => esc_html__('Date', 'WEBLIZAR_ABOUT_DOMAIN')
		);
		return $columns;
	}

	function about_author_manage_columns($column, $post_id)
	{
		global $post;
		switch ($column) {
			case 'shortcode':
				echo '<input type="text" value="[Weblizar id=' . $post_id . ']" readonly="readonly" />';
				break;
			default:
				break;
		}
	}

	//add metaboxes
	public function Add_all_About_m_e_meta_boxes()
	{
		add_meta_box(esc_html__(' Add Images', 'WEBLIZAR_ABOUT_DOMAIN'), esc_html__('Select Template', 'WEBLIZAR_ABOUT_DOMAIN'), array(&$this, 'about_me_meta_box_setting_function'), 'about_author', 'normal', 'low');
		add_meta_box(esc_html__('Add Settings', 'WEBLIZAR_ABOUT_DOMAIN'), esc_html__('Add settings', 'WEBLIZAR_ABOUT_DOMAIN'), array(&$this, 'me_meta_box_setting_function'), 'about_author', 'normal', 'low');
		add_meta_box(esc_html__('Copy Shortcode', 'WEBLIZAR_ABOUT_DOMAIN'), esc_html__('Copy Shortcode', 'WEBLIZAR_ABOUT_DOMAIN'), array(&$this, 'Weblizar_shotcode_meta_box_function'), 'about_author', 'side', 'low');
		add_meta_box(esc_html__('UpgradetoAboutAuthorPro', 'WEBLIZAR_ABOUT_DOMAIN'), esc_html__('About Author Pro', 'WEBLIZAR_ABOUT_DOMAIN'), array(&$this, 'weblizar_about_author_pro_meta_box'), 'about_author', 'side', 'low');
		add_meta_box(esc_html__('Preview Shortcode', 'WEBLIZAR_ABOUT_DOMAIN'), esc_html__('Preview Shortcode', 'WEBLIZAR_ABOUT_DOMAIN'), array(&$this, 'ab_preview_box'), 'about_author', 'side', 'low');
		add_meta_box(esc_html__('Activate About Author Widget', 'WEBLIZAR_ABOUT_DOMAIN'), esc_html__('Activate About Author Widget', 'WEBLIZAR_ABOUT_DOMAIN'), array(&$this, 'weblizar_use_widget_meta_box'), 'about_author', 'side', 'low');
	}

	//add setting page of general settings
	public function about_me_meta_box_setting_function($post)
	{
		require("settings/template-settings.php");
	}
	public function me_meta_box_setting_function($post)
	{
		require("settings/general-settings.php");
	}

	//display short code on custom post type page
	public function Weblizar_shotcode_meta_box_function()
	{ ?>
		<p><?php esc_html_e("Use below shortcode in any Page/Post to publish your author information", 'WEBLIZAR_ABOUT_DOMAIN'); ?></p>
		<input readonly="readonly" type="text" value="<?php echo "[Weblizar id=" . get_the_ID() . "]"; ?>">
	<?php
	}

	public function ab_preview_box()
	{
		if (isset($_REQUEST['post'])) {
			echo '
		<div style="text-align:center;padding:10px 0;">
			<h3>' . esc_html_e('Click here to preview', 'WEBLIZAR_ABOUT_DOMAIN') . '</h3>
			<input alt="#TB_inline?height=700&amp;width=750&amp;inlineId=Web_Lizar_Popup1" title="About Author shortcode and widget preview" class="button-primary thickbox" type="button" value="Preview" />
		</div>
		';
			$Weblizar = $_REQUEST['post'];
			echo '
		<div id="Web_Lizar_Popup1"  style="width:100%;height:100%;display:none"> <h2>' . esc_html__('Preview', 'WEBLIZAR_ABOUT_DOMAIN') . '</h2>' . do_shortcode('[Weblizar id="' . $Weblizar . '"]') . '</div>';
		} else {
			echo "<h4>" . esc_html_e("Please save first to check preview.", 'WEBLIZAR_ABOUT_DOMAIN') . "</h4> ";
		}
	}

	public function weblizar_use_widget_meta_box()
	{ ?>
		<div>
			<p><?php esc_html_e('To activate widget into any widget area', 'WEBLIZAR_ABOUT_DOMAIN'); ?></p>
			<p align="center"><a class="button button-primary button-hero" href="<?php get_site_url(); ?>./widgets.php"><?php esc_html_e('Click Here', 'WEBLIZAR_ABOUT_DOMAIN'); ?></a> </p>
			<p><?php esc_html_e('Find', 'WEBLIZAR_ABOUT_DOMAIN'); ?> <b><?php esc_html_e('About Author Widget', 'WEBLIZAR_ABOUT_DOMAIN'); ?></b> <?php esc_html_e('Place it to your widget area. Select any About Author Shortcode from the dropdown and save widget.', 'WEBLIZAR_ABOUT_DOMAIN'); ?></p>
		</div>
	<?php
	}

	public function weblizar_about_author_pro_meta_box()
	{
		wp_enqueue_style('author_dashboard-css', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/dashboard.css');
		$imgpath = WEBLIZAR_ABOUT_ME_PLUGIN_URL . "settings/images/aatp.jpg";
	?>
		<div class="">
			<div class="update_pro_button"><a target="_blank" href="https://weblizar.com/plugins/about-author-pro/">
					<?php esc_html_e('Buy Now $15', 'WEBLIZAR_ABOUT_DOMAIN'); ?></a></div>
			<div class="update_pro_image">
				<img class="aatp_getpro img-responsive" src="<?php echo esc_url($imgpath); ?>">
			</div>
			<div class="update_pro_button">
				<a class="upg_anch" target="_blank" href="https://weblizar.com/plugins/about-author-pro/">
					<?php esc_html_e('Buy Now $15', 'WEBLIZAR_ABOUT_DOMAIN'); ?></a>
			</div>
		</div>
	<?php
	}

	//save data in database
	public function Weblizar_Save_fag_meta_box_save($PostID)
	{
		if (isset($_POST['About_me_user_name']) && isset($_POST['About_me_web_site_name']) && isset($_POST['About_me_web_site_url'])) {
			$profile_user_image     = sanitize_text_field($_POST['profile_user_image']);
			$user_header_image      = sanitize_text_field($_POST['user_header_image']);
			$About_me_bg_color      = sanitize_hex_color($_POST['About_me_bg_color']);
			$About_me_user_name     = stripslashes_deep($_POST['About_me_user_name']);
			$About_me_user_name     = str_replace("\\", "", $About_me_user_name);
			$About_me_web_site_name = sanitize_text_field($_POST['About_me_web_site_name']);
			$About_me_web_site_url  = esc_url_raw($_POST['About_me_web_site_url']);
			$About_me_dis_cription  = stripslashes_deep($_POST['About_me_dis_cription']);
			$About_me_dis_cription  = str_replace("\\", "", $About_me_dis_cription);
			$followfb               = esc_url_raw($_POST['followfb']);
			$followinsta            = esc_url_raw($_POST['followinsta']);
			$followlinkdln          = esc_url_raw($_POST['followlinkdln']);
			$followpint             = esc_url_raw($_POST['followpint']);
			$followtwit             = esc_url_raw($_POST['followtwit']);
			$bodr                   = sanitize_text_field($_POST['bodr']);
			$img_bdr_type           = sanitize_text_field($_POST['img_bdr_type']);
			$bdr_size               = sanitize_text_field($_POST['bdr_size']);
			$img_bdr_color          = sanitize_hex_color($_POST['img_bdr_color']);
			$name_font_size         = sanitize_text_field($_POST['name_font_size']);
			$name_Color             = sanitize_hex_color($_POST['name_Color']);
			$weblink_font_size      = sanitize_text_field($_POST['weblink_font_size']);
			$weblink_text_color     = sanitize_hex_color($_POST['weblink_text_color']);
			$dis_font_size          = sanitize_text_field($_POST['dis_font_size']);
			$dis_text_color         = sanitize_hex_color($_POST['dis_text_color']);
			$PGPP_Font_Style        = sanitize_text_field($_POST['PGPP_Font_Style']);
			$About_me_social_color  = sanitize_hex_color($_POST['About_me_social_color']);
			$About_me_custom_css    = sanitize_text_field($_POST['About_me_custom_css']);
			$Tem_pl_at_e            = sanitize_text_field($_POST['Tem_pl_at_e']);
			$Social_icon_size       = sanitize_text_field($_POST['Social_icon_size']);

			$WeblizarArray[] = array(
				'About_me_bg_color'      => $About_me_bg_color,
				'About_me_user_name'     => $About_me_user_name,
				'About_me_web_site_name' => $About_me_web_site_name,
				'About_me_web_site_url'  => $About_me_web_site_url,
				'About_me_dis_cription'  => $About_me_dis_cription,
				'followfb'               => $followfb,
				'followinsta'            => $followinsta,
				'followlinkdln'          => $followlinkdln,
				'followpint'             => $followpint,
				'followtwit'             => $followtwit,
				'bodr'                   => $bodr,
				'img_bdr_type'           => $img_bdr_type,
				'bdr_size'               => $bdr_size,
				'img_bdr_color'          => $img_bdr_color,
				'name_font_size'         => $name_font_size,
				'name_Color'             => $name_Color,
				'weblink_font_size'      => $weblink_font_size,
				'weblink_text_color'     => $weblink_text_color,
				'dis_font_size'          => $dis_font_size,
				'dis_text_color'         => $dis_text_color,
				'PGPP_Font_Style'        => $PGPP_Font_Style,
				'profile_user_image'     => $profile_user_image,
				'user_header_image'      => $user_header_image,
				'About_me_social_color'  => $About_me_social_color,
				'About_me_custom_css'    => $About_me_custom_css,
				'Tem_pl_at_e'            => $Tem_pl_at_e,
				'Social_icon_size'       => $Social_icon_size,
			);
			$weblizar_Settings = "weblizar_Settings_" . $PostID;
			update_post_meta($PostID, $weblizar_Settings, serialize($WeblizarArray));
		}
	}
} // end of class

//create object of About_Author_Shorcode_And_Widget class
global $About_Author_Shorcode_And_Widget;
$About_Author_Shorcode_And_Widget = new About_Author_Shorcode_And_Widget();

//include short code file
require_once("about-author-use-shortcode.php");

// include widget code file
require_once("about-author-widget-code.php");
add_action('media_buttons_context', 'aa_add_rpg_custom_button');
add_action('admin_footer', 'aa_add_rpg_inline_popup_content');

//add media button fuction
function aa_add_rpg_custom_button($context)
{
	$container_id = 'AMSA';
	$title =  esc_html_e('Select About Author Shortcode to insert with content', 'WEBLIZAR_ABOUT_DOMAIN');
	$context = '<a class="button button-primary thickbox"  title="' . esc_html_e("Select About Author Shortcode to insert into content", 'WEBLIZAR_ABOUT_DOMAIN') . '"
	href="#TB_inline?width=400&inlineId=' . $container_id . '">
	' . esc_html_e("About Author Shortcode And Widget", "'WEBLIZAR_ABOUT_DOMAIN'") . '
	</a>';
	return $context;
}

function aa_add_rpg_inline_popup_content()
{ ?>
	<?php 
	wp_register_script( 'about-author-script', false );
	wp_enqueue_script( 'about-author-script' );
	$js = " ";
	ob_start(); ?>	
		jQuery(document).ready(function() {
			jQuery('#Ab_tm_insert').on('click', function() {
				var id = jQuery('#Ab_Tm_ME option:selected').val();
				window.send_to_editor('<p>[Weblizar id=' + id + ']</p>');
				tb_remove();
			})
		});
	<?php
	$js .= ob_get_clean();
	wp_add_inline_script( 'about-author-script', $js ); ?>

	<div id="AMSA" style="display:none;">
		<?php $all_posts = wp_count_posts('about_author')->publish;
		if (!$all_posts == null) { ?>
			<h3><?php esc_html_e('Select About Author Shortcode And Widget To Insert Into Post', 'WEBLIZAR_ABOUT_DOMAIN'); ?></h3>
			<select id="Ab_Tm_ME">
				<?php
				global $wpdb;
				$Web_Lizar_shortcodegallerys = $wpdb->get_results("SELECT post_title, ID FROM $wpdb->posts WHERE post_status = 'publish'	AND post_type='about_author' ");
				foreach ($Web_Lizar_shortcodegallerys as $Web_Lizar_shortcodegallery) {
					if ($Web_Lizar_shortcodegallery->post_title) {
						$title_var = $Web_Lizar_shortcodegallery->post_title;
					} else {
						$title_var = "(no title)";
					}
					echo "<option value='" . esc_attr($Web_Lizar_shortcodegallery->ID) . "'>" . esc_html($title_var) . "</option>";
				} ?>
			</select>
			<button class='button primary' id='Ab_tm_insert'><?php esc_html_e('Insert About Author Shortcode', 'WEBLIZAR_ABOUT_DOMAIN'); ?></button>
		<?php } else { ?>
			<h1 align="center"> <?php esc_html_e('No About Author Shortcode not_found ', 'WEBLIZAR_ABOUT_DOMAIN'); ?> </h1><?php
																													}
																														?>
	</div>
<?php
}

function fb_add_custom_user_profile_fields($user)
{ ?>
	<h3><?php esc_html_e('Social Profile Information', 'WEBLIZAR_ABOUT_DOMAIN'); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label><a target="_blank" style="text-decoration: none;"><i class="fab fa-facebook web_lizar_Social_icon"></i></a> <?php esc_html_e('Facebook', 'WEBLIZAR_ABOUT_DOMAIN'); ?></label>
			</th>
			<td><input id="followfb" name="followfb" type="text" value="<?php echo esc_attr(get_the_author_meta('followfb', $user->ID)); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th>
				<label><a target="_blank" style="text-decoration: none;"><i class="fab fa-twitter web_lizar_Social_icon"></i></a>
					<?php esc_html_e('Twitter', 'WEBLIZAR_ABOUT_DOMAIN'); ?></label>
			</th>
			<td><input id="followtwit" name="followtwit" type="text" value="<?php echo esc_attr(get_the_author_meta('followtwit', $user->ID)); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th>
				<label><a target="_blank" style="text-decoration: none;"> <i class="fab fa-linkedin web_lizar_Social_icon"></i></a><?php esc_html_e('LinkedIn', 'WEBLIZAR_ABOUT_DOMAIN'); ?></label>
			</th>
			<td>
				<input id="followlinkdln" name="followlinkdln" type="text" value="<?php echo esc_attr(get_the_author_meta('followlinkdln', $user->ID)); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th>
				<label><a target="_blank" style="text-decoration: none;"><i class="fab fa-instagram web_lizar_Social_icon"></i></a> <?php esc_html_e('Instagram', 'WEBLIZAR_ABOUT_DOMAIN'); ?></label>
			</th>
			<td>
				<input id="followinsta" name="followinsta" type="text" value="<?php echo esc_attr(get_the_author_meta('followinsta', $user->ID)); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th>
				<label><a target="_blank" style="text-decoration: none;"><i class="fab fa-pinterest web_lizar_Social_icon"></i></a> <?php esc_html_e('Pinterest', 'WEBLIZAR_ABOUT_DOMAIN'); ?></label>
			</th>
			<td>
				<input id="followpint" name="followpint" type="text" value="<?php echo esc_attr(get_the_author_meta('followpint', $user->ID)); ?>" class="regular-text" />
			</td>
		</tr>
	</table>
<?php
}

function fb_save_custom_user_profile_fields($user_id)
{
	if (!current_user_can('edit_user', $user_id))
		return FALSE;
	$metas = array(
		'followfb'      => esc_url_raw($_POST['followfb']),
		'followinsta'   => esc_url_raw($_POST['followinsta']),
		'followlinkdln' => esc_url_raw($_POST['followlinkdln']),
		'followtwit'    => esc_url_raw($_POST['followtwit']),
		'followpint'    => esc_url_raw($_POST['followpint']),
	);
	foreach ($metas as $key => $value) {
		update_user_meta($user_id, $key, $value);
	}
}

add_action('show_user_profile', 'fb_add_custom_user_profile_fields');
add_action('edit_user_profile', 'fb_add_custom_user_profile_fields');

add_action('personal_options_update', 'fb_save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'fb_save_custom_user_profile_fields');

function load_author_info_after_page_content($content)
{
	if (!is_single()  && get_post_type($post = get_post()) == "page") {
		$Weblizar_io_settings = unserialize(get_option('author_info_Settings'));
		if ( empty( $Weblizar_io_settings) ) 
			return "Author Settings Not Found";

		$use_page = isset($Weblizar_io_settings[0]['Author_short_code']) ? $Weblizar_io_settings[0]['Author_short_code'] :null;
		$switch_off_page = isset($Weblizar_io_settings[0]['switch_off_page'])?$Weblizar_io_settings[0]['switch_off_page']:'no';
		if ($switch_off_page == 'yes') {
			if ($use_page) {
				$content .= do_shortcode('[ABINFO id=' . $use_page . ']');
			}
		}
	}
	return $content;
}
add_filter("the_content", "load_author_info_after_page_content", 20);

function load_author_info_after_post_content($content)
{
	if (is_single() && get_post_type($post = get_post()) == "post") {
		$Weblizar_io_settings = unserialize(get_option('author_info_Settings'));
		$use_page             = $Weblizar_io_settings[0]['Author_short_code'];
		$switch_off_post      = $Weblizar_io_settings[0]['switch_off_post'];
		if ($switch_off_post == 'yes') {
			if ($use_page) {
				$content .= do_shortcode('[ABINFO id=' . $use_page . ']');
			}
		}
	}
	return $content;
}
add_filter("the_content", "load_author_info_after_post_content", 20);
require_once("author-setting/about-author-use-shortcode2.php");

// Review Notice Box
add_action("admin_notices", "review_admin_notice_aatp_free");
function review_admin_notice_aatp_free()
{
	global $pagenow;
	$aatp_screen = get_current_screen();
	if ($pagenow == 'edit.php' && $aatp_screen->post_type == "about_author") {
		include('aatp-banner.php');
	}
}
?>