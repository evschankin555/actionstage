<?php 
wp_register_style( 'temp1-post-short-style', false );
wp_enqueue_style( 'temp1-post-short-style' );
$css = " ";
ob_start(); ?>	
.menu span a:before {
	/*font-family: FontAwesome;*/
	speak: none;
	text-indent: 0em;
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
#Web_lizar_main_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_main_div  {
	width:auto;
	height:auto;
	border:1px solid #ccc;
	background-color:<?php echo esc_attr($About_me_bg_color);?>;
	overflow: hidden;
}
#Web_lizar_img_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_image_div {
	margin-top: 35px;
}
#Web_lizar_img_<?php echo esc_attr($p_o_s_t); ?> img.web_lizar_image_User {
	<?php echo esc_attr($my_bodr); ?>;
	border:<?php echo esc_attr($bdr_size),"px"," ",esc_attr($img_bdr_type)," ",esc_attr($img_bdr_color); ?>;
	width:130px;
	height:130px;
}
#Web_lizar_name_div_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_user_name_div {
	margin-top: 25px;
}
#Web_lizar_name_div_<?php echo esc_attr($p_o_s_t); ?> h3.name_user {
	color:<?php echo esc_attr($name_Color); ?>;
	font-size:<?php echo esc_attr($name_font_size); ?>px;
	font-family:<?php echo  esc_attr($name_font_family); ?> !important;
	margin-bottom: 0px;
	margin-top: 0px;
	word-wrap:break-word;
	float:none;
	border-bottom: 0;
	text-align: center;
	font-weight: normal;
}

#Web_lizar_discription_div_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_discription_div  {
	padding-left: 10px;
	padding-right: 10px;
	padding-bottom:0px !important;
	margin-top: 25px;
	word-wrap:break-word;
}

#Web_lizar_discription_div_<?php echo esc_attr($p_o_s_t); ?> p {
	color:<?php echo esc_attr($dis_text_color); ?>;
	font-size:<?php echo esc_attr($dis_font_size); ?>px;
	font-family:<?php echo esc_attr($name_font_family); ?>;
	margin-bottom: 0em;
	word-wrap:break-word;
	text-align: center !important;
	line-height: normal !important;
}
#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?>.web_lizar_social_icon_div_use {
	padding-left: 10px;
	padding-right: 10px;
	margin-top: 25px;
	height: auto;
}

#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?> a>span.web_lizar_Social_icon {
	color:<?php echo esc_attr($About_me_social_color); ?> !important;
	border-bottom: 0;
	font-size:<?php echo esc_attr($Social_icon_size);?>px;

}
#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?> a {
	border-bottom: 0;
}
#Web_lizar_web_link_div_<?php echo esc_attr($p_o_s_t); ?> {
	margin-top: 25px ;
	padding-bottom:40px;
}
#Web_lizar_web_link_div_<?php echo esc_attr($p_o_s_t); ?> a.web_lizar_web_link {
	color:<?php echo esc_attr($weblink_text_color); ?> !important;
	font-size:<?php echo esc_attr($weblink_font_size);?>px;
	font-family:<?php echo esc_attr($name_font_family); ?>;
	border-bottom: 0;
	word-wrap:break-word;
}
#Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?> a.icon {
	text-decoration:none;
	box-shadow: none !important;
	padding: 0 12px;
}
#Web_lizar_web_link_div_<?php echo esc_attr($p_o_s_t) ; ?> a.icon {
	text-decoration:none;
	box-shadow: none !important;
}
<?php
$css .= ob_get_clean();
wp_add_inline_style( 'temp1-post-short-style', $css ); ?>

<?php 
wp_register_style( 'temp1-post-short-style1', false );
wp_enqueue_style( 'temp1-post-short-style1' );
$css = " ";
ob_start(); ?>	
	<?php echo esc_attr($About_me_custom_css); ?>
<?php
$css .= ob_get_clean();
wp_add_inline_style( 'temp1-post-short-style1', $css ); ?>

<div class="web_lizar_main_div " id="Web_lizar_main_<?php echo esc_attr($p_o_s_t); ?>">
	<div align="center" class="web_lizar_image_div" id="Web_lizar_img_<?php echo esc_attr($p_o_s_t); ?>" >
		<img class="web_lizar_image_User" src= "<?php echo esc_url($profile_user_image); ?>" />
	</div>
	<div align="center" class="web_lizar_user_name_div" id="Web_lizar_name_div_<?php echo esc_attr($p_o_s_t); ?>" >
		<h3 class="name_user"><?php esc_html_e($About_me_user_name); ?></h3>
	</div>
	<div align="center" class="web_lizar_discription_div" id="Web_lizar_discription_div_<?php echo esc_attr($p_o_s_t); ?>">
		<p><?php esc_html_e($About_me_dis_cription); ?></p>
	</div>
	<?php
	if($followfb !=="" || $followinsta !=="" || $followlinkdln !=="" ||  $followtwit !=="") {  ?>
		<div class="web_lizar_social_icon_div_use" id="Web_lizar_social_icon_div_<?php echo esc_attr($p_o_s_t); ?>"  align="center">
			<?php if($followfb !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followfb); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-facebook web_lizar_Social_icon"></span>
				</a>
				<?php
			}
			?>

			<?php if($followinsta !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followinsta); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-instagram web_lizar_Social_icon" ></span>
				</a>
				<?php
			}
			?>
			<?php if($followlinkdln !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followlinkdln); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-linkedin web_lizar_Social_icon">
					</span>
				</a>
				<?php
			}
			?>
			<?php if($followtwit !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followtwit); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-twitter web_lizar_Social_icon">
					</span>
				</a>
				<?php
			}
			?>
			<?php if($followpint !=="") { ?>
				<a class="icon"  href="<?php echo esc_url($followpint); ?>" target="_blank" style="text-decoration: none;">
					<span class="fab fa-pinterest web_lizar_Social_icon">
					</span>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
	<div align="center"  id="Web_lizar_web_link_div_<?php echo esc_attr($p_o_s_t); ?>" ><a target="_blank" class="web_lizar_web_link" href="<?php echo esc_url($About_me_web_site_url); ?>"><?php esc_html_e($About_me_web_site_name); ?></a>
	</div>
</div>