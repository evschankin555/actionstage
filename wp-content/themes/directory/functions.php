<?php
/*
 * 1. Constants to help us throughout the theme.
 */
DEFINE('DIRECTORY_CSS_PATH', get_template_directory_uri() . '/assets/css/');
DEFINE('DIRECTORY_JS_PATH', get_template_directory_uri() . '/assets/js/');
DEFINE('DIRECTORY_IMAGES_PATH', get_template_directory_uri() . '/assets/images/');
DEFINE('DIRECTORY_FONTS_PATH', get_template_directory_uri() . '/assets/fonts/');
DEFINE('DIRECTORY_STYLESHEET_PATH', get_stylesheet_uri());
DEFINE('DIRECTORY_FRAMEWORK_PATH', get_template_directory_uri() . '/framework/');
DEFINE('DIRECTORY_FRAMEWORK_REQUIRED_PATH', get_template_directory() . '/framework/');

/*
 * 2. After setup theme
 */
add_action('after_setup_theme', 'directory_setup');
if (!function_exists('directory_setup')):
    function directory_setup()
    {

        if (!isset($content_width))
            $content_width = 750;

        add_theme_support('automatic-feed-links');
        load_theme_textdomain('directory', get_template_directory() . '/languages');
        add_theme_support('html5', array('gallery', 'caption'));

        global $wp_version;
        if (version_compare($wp_version, '4.1', '>=')):
            add_theme_support('title-tag');
        endif;

        register_nav_menus(array(
            'main' => __('Main Navigation', 'directory'),
            'top' => __('Top Navigation', 'directory'),
        ));

        $directory_bg_defaults = array(
            'default-color' => 'ffffff',
            'default-image' => '',
            'wp-head-callback' => 'directory_bg_callback',
        );
        add_theme_support('custom-background', $directory_bg_defaults);

        $directory_header_defaults = array(
            'default-image' => '',
            'random-default' => false,
            'width' => '1920',
            'height' => '650',
            'flex-height' => false,
            'flex-width' => false,
            'default-text-color' => '',
            'header-text' => false,
            'uploads' => true,
            'wp-head-callback' => '',
            'admin-head-callback' => '',
            'admin-preview-callback' => '',
        );

        add_theme_support('custom-header', $directory_header_defaults);

        add_theme_support('post-thumbnails');
        add_image_size('slider-image', 1920, 850, true); // slider image size.
        add_image_size('smaller-slider-image', 1300, 650, true); // slider image size.
        add_image_size('boxed-9', 847, 385, true); // slider image size.
        add_image_size('boxed-12', 1170, 550, true); // Single post type image (boxed 3/4 layout)
        add_image_size('fullwidth', 1920, 700, true); // Single post type image (fulwidth)
        add_image_size('listing-image',768,550,true);
    }
endif;

/*
 * 3. Fallback Functions
 */
if (!function_exists('directory_bg_callback')):

    function directory_bg_callback()
    {
        $background = set_url_scheme(get_background_image());
        $color = get_theme_mod('background_color', get_theme_support('custom-background', 'default-color'));

        if (!$background && !$color)
            return;

        $style = $color ? "background-color: #$color;" : '';

        if ($background) {
            $image = " background-image: url('$background');";

            $repeat = get_theme_mod('background_repeat', get_theme_support('custom-background', 'default-repeat'));
            if (!in_array($repeat, array('no-repeat', 'repeat-x', 'repeat-y', 'repeat')))
                $repeat = 'repeat';
            $repeat = " background-repeat: $repeat;";

            $position = get_theme_mod('background_position_x', get_theme_support('custom-background', 'default-position-x'));
            if (!in_array($position, array('center', 'right', 'left')))
                $position = 'left';
            $position = " background-position: top $position;";

            $attachment = get_theme_mod('background_attachment', get_theme_support('custom-background', 'default-attachment'));
            if (!in_array($attachment, array('fixed', 'scroll')))
                $attachment = 'scroll';
            $attachment = " background-attachment: $attachment;";

            $style .= $image . $repeat . $position . $attachment;
        }
        ?>
        <style type="text/css" id="custom-background-css">
            body.custom-background {
            <?php echo trim( $style ); ?>
            }
        </style>
        <?php
    }
endif;

/*
 * 4. Enqueue styles + scripts.
 */
