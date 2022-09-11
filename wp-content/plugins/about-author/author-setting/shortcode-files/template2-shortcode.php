<?php
$Weblizar_Settings = unserialize(get_option('author_info_Settings'));
if(count($Weblizar_Settings[0]))
{
	$Author_short_code = $Weblizar_Settings[0]['Author_short_code'];
	$switch_off_name = $Weblizar_Settings[0]['switch_off_name'];
	$switch_off_bio_info = $Weblizar_Settings[0]['switch_off_bio_info'];
	$switch_off_web = $Weblizar_Settings[0]['switch_off_web'];
	$switch_off_bio_info = $Weblizar_Settings[0]['switch_off_bio_info'];
	$switch_off_page = $Weblizar_Settings[0]['switch_off_page'];
	$switch_off_post = $Weblizar_Settings[0]['switch_off_post'];
	$auther_lbl_text = $Weblizar_Settings[0]['auther_lbl_text'];
	$Author_bg_color = $Weblizar_Settings[0]['Author_bg_color'];
	$Author_Color = $Weblizar_Settings[0]['Author_Color'];
	$auther_lbl_text_font = $Weblizar_Settings[0]['auther_lbl_text_font'];
	$Author_PGPP_Font_Style = $Weblizar_Settings[0]['Author_PGPP_Font_Style'];
	if( isset($Weblizar_Settings[0]['Author_short_code']) && ! empty( $Weblizar_Settings[0]['Author_short_code'] )) {
		$Author_all_data = unserialize(get_post_meta($Author_short_code , 'weblizar_Settings_'.$Author_short_code)[0])[0];
	}
	extract( $Author_all_data );
}
?>
<?php 
wp_register_style( 'temp2-page-short-style', false );
wp_enqueue_style( 'temp2-page-short-style' );
$css = " ";
ob_start(); ?>
	.menu li a:before {
		/*font-family: FontAwesome;*/
		speak: none;
		text-indent: 0em;
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}
	#Web_lizar_main_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_main_div
	{
		width:auto;
		height:auto;
		border:1px solid #ccc;
		background-color:<?php echo esc_attr($About_me_bg_color);?>;
		overflow: hidden;
	}

	#Web_lizar_img_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_image_div
	{
		position: relative;
		left:0;
		top:0;
		padding-top:10px;
		background-image: url('<?php echo esc_url($user_header_image); ?>');
		background-size: <?php echo esc_attr($Us_sr_H_der_img_Width); ?>% <?php echo esc_attr($Us_sr_H_der_img_High); ?>%;
		background-repeat:no-repeat;
		height:150px;
		width:100%;
	}
	#Web_lizar_img_<?php echo esc_attr($p_o_s_t); ?> img
	{
		<?php echo esc_attr($my_bodr); ?>;
		width:130px;
		height:130px;
		border:<?php echo esc_attr($bdr_size),"px"," ",esc_attr($img_bdr_type)," ",esc_attr($img_bdr_color); ?>;
		position:relative;
		top:65px;
		float: none;
		margin: 0 0em 0em 0;
		padding: 0px !important;
	}
	#Web_lizar_name_div_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_user_name_div
	{

	}
	#Web_lizar_name_div_<?php echo esc_attr($p_o_s_t); ?>	h3.name_user
	{
		font-size:<?php echo esc_attr($name_font_size); ?>px;
		font-family:<?php echo esc_attr($name_font_family); ?> !important;
		color:<?php echo esc_attr($name_Color); ?>;
		margin-bottom: 0px;
		text-align: center;
	    font-weight: normal;
	}

	#Web_lizar_discription_div_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_discription_div
	{
		margin-top:25px;
		padding-left: 10px;
		padding-right: 10px;
	}
	#Web_lizar_discription_div_<?php echo esc_attr($p_o_s_t); ?> p
	{
		color:<?php echo esc_attr($dis_text_color); ?>;
		font-size:<?php echo esc_attr($dis_font_size); ?>px;
		font-family:<?php echo esc_attr($name_font_family); ?>;
		margin-bottom: 0em;
		word-wrap:break-word;
		text-align: center !important;
		line-height: normal !important;
	}
	#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_social_icon_div_use
	{
		margin-top:25px;
		padding-left: 10px;
		padding-right: 10px;
		height: auto;
	}
	#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?> a>span.web_lizar_Social_icon
	{
		color:<?php echo esc_attr($About_me_social_color); ?> !important;
		margin: 5px;
		font-size:<?php echo esc_attr($Social_icon_size);?>px;
	}
	#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?> a
	{
		border-bottom: 0;
	}
	#Web_lizar_web_link_div_auther<?php echo esc_attr($p_o_s_t); ?>
	{
		margin-top:25px;
	}

	#Web_lizar_web_link_div_auther<?php echo esc_attr($p_o_s_t); ?> a.web_lizar_web_link
	{
		color:<?php echo esc_attr($weblink_text_color); ?> !important;
		font-size:<?php echo esc_attr($weblink_font_size);?>px;
		font-family:<?php echo esc_attr($name_font_family); ?>;
		border-bottom: 0;
		word-wrap:break-word;
	}
	#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?> a.icon
	{
		text-decoration:none;
		box-shadow: none !important;
	}
	#Web_lizar_web_link_div_auther<?php echo esc_attr($p_o_s_t) ; ?> a.icon
	{
		text-decoration:none;
		box-shadow: none !important;
	}
	#Web_lizar_info_container_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_info_cotainer
	{
		margin-top:75px;
		margin-bottom: 40px;
	}
	#auther_bio_info.weblizar_auther_bio_info
	{
		color:<?php echo esc_attr($Author_Color); ?> !important;
		font-size:<?php echo esc_attr($auther_lbl_text_font);?>px;
		font-family:<?php echo esc_attr($Author_PGPP_Font_Style); ?> !important;
		background-color:<?php echo esc_attr($Author_bg_color);?>;
		margin-bottom: 20px;
		text-align: center;
		height:auto;
		padding: 10px;
        line-height: normal;
	}
