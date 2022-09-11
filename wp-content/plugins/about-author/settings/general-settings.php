<?php
$postid=$post->ID;
$weblizar_Settings = "weblizar_Settings_".$postid;
$Weblizar_Settings = unserialize(get_post_meta($postid, $weblizar_Settings, true));
if(isset($Weblizar_Settings[0])) {

	if(isset($Weblizar_Settings[0]['About_me_bg_color'])){
		$About_me_bg_color = $Weblizar_Settings[0]['About_me_bg_color'];
	}else{
		$About_me_bg_color = "#dd3333";
	}

	if(isset($Weblizar_Settings[0]['About_me_user_name'])){
		$About_me_user_name = $Weblizar_Settings[0]['About_me_user_name'];
	}else{
		$About_me_user_name = "Weblizar";
	}

	if(isset($Weblizar_Settings[0]['About_me_web_site_name'])){
		$About_me_web_site_name = $Weblizar_Settings[0]['About_me_web_site_name'];
	}else{
		$About_me_web_site_name = "";
	}

	if(isset($Weblizar_Settings[0]['About_me_web_site_url'])){
		$About_me_web_site_url = $Weblizar_Settings[0]['About_me_web_site_url'];
	}else{
		$About_me_web_site_url = "";
	}

	if(isset($Weblizar_Settings[0]['About_me_dis_cription'])){
		$About_me_dis_cription = $Weblizar_Settings[0]['About_me_dis_cription'];
	}else{
		$About_me_dis_cription = "";
	}

	if(isset($Weblizar_Settings[0]['followpint'])){
		$followpint = $Weblizar_Settings[0]['followpint'];
	}else{
		$followpint = "";
	}

	if(isset($Weblizar_Settings[0]['About_me_social_color'])){
		$About_me_social_color = $Weblizar_Settings[0]['About_me_social_color'];
	}else{
		$About_me_social_color = "";
	}

	if(isset($Weblizar_Settings[0]['About_me_custom_css'])){
		$About_me_custom_css = $Weblizar_Settings[0]['About_me_custom_css'];
	}else{
		$About_me_custom_css = "";
	}

	$followfb = $Weblizar_Settings[0]['followfb'];
	$followinsta = $Weblizar_Settings[0]['followinsta'];
	$followlinkdln = $Weblizar_Settings[0]['followlinkdln'];
	$followtwit = $Weblizar_Settings[0]['followtwit'];
	$bodr = $Weblizar_Settings[0]['bodr'];
	$img_bdr_type = $Weblizar_Settings[0]['img_bdr_type'];
	$bdr_size = $Weblizar_Settings[0]['bdr_size'];
	$img_bdr_color = $Weblizar_Settings[0]['img_bdr_color'];
	$name_font_size = $Weblizar_Settings[0]['name_font_size'];
	$name_Color = $Weblizar_Settings[0]['name_Color'];
	$weblink_font_size = $Weblizar_Settings[0]['weblink_font_size'];
	$weblink_text_color = $Weblizar_Settings[0]['weblink_text_color'];
	$dis_font_size = $Weblizar_Settings[0]['dis_font_size'];
	$dis_text_color = $Weblizar_Settings[0]['dis_text_color'];
	$PGPP_Font_Style = $Weblizar_Settings[0]['PGPP_Font_Style'];
	$Tem_ous = $Weblizar_Settings[0]['Tem_pl_at_e'];
	$Social_icon_size = $Weblizar_Settings[0]['Social_icon_size'];
}
?>

<?php 
wp_register_script( 'general-settings-script', false );
wp_enqueue_script( 'general-settings-script' );
$js = " ";
ob_start(); ?>	
	jQuery(document).ready(function()
	{
		jQuery('.my-color-picker').wpColorPicker();
	});
	function outputUpdate(vol)
	{
		jQuery("span.volum").text(vol);
	}
	function outputUpdate2(vol2)
	{
		jQuery("span.volum2").text(vol2);
	}
	function outputUpdate3(vol3)
	{
		jQuery("span.volum3").text(vol3);
	}
	function outputUpdate4(vol4)
	{
		jQuery("span.volum4").text(vol4);
	}
	function Social_icon_size_outputUpdate(vol5)
	{
		jQuery("span.volum5").text(vol5);
	}
