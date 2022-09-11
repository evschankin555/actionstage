<?php
$postid = $post->ID;
$weblizar_Settings = "weblizar_Settings_".$postid;
$Weblizar_Settings = unserialize(get_post_meta( $post->ID, $weblizar_Settings, true));
if(isset($Weblizar_Settings[0])) {
	$profile_user_image = $Weblizar_Settings[0]['profile_user_image'];
}
?>
<?php 
wp_register_style( 'tempplate1-style', false );
wp_enqueue_style( 'tempplate1-style' );
$css = " ";
ob_start(); ?>
	.text_in_put {
		width: 80%;
	}
	.tr-center  {
		text-align: center;
	}
<?php
$css .= ob_get_clean();
wp_add_inline_style( 'tempplate1-style', $css ); ?>

 <table>
 	<tr class="tr-center"><th><span style=" font-size: 30px;font-family: Courier New;"><?php esc_html_e('Template Style 1', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></span></th></tr>
 	<tr><th></th></tr>
 	<tr class="tr-center">
 		<td>
 			<img src="<?php echo WEBLIZAR_ABOUT_ME_PLUGIN_URL.'settings/images/temp11.png'; ?>" />
 		</td>
 	</tr>
 </table>
<table class="form-table">
	<tr>
		<th id="lbl_id">
			<label><?php esc_html_e('Upload profile Image', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></label>
		</th>
		<td id="In_put_id">
			<input type="text" class="text_in_put"  id="profile_user_image"  name="profile_user_image" placeholder="<?php esc_attr_e('No media selected!','WEBLIZAR_ABOUT_DOMAIN')?>" readonly name="upload_image"   value="<?php  if(!isset($profile_user_image)){ echo esc_url($profile_user_image =WEBLIZAR_ABOUT_ME_PLUGIN_URL.'settings/images/1.jpg'); } else { echo esc_url($profile_user_image);  } ?>"  />
			<input type="button" value="<?php esc_attr_e('Upload','WEBLIZAR_ABOUT_DOMAIN')?>"  class="button-primary upload_image_button"  />
			<img  src="<?php echo esc_url($profile_user_image); ?>"  width="150" height="150" style="margin:auto; margin-top:10px; display: inline-block; border: 4px double #000000; vertical-align: middle; border-radius: 22px" />
			<p class="description"><b> <?php esc_html_e('*Upload profile Image size should be 200*200 pixel maximum and 150*150 minimum', 'WEBLIZAR_ABOUT_DOMAIN' ); ?><b> </p>
		</td>
	</tr>
</table>