<?php
$css .= ob_get_clean();
wp_add_inline_style( 'temp2-page-short-style', $css ); ?>

<?php 
wp_register_style( 'temp2-page-short-style1', false );
wp_enqueue_style( 'temp2-page-short-style1' );
$css = " ";
ob_start(); ?>
	<?php echo esc_attr($About_me_custom_css); ?>
<?php
$css .= ob_get_clean();
wp_add_inline_style( 'temp2-page-short-style1', $css ); ?>

<h1 id="auther_bio_info"  class="weblizar_auther_bio_info" align="center"><?php esc_html_e($auther_lbl_text); ?></h1>
<div class="web_lizar_main_div" id="Web_lizar_main_<?php echo esc_attr($p_o_s_t); ?>" >
	<div align="center" class="web_lizar_image_div" id="Web_lizar_img_<?php echo esc_attr($p_o_s_t); ?>" ><?php  $user_info = get_userdata($user_ID );  $pro_fi_le=$user_info->user_email; ?> <?php  echo get_avatar( $pro_fi_le, 130 );  ?>
	</div>
	<div id="Web_lizar_info_container_<?php echo esc_attr($p_o_s_t); ?>" class="web_lizar_info_cotainer" >
		<?php if($switch_off_name=='yes') {	?>
		<div align="center" class="web_lizar_user_name_div" id="Web_lizar_name_div_<?php echo esc_attr($p_o_s_t); ?>" >
			<h3 class="name_user"><?php esc_html_e($About_me_user_name); ?></h3>
		</div>
		<?php  } ?>

		<?php if($switch_off_bio_info=='yes') {	?>
		<div align="center" class="web_lizar_discription_div" id="Web_lizar_discription_div_<?php echo esc_attr($p_o_s_t); ?>"  > <p><?php esc_html_e($About_me_dis_cription); ?></p>
		</div>
		<?php  } ?>

		<div class="web_lizar_social_icon_div_use" id="Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?>"  align="center">
			<?php if($followfb !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followfb); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-facebook web_lizar_Social_icon"></span>
				</a>
			<?php } ?>

			<?php if($followinsta !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followinsta); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-instagram web_lizar_Social_icon" ></span>
				</a>
			<?php } ?>

			<?php if($followlinkdln !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followlinkdln); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-linkedin web_lizar_Social_icon">
					</span>
				</a>
			<?php } ?>

			<?php if($followpint !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followpint); ?>" target="_blank" style="text-decoration: none;">
					<span  class="fab fa-pinterest web_lizar_Social_icon" >
					</span>
				</a>
			<?php } ?>

			<?php if($followtwit !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followtwit); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-twitter web_lizar_Social_icon">
					</span>
				</a>
			<?php } ?>
		</div>

		<?php if($switch_off_web =='yes') {	?>
		<div align="center"  id="Web_lizar_web_link_div_auther<?php echo esc_attr($p_o_s_t); ?>" ><a  target="_blank"  class="web_lizar_web_link" href="<?php echo esc_url($About_me_web_site_name); ?>"><?php esc_html_e($About_me_web_site_name); ?></a>
		</div>
		<?php  }?>
	</div>
</div>