<?php
$js .= ob_get_clean();
wp_add_inline_script( 'general-settings-script', $js ); ?>
<?php
if(!isset($Tem_ous)) { $Tem_ous = "11"; }
if($Tem_ous =='11' || $Tem_ous =='12') {
	$d_n_t="";
} else  {
	$d_n_t= 'display:none;';
}
?>
<table class="form-table aap_layout_setting"  >
	<tr>
		<td colspan="2">
			<label class="lbl "><?php esc_html_e('Profile Image Layout', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
	    </td>
	</tr>
	<tr>
		<th colspan="2">
			<label>
				<?php if(!isset($bodr)) { $bodr = "1"; }?>
				<input id="bodr" name="bodr" type="radio" value="1"  <?php checked( '1', $bodr ); ?> style="display:none;"/>
				<img src="<?php echo WEBLIZAR_ABOUT_ME_PLUGIN_URL.'settings/images/pic2.png'; ?>"  />
			</label>
			<label>
				<input id="bodr" name="bodr" type="radio" value="3" <?php checked( '3', $bodr ); ?> style="display:none;"/>
				<img src="<?php echo WEBLIZAR_ABOUT_ME_PLUGIN_URL.'settings/images/pic3.png'; ?>"/>
			</label>
		</th>
	</tr>
	<tr>
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('Profile Image Layout settings','WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</td>
	</tr>
	<tr>
		<th>
			<label><?php esc_html_e('Border style', 'WEBLIZAR_ABOUT_DOMAIN'); ?></label>
		</th>
		<td> <?php if(!isset($img_bdr_type)) { 	$img_bdr_type = "solid"; }?>
			<select name="img_bdr_type" id="img_bdr_type">
				<?php  $options_bdr_type = array('none','solid');
				foreach ($options_bdr_type as $option_bdr_type_img) {
					echo '<option
					value="' . esc_attr($option_bdr_type_img) . '"
					id="' . esc_attr($option_bdr_type_img) . '"',
					$img_bdr_type == $option_bdr_type_img ? ' selected="selected"' : '', '>',
					$option_bdr_type_img, '</option>';
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Border size', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<input type="range" class="slider" min="1" max="10" step="1" value="<?php if(!isset($bdr_size)) {	echo esc_attr($bdr_size = "5"); } else {	echo esc_attr($bdr_size);} ?>" data-orientation="vertical" id="bdr_size"name="bdr_size"  oninput="outputUpdate4(value);" />
			<span id="img_bdr_span_value" name="img_bdr_span_value" class="volum4" ><?php esc_html_e($bdr_size);?><span>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Border Color', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<p>
				<input class="my-color-picker" id="img_bdr_color" name="img_bdr_color" type="text"  value="<?php if(!isset($img_bdr_color)) { 	echo esc_attr($img_bdr_color = "#ffffff"); } else	{ 	echo esc_attr($img_bdr_color); }?>">
		    </p>
		</td>
	</tr>
	<tr class="co_lo_hi_d" style="<?php echo esc_attr($d_n_t); ?>">
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('BackgroundColor option', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</td>
	</tr>

	<tr class="co_lo_hi_d" style=" <?php echo esc_attr($d_n_t); ?>" >
		<th><label ><?php esc_html_e('BackgroundColor', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<p>
				<input  type="text" class="my-color-picker" id="About_me_bg_color" name="About_me_bg_color" value="<?php if(!isset($About_me_bg_color)) {echo esc_attr($About_me_bg_color = "#dd3333"); } else { echo esc_attr($About_me_bg_color); }?>"/>
			<p>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('Name Text Settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</td>
	</tr>
	<tr>
		<th><label> <?php esc_html_e('Name Text', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<input class="widefat"  id="About_me_user_name" name="About_me_user_name" type="text" value="<?php if(!isset($About_me_user_name)) { echo esc_attr($About_me_user_name = "About_me_web_site_name "); } else { echo esc_attr($About_me_user_name); } ?>" />
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Font size', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<input class="slider" type="range" min="12" max="40" step="1" value="<?php if(!isset($name_font_size)) { echo esc_attr($name_font_size = "20"); } else { echo esc_attr($name_font_size); } ?>" data-orientation="vertical" id="name_font_size"name="name_font_size"  oninput="outputUpdate(value);" /> <span id="name_set_span_value" name="name_set_span_value" class="volum" ><?php esc_html_e($name_font_size);?><span>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Color', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<p>
				<input class="my-color-picker" id="name_Color" name="name_Color" type="text"  value="<?php 	if(!isset($name_Color)) { echo esc_attr($name_Color = "#ffffff"); } else { echo esc_attr($name_Color); } ?>">
			</p>
		</td>
	</tr>

	<tr><td colspan="2"><label class="lbl"><?php esc_html_e('Website Text Settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></td></tr>
	<tr>
		<th><label> <?php esc_html_e('Website Name Text', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td><input class="widefat" id="About_me_web_site_name"  name="About_me_web_site_name" type="text" value="<?php if(!isset($About_me_web_site_name))  { echo esc_attr($About_me_web_site_name = "About_me_web_site_name"); } else { echo esc_attr($About_me_web_site_name); } ?>" /></td>
	</tr>
	<tr>
		<th><label> <?php esc_html_e('Website url', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<input class="widefat" id="About_me_web_site_url"  name="About_me_web_site_url" type="text" value="<?php if(!isset($About_me_web_site_url))  { echo esc_attr($About_me_web_site_url = "http://www.About_me_web_site_name .com"); } else { echo esc_attr($About_me_web_site_url); } ?>" />
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Font size', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td><input type="range" class="slider" min="12" max="40" step="1" value="<?php if(!isset($weblink_font_size))  { echo esc_attr($weblink_font_size = "20"); } else { echo esc_attr($weblink_font_size); } ?>" data-orientation="vertical" id="weblink_font_size" name="weblink_font_size"   oninput="outputUpdate2(value);"> <span id="weblink_set_span_value"name="weblink_set_span_value"class="volum2" ><?php esc_html_e($weblink_font_size);?><span>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Website Link Color', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td><input class="my-color-picker" id="weblink_text_color" name="weblink_text_color" type="text"  value="<?php if(!isset($weblink_text_color)) { echo esc_attr($weblink_text_color = "#ffffff"); } else { echo esc_attr($weblink_text_color); } ?>"></td>
	</tr>
	<tr>
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('Description Text Settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</td>
	</tr>
	<tr>
		<th><label> <?php esc_html_e('Description Text', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>
			<textarea class="widefat" id="About_me_dis_cription" name="About_me_dis_cription" maxlength="325" style="height:160px;"><?php if(!isset($About_me_dis_cription)) {	echo htmlentities($About_me_dis_cription = "About_me_web_site_name  Creators of Premium WordPress Minimalist WordPress Themes For Creatives"); } else { echo htmlentities($About_me_dis_cription);}?>
	        </textarea>
			<p class="description"><b><?php esc_html_e('Note: Maximum words for Description are 325.','WEBLIZAR_ABOUT_DOMAIN')?><b></p>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Font size', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td><input class="slider" type="range" min="12" max="40" step="1" value="<?php if(!isset($dis_font_size)) { echo esc_attr($dis_font_size = "20"); } else { echo esc_attr($dis_font_size); } ?>" data-orientation="vertical" id="dis_font_size" name="dis_font_size" oninput="outputUpdate3(value);"> <span id="dis_set_span_value" name="dis_set_span_value" class="volum3" ><?php esc_html_e($dis_font_size);?><span>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Description Text Color', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td><input class="my-color-picker" id="dis_text_color" name="dis_text_color" type="text"  value="<?php if(!isset($dis_text_color)) { echo esc_attr($dis_text_color = "#ffffff"); } else { echo esc_attr($dis_text_color); } ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('Social link Settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</td>
	</tr>
	<tr>
		<th><label><a target="_blank" style="text-decoration: none;"><i class="fab fa-facebook web_lizar_Social_icon"></i></a><?php esc_html_e('Facebook', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </label></th>
		<td><input class="widefat" id="followfb" name="followfb" type="text" value="<?php if(!isset($followfb)) { echo esc_attr($followfb = "https://www.facebook.com/Weblizar-1440510482872657/"); } else { echo esc_attr($followfb); } 	?>" />
		</td>
	</tr>
	<tr>
		<th> <label> <a target="_blank" style="text-decoration: none;"><i class="fab fa-twitter web_lizar_Social_icon"></i></a><?php esc_html_e('Twitter', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </label></th>
		<td><input class="widefat" id="followtwit" name="followtwit" type="text"  value="<?php if(!isset($followtwit)) { echo	esc_attr($followtwit = "https://twitter.com/weblizar"); } else { echo esc_attr($followtwit); } ?>"/>
		</td>
	</tr>

	<tr>
		<th><label><a target="_blank" style="text-decoration: none;"><i class="fab fa-linkedin web_lizar_Social_icon"></i></a><?php esc_html_e('LinkedIn', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </label></th>
		<td><input class="widefat" id="followlinkdln" name="followlinkdln" type="text"  value="<?php if(!isset($followlinkdln)) { echo esc_attr($followlinkdln = "https://in.linkedin.com/in/weblizar"); 	}	else	{ echo esc_attr($followlinkdln); } ?>" />
		</td>
	</tr>
	<tr>
		<th><label><a target="_blank" style="text-decoration: none;"><i class="fab fa-pinterest web_lizar_Social_icon"></i></a><?php esc_html_e('Pinterest', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </label></th>
		<td><input class="widefat" id="followpint" name="followpint" type="text"  value="<?php if(!isset($followpint)) { echo esc_attr($followpint = "https://in.pinterest.com/"); 	}	else	{ echo esc_attr($followpint); } ?>" />
		</td>
	</tr>

	<tr>
		<th><label><a target="_blank" style="text-decoration: none;"> <i class="fab fa-instagram web_lizar_Social_icon" ></i></a><?php esc_html_e('Instagram', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </label>
		</th>
		<td><input class="widefat" id="followinsta" name="followinsta" type="text" value="<?php if(!isset($followinsta)) { echo esc_attr($followinsta = "https://www.instagram.com/?hl=en"); } else { echo esc_attr($followinsta); }?>" />
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('Social Link Color', 'WEBLIZAR_ABOUT_DOMAIN' );?></label>
		</td>
	</tr>
	<tr>
		<th><label> <?php esc_html_e('Color', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label> </th>
		<td>
			<p>
				<input  type="text" class="my-color-picker" id="About_me_social_color" name="About_me_social_color" value="<?php if(!isset($About_me_social_color)) { echo	esc_attr($About_me_social_color = "#ffffff"); } else { echo esc_attr($About_me_social_color); } ?>"/>
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('Social icon size', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Size', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label> </th>
		<td><input class="slider" type="range" min="15" max="40" step="1" value="<?php if(!isset($Social_icon_size)) { echo esc_attr($Social_icon_size = "20"); } else { echo esc_attr($Social_icon_size); } ?>" data-orientation="vertical" id="Social_icon_size"name="Social_icon_size"  oninput="Social_icon_size_outputUpdate(value);" /> <span id="Social_icon_set_span_value" name="Social_icon_set_span_value" class="volum5" ><?php esc_html_e($Social_icon_size);?><span>
		</td>
	</tr>

	<tr>
		<td colspan="2">
			<label class="lbl"><?php esc_html_e('Font Family', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</td>
	</tr>
	<tr>
		<th><label><?php esc_html_e('Font Family', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
		<td>	<?php if(!isset($PGPP_Font_Style)) { $PGPP_Font_Style = "Courier New"; }?>
			<select  name="PGPP_Font_Style" id="PGPP_Font_Style" class="standard-dropdown" >
				<optgroup label="Default Fonts">
					<option value="Arial" <?php selected($PGPP_Font_Style, 'Arial' ); ?>> <?php esc_html_e( 'Arial', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Arial Black" <?php selected($PGPP_Font_Style, 'Arial Black' ); ?>> <?php esc_html_e( 'Arial Black', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Courier New" <?php selected($PGPP_Font_Style, 'Courier New' ); ?>><?php esc_html_e( 'Courier New ', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="cursive" <?php selected($PGPP_Font_Style, 'cursive' ); ?>> <?php esc_html_e( 'Cursive', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="fantasy" <?php selected($PGPP_Font_Style, 'fantasy' ); ?>> <?php esc_html_e( 'Fantasy', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Georgia" <?php selected($PGPP_Font_Style, 'Georgia' ); ?>> <?php esc_html_e( 'Georgia', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Grande"<?php selected($PGPP_Font_Style, 'Grande' ); ?>> <?php esc_html_e( 'Grande', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Helvetica Neue" <?php selected($PGPP_Font_Style, 'Helvetica Neue' ); ?>> <?php esc_html_e( 'Helvetica Neue', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Impact" <?php selected($PGPP_Font_Style, 'Impact' ); ?>> <?php esc_html_e( 'Impact', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Lucida" <?php selected($PGPP_Font_Style, 'Lucida' ); ?>> <?php esc_html_e( 'Lucida', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Lucida Console"<?php selected($PGPP_Font_Style, 'Lucida Console' ); ?>> <?php esc_html_e( 'Lucida Console', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="monospace" <?php selected($PGPP_Font_Style, 'monospace' ); ?>> <?php esc_html_e( 'Monospace', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Open Sans" <?php selected($PGPP_Font_Style, 'Open Sans' ); ?>> <?php esc_html_e( 'Open Sans', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Palatino" <?php selected($PGPP_Font_Style, 'Palatino' ); ?>> <?php esc_html_e( 'Palatino', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="sans" <?php selected($PGPP_Font_Style, 'sans' ); ?>><?php esc_html_e( 'Sans', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="sans-serif" <?php selected($PGPP_Font_Style, 'sans-serif' ); ?>><?php esc_html_e( 'Sans-Serif', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Tahoma" <?php selected($PGPP_Font_Style, 'Tahoma' ); ?>> <?php esc_html_e( 'Tahoma', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Times New Roman"<?php selected($PGPP_Font_Style, 'Times New Roman' ); ?>> <?php esc_html_e( 'Times New Roman', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Trebuchet MS" <?php selected($PGPP_Font_Style, 'Trebuchet MS' ); ?>> <?php esc_html_e( 'Trebuchet MS', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
                    <option value="Verdana" <?php selected($PGPP_Font_Style, 'Verdana' ); ?>> <?php esc_html_e( 'Verdana', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
				</optgroup>
				<optgroup label="Google Fonts">
							<?php
	                            // fetch the Google font list
								$google_font_token = "AIzaSyBmvWUL5IR3vTH0pf5dTGOSP6iiXnNpfl4";
	                            $google_api_url = "https://www.googleapis.com/webfonts/v1/webfonts?key=$google_font_token";
	                           $response_font_api = wp_remote_retrieve_body( wp_remote_get($google_api_url, array('sslverify' => false )));
	                           if(!is_wp_error( $response_font_api ) ) {
	                                $fonts_list = json_decode($response_font_api,  true);
	                                // that's it
	                                if(is_array($fonts_list)) {
	                                	if(isset($fonts_list['items'])){
			                                    $g_fonts = $fonts_list['items'];
			                                    foreach( $g_fonts as $g_font) { $font_name = $g_font['family']; ?><option value="<?php echo esc_attr($font_name); ?>" <?php selected($PGPP_Font_Style, $font_name ); ?>><?php esc_html_e($font_name); ?></option><?php
			                                    }
		                                	}
		                            	} else {
		                                    echo "<option disabled>". esc_html_e("Error to fetch Google fonts.", 'WEBLIZAR_ABOUT_DOMAIN')."</option>";
		                                    echo "<option disabled>". esc_html_e("Google font will not available in offline mode.",'WEBLIZAR_ABOUT_DOMAIN')."</option>";
		                                }
	                            }
	                        ?>
						</optgroup>
			</select>
			<p class="description">
				<?php esc_html_e('Choose a caption font style.','WEBLIZAR_ABOUT_DOMAIN')?>
			</p>
		</td>
	</tr>
	<tr>
	<td colspan="2">
		<label class="lbl"><?php esc_html_e('Custom CSS', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></td>
	</tr>
	<tr>
		<th><label ><?php esc_html_e('Custom CSS', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label> </th>
		<td>
			<textarea class="widefat" id="About_me_custom_css" name="About_me_custom_css"><?php if(!isset($About_me_custom_css)) {	echo esc_textarea($About_me_custom_css = ""); } else { echo esc_textarea($About_me_custom_css);}?>
		    </textarea>
			<p class="description">
				<?php esc_html_e('Enter any custom css you want to apply on this shortcode.','WEBLIZAR_ABOUT_DOMAIN')?>
			</p>
			<p class="custnote"><?php esc_html_e( 'Note: Please Do Not Use ', 'WEBLIZAR_ABOUT_DOMAIN' ); ?><b><?php esc_html_e( 'Style', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></b><?php esc_html_e( 'Tag With Custom CSS', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></p>
		</td>
	</tr>
</table>

<?php 
wp_register_script( 'general-settings-script1', false );
wp_enqueue_script( 'general-settings-script1' );
$js = " ";
ob_start(); ?>	
	jQuery(document).ready(function($){
		jQuery(window).scroll(function(){
			if (jQuery(this).scrollTop() < 200) {
				jQuery('#smoothup') .fadeOut();
			} else {
				jQuery('#smoothup') .fadeIn();
			}
		});
		jQuery('#smoothup').on('click', function(){
			jQuery('html, body').animate({scrollTop:0}, 'fast');
			return false;
		});
	});

	jQuery(document).ready(function(){
		var editor = CodeMirror.fromTextArea(document.getElementById("About_me_custom_css"), {
			lineWrapping: true,
			lineNumbers: true,
			styleActiveLine: true,
			matchBrackets: true,
			hint:true,
			theme : 'blackboard',
			extraKeys: {"Ctrl-Space": "autocomplete"},
		});
	});
<?php
$js .= ob_get_clean();
wp_add_inline_script( 'general-settings-script1', $js ); ?>
<a href="#top" id="smoothup" title="Back to top"></a>