add_action('wp_enqueue_scripts', 'directory_styles');
if (!function_exists('directory_styles')):
    function directory_styles()
    {
        wp_enqueue_style('directory-ubuntu-font', DIRECTORY_FONTS_PATH . 'Ubuntu/font-ubuntu-minified.css', '', '', 'all');
        wp_enqueue_style('directory-raleway-font', DIRECTORY_FONTS_PATH . 'Raleway/stylesheet.css', '', '', 'all');

        wp_enqueue_style('directory-fontello', DIRECTORY_FONTS_PATH . 'Directory-font/css/fontello.css', '', '', 'all');
        wp_enqueue_style('directory-fontello-codes', DIRECTORY_FONTS_PATH . 'Directory-font/css/fontello-codes.css', '', '', 'all');
        wp_enqueue_style('directory-fontello-embedded', DIRECTORY_FONTS_PATH . 'Directory-font/css/fontello-embedded.css', '', '', 'all');

        wp_enqueue_style('directory-minified-css', DIRECTORY_CSS_PATH . 'minified.css.php', '', '', 'all');
        wp_enqueue_style('directory-main-stylesheets', DIRECTORY_STYLESHEET_PATH);
    }
endif;

add_action('wp_enqueue_scripts', 'directory_scripts');
if (!function_exists('directory_scripts')):
    function directory_scripts()
    {
        if (is_singular() && get_option('thread_comments'))
            wp_enqueue_script('comment-reply');

        wp_enqueue_script('directory-bootstrap', DIRECTORY_JS_PATH . 'bootstrap.min.js', array('jquery'), '', true);
        wp_enqueue_script('directory-sidr', DIRECTORY_JS_PATH . 'jquery.sidr.min.js', array('jquery'), '', true);
        wp_enqueue_script('directory-stickyjs', DIRECTORY_JS_PATH . 'jquery.sticky.js', array('jquery'), '', true);
        wp_enqueue_script('directory-matchHeight', DIRECTORY_JS_PATH . 'jquery.matchHeight-min.js', array('jquery'), '', true);
        wp_enqueue_script('directory-gmaps', DIRECTORY_JS_PATH . 'gmaps.js',array( 'jquery' ),'',true);

        $api_key = sanitize_text_field(get_theme_mod('google-api-key',''));

        if($api_key == ''):

          wp_enqueue_script('directory-gmaps-sapi', 'http://maps.google.com/maps/api/js', array('jquery'), '', false);

        else:
          $api_url = "http://maps.google.com/maps/api/js?key=$api_key";
          wp_enqueue_script('directory-gmaps-sapi', $api_url, array('jquery'), '', false);

        endif;

        wp_enqueue_script('directory-main-js', DIRECTORY_JS_PATH . 'main-js.js', array('jquery'), '', true);

    }
endif;

add_action('wp_head', 'directory_html5shiv');
function directory_html5shiv()
{
    echo '<!--[if lt IE 9]>';
    echo '<script src="' . DIRECTORY_JS_PATH . 'html5shiv.min.js"></script>';
    echo '<![endif]-->';
}
function directory_admin_scripts(){
    wp_enqueue_script('directory-shortcode-btn',DIRECTORY_JS_PATH.'shortcode-btn.js',array('jquery'),'',true);
}
add_action('admin_enqueue_scripts','directory_admin_scripts');
/*
 * 5. Comments
 */
if(!function_exists('directory_comment')):
    function directory_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ( 'div' == $args['style'] ) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
        <?php if ( 'div' != $args['style'] ) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>
        <div class="row">

            <div class="col-md-2">


                <div class="comment-author vcard">
                    <?php
                    if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>

                </div>

            </div>

            <div class="col-md-10">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'directory'); ?></em>
                    <br/>
                <?php endif; ?>

                <?php printf(__('<cite class="fn">%s</cite>','directory'), get_comment_author_link()); ?>

                <div class="comment-meta commentmetadata">
                    <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                        <?php
                        printf(__('%1$s at %2$s', 'directory'), get_comment_date(), get_comment_time()); ?></a><?php edit_comment_link(__('(Edit)', 'directory'), '  ', '');
                    ?>
                </div>
                <hr class="comment-name-hr">

                <div class="directory_comment_body">
                    <?php comment_text(); ?>
                </div>
                <div class="reply">
                    <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                </div>
            </div>
        </div>
        <?php if ( 'div' != $args['style'] ) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
endif;
/*
 * 6. Widgets Initialization
 */
