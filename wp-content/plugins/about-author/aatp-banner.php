<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style( 'respport-banner', WEBLIZAR_ABOUT_ME_PLUGIN_URL . 'css/aatp-banner.css' );
$aap_imgpath = WEBLIZAR_ABOUT_ME_PLUGIN_URL . "settings/images/aatp_pro.png";
?>
<div class="wb_plugin_feature notice  is-dismissible">
    <div class="wb_plugin_feature_banner default_pattern pattern_ ">
        <div class="wb-col-md-6 wb-col-sm-12 box">
            <div class="ribbon"><span><?php esc_html_e('Go Pro', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></span></div>
            <img class="wp-img-responsive" src="<?php echo esc_url($aap_imgpath); ?>" alt="img">
        </div>
        <div class="wb-col-md-6 wb-col-sm-12 wb_banner_featurs-list">
            <span class="gp_banner_head">
                <h2><?php esc_html_e( 'About Author Pro Features', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </h2>
            </span>
            <ul>
                <li><?php esc_html_e( 'More Profile Image Layout', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
                <li><?php esc_html_e( 'Profile Header background', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li> 
                <li><?php esc_html_e( 'Multiple Author Widget', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
                <li><?php esc_html_e( 'Multiple Author Shortcode', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
                <li><?php esc_html_e( '10 Design Author Template', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
                <li><?php esc_html_e( 'Multiple Author Image Layout', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
                <li><?php esc_html_e( '8 Type of Hover Animations', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
                <li><?php esc_html_e( 'More Social Media Settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
                <li><?php esc_html_e( 'Responsive Design & Many More', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></li>
            </ul>
            <div class="wp_btn-grup">
                <a class="wb_button-primary" href="http://demo.weblizar.com/about-author-pro/"
                   target="_blank"><?php esc_html_e( 'View Demo', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></a>
                <a class="wb_button-primary" href="https://weblizar.com/plugins/about-author-pro/"
                   target="_blank"><?php esc_html_e( 'Buy Now', 'WEBLIZAR_ABOUT_DOMAIN' ); ?><?php esc_html_e( '$15', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </a>
            </div>
        </div>
    </div>
</div>