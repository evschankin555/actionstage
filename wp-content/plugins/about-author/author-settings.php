<?php
if(isset($_POST['submit']) && isset($_POST['security'])) {
	if ( ! wp_verify_nonce( $_POST['security'], 'about_author_settings' ) ) {
		die();}	
	$Author_short_code      = sanitize_text_field($_POST['Author_short_code']);
	$switch_off_name        = sanitize_text_field($_POST['switch_off_name']);
	$switch_off_web         = sanitize_text_field($_POST['switch_off_web']);
	$switch_off_bio_info    = sanitize_text_field($_POST['switch_off_bio_info']);
	$switch_off_page        = sanitize_text_field($_POST['switch_off_page']);
	$switch_off_post        = sanitize_text_field($_POST['switch_off_post']);
	$auther_lbl_text        = stripslashes($_POST['auther_lbl_text']);
	$Author_bg_color        = sanitize_hex_color($_POST['Author_bg_color']);
	$Author_Color           = sanitize_hex_color($_POST['Author_Color']);
	$auther_lbl_text_font   = sanitize_text_field($_POST['auther_lbl_text_font']);
	$Author_PGPP_Font_Style = sanitize_text_field($_POST['Author_PGPP_Font_Style']);

	$Weblizar_io_Array[] = array(
		'Author_short_code'      => $Author_short_code,
		'switch_off_name'        => $switch_off_name,
		'switch_off_web'         => $switch_off_web,
		'switch_off_bio_info'    => $switch_off_bio_info,
		'switch_off_page'        => $switch_off_page,
		'switch_off_post'        => $switch_off_post,
		'auther_lbl_text'        => $auther_lbl_text,
		'Author_bg_color'        => $Author_bg_color,
		'Author_Color'           => $Author_Color,
		'auther_lbl_text_font'   => $auther_lbl_text_font,
		'Author_PGPP_Font_Style' => $Author_PGPP_Font_Style,
	);
	update_option('author_info_Settings', serialize($Weblizar_io_Array));
}

$Weblizar_io_settings = unserialize(get_option('author_info_Settings'));
if(  !empty( $Weblizar_io_settings ) && is_array($Weblizar_io_settings[0]) && count($Weblizar_io_settings[0])) {
	$Author_short_code      = $Weblizar_io_settings[0]['Author_short_code'];
	$switch_off_name        = $Weblizar_io_settings[0]['switch_off_name'];
	$switch_off_web         = $Weblizar_io_settings[0]['switch_off_web'];
	$switch_off_bio_info    = $Weblizar_io_settings[0]['switch_off_bio_info'];
	$switch_off_page        = $Weblizar_io_settings[0]['switch_off_page'];
	$switch_off_post        = $Weblizar_io_settings[0]['switch_off_post'];
	$auther_lbl_text        = $Weblizar_io_settings[0]['auther_lbl_text'];
	$auther_lbl_text_font   = $Weblizar_io_settings[0]['auther_lbl_text_font'];
	$Author_bg_color        = $Weblizar_io_settings[0]['Author_bg_color'];
	$Author_Color           = $Weblizar_io_settings[0]['Author_Color'];
	$Author_PGPP_Font_Style = $Weblizar_io_settings[0]['Author_PGPP_Font_Style'];
}
?>
<?php 
wp_register_script( 'about-settings-script', false );
wp_enqueue_script( 'about-settings-script' );
$js = " ";
ob_start(); ?>	
	jQuery(document).ready(function() {
		jQuery('input.my-color-picker').wpColorPicker();
	});
	function outputUpdate(vol) {
		jQuery("span.volum").text(vol);
	}
<?php
$js .= ob_get_clean();
wp_add_inline_script( 'about-settings-script', $js ); ?>

</script>