/**== Add some sidebars ==**/
add_action('widgets_init','directory_sidebars');
function directory_sidebars(){

    /**== Right sidebar ==**/
    register_sidebar( array(
        'name'              => __( 'Sidebar', 'directory' ),
        'id'                => 'sidebar',
        'description'       => __( 'This is the main sidebar.It is in every page - post. However you can override this setting from within each post.',
            'directory' ),
        'before_widget'     => '<div id="%1$s" class="widget %2$s">',
        'after_widget'      => '</div>',
        'before_title'      => '<h3 class="widget-title">',
        'after_title'       => '</h3>'
    ));

    /*
     * Home sidebar 1
     */

    register_sidebar( array(
        'name'              => __( 'Home sidebar 1', 'directory' ),
        'id'                => 'home-sidebar-1',
        'description'       => __( 'The first home sidebar on the left.', 'directory' ),
        'before_widget'     => '<div id="%1$s" class="widget %2$s">',
        'after_widget'      => '</div>',
        'before_title'      => '<h3 class="widget-title">',
        'after_title'       => '</h3>'
    ));

    /*
   * Home sidebar 2
   */

    register_sidebar( array(
        'name'              => __( 'Home sidebar 2', 'directory' ),
        'id'                => 'home-sidebar-2',
        'description'       => __( 'The second home sidebar on the left.', 'directory' ),
        'before_widget'     => '<div id="%1$s" class="widget %2$s">',
        'after_widget'      => '</div>',
        'before_title'      => '<h3 class="widget-title">',
        'after_title'       => '</h3>'
    ));

    /*
   * Home sidebar 3
   */
    register_sidebar( array(
        'name'              => __( 'Home sidebar 3', 'directory' ),
        'id'                => 'home-sidebar-3',
        'description'       => __( 'The second home sidebar on the right.', 'directory' ),
        'before_widget'     => '<div id="%1$s" class="widget %2$s">',
        'after_widget'      => '</div>',
        'before_title'      => '<h3 class="widget-title">',
        'after_title'       => '</h3>'
    ));

    /*
   * Home sidebar 4
   */
    register_sidebar( array(
        'name'              => __( 'Home sidebar 4', 'directory' ),
        'id'                => 'home-sidebar-4',
        'description'       => __( 'The first home sidebar on the right.', 'directory' ),
        'before_widget'     => '<div id="%1$s" class="widget %2$s">',
        'after_widget'      => '</div>',
        'before_title'      => '<h3 class="widget-title">',
        'after_title'       => '</h3>'
    ));


    /**== Footer Sidebar 1 ==**/
    register_sidebar( array(
        'name'              => __( 'Footer Sidebar 1', 'directory' ),
        'id'                => 'directory-footer-1',
        'description'       => __( 'This is the sidebar in the footer, on the left', 'directory' ),
        'before_widget'     => '<aside id="%1$s" class="footerwidget %2$s">',
        'after_widget'      => '</aside>',
        'before_title'      => '<h3 class="widget-title">',
        'after_title'       => '</h3>'
    ));
    /**== Footer Sidebar 1 ==**/
    register_sidebar( array(
        'name'              => __( 'Footer Sidebar 2', 'directory' ),
        'id'                => 'directory-footer-2',
        'description'       => __( 'This is the sidebar in the footer, the second on the left', 'directory' ),
        'before_widget'     => '<aside id="%1$s" class="footerwidget %2$s">',
        'after_widget'      => '</aside>',
        'before_title'      => '<h3 class="widget-title">',
        'after_title'       => '</h3>'
    ));
}
/*
 * Misc Functions that apply to this file
 */
if (version_compare($GLOBALS['wp_version'], '4.1', '<')) :
    function directory_wp_title($title,
                                  $sep)
    {
        if (is_feed()) {
            return $title;
        }
        global $page, $paged;

        $title .= get_bloginfo('name', 'display');

        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            $title .= " $sep $site_description";
        }
        if (($paged >= 2 || $page >= 2) && !is_404()) {
            $title .= " $sep " . sprintf(__('Page %s', 'directory'), max($paged, $page));
        }
        return $title;
    }

    add_filter('wp_title', 'directory_wp_title', 10, 2);
endif;

function directory_upsell_notice(){
    // Enqueue the script
    wp_enqueue_script(
        'prefix-customizer-upsell',
        DIRECTORY_JS_PATH.'upsell.js',
        array(), '1.0.0',
        true
    );

    wp_localize_script(
        'prefix-customizer-upsell',
        'prefixL10n',
        array(
            'prefixURL' => esc_url('http://ketchupthemes.com/wordpress-directory-theme'),
            'prefixLabel' => __('Upgrade To Premium', 'directory') ,
            'paragraphText'=>__('Need Paid listings and a lot of features?','directory'),
            'premiumSpanTxt'=>__('- OR CHECK -','directory'),
            'premiumDemoTxt'=>__('Premium Demo','directory'),
            'premiumDemoUrl'=>esc_url('http://ketchupthemes.com/themes-demo/?theme=Directory')
        )
    );


}
add_action('customize_controls_enqueue_scripts', 'directory_upsell_notice');


/*
 * . Required Files
 */
require_once(DIRECTORY_FRAMEWORK_REQUIRED_PATH . 'directory-init.php');
