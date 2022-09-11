<?php
add_shortcode( 'ABINFO', 'ABOUTMEUSER2' );
function ABOUTMEUSER2( $Id ) {
	wp_enqueue_style('font-awesome', WEBLIZAR_ABOUT_ME_PLUGIN_URL.'css/all.min.css');
	ob_start();

	if(isset($Id['id']))
	{

		/**
		 * Load About_me Custom Post Type
		 */
		$post_id=get_the_ID();
		$post_info = get_post( get_the_ID() );
		$author = $post_info->post_author;
		$Weblizar_CPT_Name = "about_author";
		$AllWeblizar = array(  'p' => $Id['id'], 'post_type' => $Weblizar_CPT_Name, 'orderby' => 'ASC', 'post_status' => 'publish');
		$loop = new WP_Query( $AllWeblizar );
		while ( $loop->have_posts() ) : $loop->the_post();

		$ID = get_the_ID();
		$weblizar_Settings = "weblizar_Settings_".$ID;
		$Weblizar_sets = unserialize(get_post_meta( $ID, $weblizar_Settings, true));
		foreach($Weblizar_sets as $Weblizar_Settings) {          
			$p_o_s_t=$ID;
			$profile_user_image= $Weblizar_Settings['profile_user_image'];
			$user_header_image = $Weblizar_Settings['user_header_image'];
			$About_me_bg_color= $Weblizar_Settings['About_me_bg_color'];
			$user_ID=$author ;
			$user_info = get_userdata($user_ID );
			$name=get_user_meta($user_ID ,'first_name',true);
			$last_name=get_user_meta($user_ID ,'last_name',true);
			$About_me_user_name= $name.' '.$last_name;
			$About_me_dis= get_user_meta($user_ID ,'description',true);
			$About_me_web_site_name = $user_info->user_url;
			$followbitbucket= get_user_meta($user_ID,'followbitbucket',true);
			$followdropbox = get_user_meta($user_ID,'followdropbox',true);
			$followfb = get_user_meta($user_ID,'followfb',true);
			$followflicker = get_user_meta($user_ID,'followflicker',true);
			$followgithub =get_user_meta($user_ID,'followgithub',true);
			$followinsta = get_user_meta($user_ID,'followinsta',true);
			$followlinkdln = get_user_meta($user_ID,'followlinkdln',true);
			$followpint = get_user_meta($user_ID,'followpint',true);
			$followtumbler = get_user_meta($user_ID,'followtumbler',true);
			$followtwit =get_user_meta($user_ID,'followtwit',true);
			$followtVk = get_user_meta($user_ID,'followtVk',true);
			$bodr =  $Weblizar_Settings['bodr'];
			$img_bdr_type =  $Weblizar_Settings['img_bdr_type'];
			$bdr_size =  $Weblizar_Settings['bdr_size'];
			$img_bdr_color =  $Weblizar_Settings['img_bdr_color'];
			$name_font_size =  $Weblizar_Settings['name_font_size'];
			$name_Color =  $Weblizar_Settings['name_Color'];
			$weblink_font_size =  $Weblizar_Settings['weblink_font_size'];
			$weblink_text_color =  $Weblizar_Settings['weblink_text_color'];
			$dis_font_size =  $Weblizar_Settings['dis_font_size'];
			$dis_text_color =  $Weblizar_Settings['dis_text_color'];
			$name_font_family = $Weblizar_Settings['PGPP_Font_Style'];
			$About_me_social_color = $Weblizar_Settings['About_me_social_color'];
			$About_me_custom_css = $Weblizar_Settings['About_me_custom_css'];
			$Tem_pl_at_e = $Weblizar_Settings['Tem_pl_at_e'];
			$Social_icon_size = $Weblizar_Settings['Social_icon_size'];
			$Us_sr_H_der_img_Width="100";
			$Us_sr_H_der_img_High="100";
			$my_hea_der_im_g=" ";
			$use_dis_cription=substr($About_me_dis, 0,325);
			$count_discription=strlen($About_me_dis);
			if($count_discription>325)
			{
				$About_me_dis_cription=$use_dis_cription.'......';
			}
			else
			{
				$About_me_dis_cription=$use_dis_cription;
			}
			if ($bodr == true )
			{
				if($bodr=='1')
				{
					$my_bodr="border-radius:50% 50% 50% 50%";
				}
				if($bodr=='2')
				{
					$my_bodr="border-radius:10% 50% 10% 50%";

				}
			}
			if($Tem_pl_at_e=='11') {
				include("shortcode-files/template1-shortcode.php");
			}
			if($Tem_pl_at_e=='12') {
				include("shortcode-files/template2-shortcode.php");
			} ?>

		<?php
		    }// end of foreach

		    endwhile;
		}
		else
		{
		 	echo "<div align='center' class='alert alert-danger'>". esc_html_e("Sorry! Invalid About me Shortcode Embedded", 'WEBLIZAR_ABOUT_DOMAIN' )."</div>";
		}
	wp_reset_query();
	return ob_get_clean();
}?>