<div class="row-fluid pricing-table pricing-three-column">
	<form method="post" action="">
		<?php $nonce = wp_create_nonce( 'about_author_settings' ); ?>
                <input type="hidden" name="security" value="<?php echo esc_attr( $nonce ); ?>">
		<div class="plan-name">
			<h2 style=""><?php esc_html_e('Author Settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></h2>
		</div>
		<table class="form-table" style="margin-left:20px; width: 98%;"  >
			<tr><td colspan="2"><label class="lbl"> <?php esc_html_e('Display Author Settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></td></tr>
			<tr>
				<?php if(!isset($switch_off_page)) { $switch_off_page = "no"; }?>
				<th><?php esc_html_e('On page', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></th>
				<td>
					<input type="radio" name="switch_off_page" id="switch_off_page" value="yes" <?php checked( 'yes', $switch_off_page ); ?> ><?php esc_html_e('Yes', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
					<input type="radio" name="switch_off_page" id="switch_off_page" value="no" <?php checked( 'no', $switch_off_page ); ?>><?php esc_html_e('No', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
				</td>
			</tr>
				<?php if(!isset($switch_off_post)) { $switch_off_post = "no"; }?>
				<th><?php esc_html_e('On post', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></th>
				<td><input type="radio" name="switch_off_post" id="switch_off_post" value="yes" <?php checked( 'yes', $switch_off_post ); ?> ><?php esc_html_e('Yes', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
					<input type="radio" name="switch_off_post" id="switch_off_post" value="no" <?php checked( 'no', $switch_off_post ); ?>><?php esc_html_e('No', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></td>
				</tr>
				<tr>
					<td colspan="2"><label class="lbl"><?php esc_html_e('Select Template Style', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></td>
				</tr>
				<?php if(!isset($Author_short_code)) { $Author_short_code = "1"; }?>
				<?php	$Weblizar_CPT_Name = "about_author";
				$Weblizar_All_Posts = wp_count_posts( $Weblizar_CPT_Name )->publish;
				global $All_Weblizar;
				$All_Weblizar = array('post_type' => $Weblizar_CPT_Name, 'orderby' => 'ASC', 'posts_per_page' => $Weblizar_All_Posts);
				$All_Weblizar = new WP_Query( $All_Weblizar );
				?>
			<tr>
				<th><?php esc_html_e('Choose one', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></th>
				<td>
					<select id="Author_short_code" name="Author_short_code">
						<option value="1"> <?php esc_html_e( 'Select Any Shortcode', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
						<?php
						if( $All_Weblizar->have_posts() ) {	 ?>
							<?php
							while ( $All_Weblizar->have_posts() ) : $All_Weblizar->the_post();
							$PostId = get_the_ID();
							$PostTitle = get_the_title($PostId);
							?>
							<option value="<?php echo esc_attr($PostId); ?>" <?php if($Author_short_code==$PostId) echo 'selected="selected"'; ?>><?php if($PostTitle) esc_html_e($PostTitle); else esc_html_e("No Title", 'WEBLIZAR_ABOUT_DOMAIN'); ?></option>
							<?php endwhile; ?>
						<?php
							} else  {
								echo "<option>". esc_html_e("Sorry! No Author Shortcode Created Yet.", 'WEBLIZAR_ABOUT_DOMAIN') ."</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><label class="lbl"><?php esc_html_e('Display On Post OR Page', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Name', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></th>
				<?php if(!isset($switch_off_name)) { $switch_off_name = "no"; }?>
				<td><input type="radio" name="switch_off_name" id="switch_off_name" value="yes" <?php checked( 'yes', $switch_off_name ); ?> ><?php esc_html_e('Yes', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
				<input type="radio" name="switch_off_name" id="switch_off_name" value="no" <?php checked( 'no', $switch_off_name ); ?>><?php esc_html_e('No', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></td>
			</tr>
			<tr>
				<th><?php esc_html_e('Website Name', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></th>
				<td>
					<?php if(!isset($switch_off_web)) { $switch_off_web = "no"; }?>
					<input type="radio" name="switch_off_web" id="switch_off_web" value="yes" <?php checked( 'yes', $switch_off_web ); ?> ><?php esc_html_e('Yes', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
					<input type="radio" name="switch_off_web" id="switch_off_web" value="no" <?php checked( 'no', $switch_off_web ); ?> > <?php esc_html_e('No', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e('Biographical Info', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></th>
				<td>
					<?php if(!isset($switch_off_bio_info)) { $switch_off_bio_info = "yes"; }?>
					<input type="radio" name="switch_off_bio_info" id="switch_off_bio_info" value="yes" <?php checked( 'yes', $switch_off_bio_info ); ?>><?php esc_html_e('Yes', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
					<input type="radio" name="switch_off_bio_info" id="switch_off_bio_info" value="no" <?php checked( 'no', $switch_off_bio_info ); ?>><?php esc_html_e('No', 'WEBLIZAR_ABOUT_DOMAIN' ); ?>
				</td>
			</tr>
			<tr>
				<tr><td colspan="2"><label class="lbl"><?php esc_html_e('Author label settings', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></td></tr>
				<tr>
					<th><?php esc_html_e('Author label text', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></th>
					<?php if(!isset($auther_lbl_text)) { $auther_lbl_text = "Author Bio"; }?>
					<td>
						<input type="text" name="auther_lbl_text" id="auther_lbl_text" value="<?php echo esc_attr($auther_lbl_text); ?>"/>
					</td>
				</tr>

				<tr>
					<th><label><?php esc_html_e('Font size', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
					<td>
						<input  type="range" class="slider" min="12" max="32" step="1" value="<?php if(!isset($auther_lbl_text_font)) { echo  esc_attr($auther_lbl_text_font = "20"); } else { echo esc_attr($auther_lbl_text_font); } ?>" data-orientation="vertical" id="auther_lbl_text_font"name="auther_lbl_text_font"  oninput="outputUpdate(value);" />
						<span id="auther_lbl_text_font" name="auther_lbl_text_font" class="volum" ><?php esc_html_e($auther_lbl_text_font); ?><span>
					</td>
				</tr>
				<tr>
					<th><label ><?php esc_html_e('Background Color', 'WEBLIZAR_ABOUT_DOMAIN' ); ?> </label> </th>
					<td>
						<p><input  type="text" class="my-color-picker" id="Author_bg_color" name="Author_bg_color" value="<?php if(!isset($Author_bg_color)) {	echo esc_attr($Author_bg_color = "#dd3333"); 	} else { echo esc_attr($Author_bg_color); }?>"/><p>
					</td>
				</tr>
				<tr>
					<th><label><?php esc_html_e('Font Color', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
					<td><p><input class="my-color-picker" id="Author_Color" name="Author_Color" type="text"  value="<?php 	if(!isset($Author_Color)) { echo esc_attr($Author_Color = "#ffffff"); } else { echo esc_attr($Author_Color); } ?>"></p></td>
				</tr>
				<tr>
					<th><label><?php esc_html_e('Font Family', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label></th>
					<td><?php if(!isset($Author_PGPP_Font_Style)) { $Author_PGPP_Font_Style = "Courier New"; }?>
						<select  name="Author_PGPP_Font_Style" id="Author_PGPP_Font_Style" class="standard-dropdown" >
							<optgroup label="Default Fonts">
								<option value="Arial" <?php selected($Author_PGPP_Font_Style, 'Arial' ); ?>><?php esc_html_e( 'Arial', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Arial Black" <?php selected($Author_PGPP_Font_Style, 'Arial Black' ); ?>><?php esc_html_e( 'Arial Black', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Courier New" <?php selected($Author_PGPP_Font_Style, 'Courier New' ); ?>><?php esc_html_e( 'Courier New', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="cursive" <?php selected($Author_PGPP_Font_Style, 'cursive' ); ?>><?php esc_html_e( 'Cursive', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="fantasy" <?php selected($Author_PGPP_Font_Style, 'fantasy' ); ?>><?php esc_html_e( 'Fantasy', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Georgia" <?php selected($Author_PGPP_Font_Style, 'Georgia' ); ?>><?php esc_html_e( 'Georgia', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Grande"<?php selected($Author_PGPP_Font_Style, 'Grande' ); ?>><?php esc_html_e( 'Grande', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Helvetica Neue" <?php selected($Author_PGPP_Font_Style, 'Helvetica Neue' ); ?>><?php esc_html_e( 'Helvetica Neue', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Impact" <?php selected($Author_PGPP_Font_Style, 'Impact' ); ?>><?php esc_html_e( 'Impact', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Lucida" <?php selected($Author_PGPP_Font_Style, 'Lucida' ); ?>><?php esc_html_e( 'Lucida', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Lucida Console"<?php selected($Author_PGPP_Font_Style, 'Lucida Console' ); ?>><?php esc_html_e( 'Lucida Console', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="monospace" <?php selected($Author_PGPP_Font_Style, 'monospace' ); ?>><?php esc_html_e( 'Monospace', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Open Sans" <?php selected($Author_PGPP_Font_Style, 'Open Sans' ); ?>><?php esc_html_e( 'Open Sans', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Palatino" <?php selected($Author_PGPP_Font_Style, 'Palatino' ); ?>><?php esc_html_e( 'Palatino', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="sans" <?php selected($Author_PGPP_Font_Style, 'sans' ); ?>><?php esc_html_e( 'Sans', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="sans-serif" <?php selected($Author_PGPP_Font_Style, 'sans-serif' ); ?>><?php esc_html_e( 'Sans-Serif', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Tahoma" <?php selected($Author_PGPP_Font_Style, 'Tahoma' ); ?>> <?php esc_html_e( 'Tahoma', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Times New Roman"<?php selected($Author_PGPP_Font_Style, 'Times New Roman' ); ?>><?php esc_html_e( 'Times New Roman', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Trebuchet MS" <?php selected($Author_PGPP_Font_Style, 'Trebuchet MS' ); ?>><?php esc_html_e( 'Trebuchet MS', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
								<option value="Verdana" <?php selected($Author_PGPP_Font_Style, 'Verdana' ); ?>><?php esc_html_e( 'Verdana', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></option>
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
				                                    foreach( $g_fonts as $g_font) { $font_name = $g_font['family']; ?><option value="<?php echo esc_attr($font_name); ?>" <?php selected($Author_PGPP_Font_Style, $font_name ); ?>><?php esc_html_e($font_name); ?></option><?php 
				                                    }
			                                	} 
			                            	} else {
			                                    echo "<option disabled>". esc_html_e("Error to fetch Google fonts.", 'WEBLIZAR_ABOUT_DOMAIN') ."</option>";
			                                    echo "<option disabled>". esc_html_e("Google font will not available in offline mode.", 'WEBLIZAR_ABOUT_DOMAIN') ."</option>";
			                                }
		                            } 
		                        ?>
							</optgroup>	
						</select>
						<p class="description"><?php esc_html_e('Choose a caption font style.', 'WEBLIZAR_ABOUT_DOMAIN')?></p>
					</td>
				</tr>
			</tr>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="save" id="save" class="button-primary" style="font-size: 18px;width:7%;height:10%;"></td>
			</tr>
		</table>
	</form>
</div>