<?php add_shortcode( 'Weblizar', 'ABOUTMEUSER' );
function ABOUTMEUSER( $Id ) {
	wp_enqueue_style('font-awesome', WEBLIZAR_ABOUT_ME_PLUGIN_URL.'css/all.min.css');

	ob_start();
	if(isset($Id['id'])) {
		/**
		 * Load About Author Custom Post Type
		 */
		$Weblizar_CPT_Name = "about_author";
		$AllWeblizar = array(  'p' => $Id['id'], 'post_type' => $Weblizar_CPT_Name, 'orderby' => 'ASC', 'post_status' => 'publish');
		$loop = new WP_Query( $AllWeblizar );

		while ( $loop->have_posts() ) : $loop->the_post();

		$ID = get_the_ID();
		$weblizar_Settings = "weblizar_Settings_".$ID;
		$Weblizar_sets = unserialize(get_post_meta( $ID, $weblizar_Settings, true));
        // print_r( $Weblizar_sets);
		if(is_array($Weblizar_sets)){
			foreach($Weblizar_sets as $Weblizar_Settings) {
				$p_o_s_t                = $ID;
				$profile_user_image     = $Weblizar_Settings['profile_user_image'];
				$user_header_image      = $Weblizar_Settings['user_header_image'];
				$About_me_bg_color      = $Weblizar_Settings['About_me_bg_color'];
				$About_me_user_name     = $Weblizar_Settings['About_me_user_name'];
				$About_me_web_site_name = $Weblizar_Settings['About_me_web_site_name'];
				$About_me_web_site_url  = $Weblizar_Settings['About_me_web_site_url'];
				$About_me_dis_cription  = $Weblizar_Settings['About_me_dis_cription'];
				$followfb               = $Weblizar_Settings['followfb'];
				$followinsta            = $Weblizar_Settings['followinsta'];
				$followlinkdln          = $Weblizar_Settings['followlinkdln'];
				$followpint             = $Weblizar_Settings['followpint'];
				$followtwit             = $Weblizar_Settings['followtwit'];
				$bodr                   = $Weblizar_Settings['bodr'];
				$img_bdr_type           = $Weblizar_Settings['img_bdr_type'];
				$bdr_size               = $Weblizar_Settings['bdr_size'];
				$img_bdr_color          = $Weblizar_Settings['img_bdr_color'];
				$name_font_size         = $Weblizar_Settings['name_font_size'];
				$name_Color             = $Weblizar_Settings['name_Color'];
				$weblink_font_size      = $Weblizar_Settings['weblink_font_size'];
				$weblink_text_color     = $Weblizar_Settings['weblink_text_color'];
				$dis_font_size          = $Weblizar_Settings['dis_font_size'];
				$dis_text_color         = $Weblizar_Settings['dis_text_color'];
				$name_font_family       = $Weblizar_Settings['PGPP_Font_Style'];
				$About_me_social_color  = $Weblizar_Settings['About_me_social_color'];
				$About_me_custom_css    = $Weblizar_Settings['About_me_custom_css'];
				$Tem_pl_at_e            = $Weblizar_Settings['Tem_pl_at_e'];
				$Social_icon_size       = $Weblizar_Settings['Social_icon_size'];
				$Us_sr_H_der_img_Witdh  = "100";
				$Us_sr_H_der_img_High   = "100";
				$my_hea_der_im_g        = " ";
				if ($bodr == true ) {
					if($bodr=='1') {
						$my_bodr="border-radius:50% 50% 50% 50%";
					}
					if($bodr=='2') {
						$my_bodr="border-radius:10% 50% 10% 50%";
					}
				}
				if($Tem_pl_at_e=='11') {
					include("shortcode-files/template1-shortcode.php");
				}
				if($Tem_pl_at_e=='12') {
					include("shortcode-files/template2-shortcode.php");
				}
			  }	// end of foreach
		 }// end of is_array
		endwhile;
	} else {
		echo "<div align='center' class='alert alert-danger'>". esc_html_e("Sorry! Invalid About me Shortcode Embedded", 'WEBLIZAR_ABOUT_DOMAIN' )."</div>";
	}
	wp_reset_query();
	return ob_get_clean();
}?>