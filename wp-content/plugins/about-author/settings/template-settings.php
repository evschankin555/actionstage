<?php
$postid=$post->ID;
$weblizar_Settings = "weblizar_Settings_".$postid;
$Weblizar_Settings = unserialize(get_post_meta( $post->ID,  $weblizar_Settings, true));
if(isset($Weblizar_Settings[0])) {
	$Tem_pl_at_e = $Weblizar_Settings[0]['Tem_pl_at_e'];
} else {
	$Tem_pl_at_e = "11";
}
?>
<?php 
wp_register_script( 'tempplate-settings-script', false );
wp_enqueue_script( 'tempplate-settings-script' );
$js = " ";
ob_start(); ?>	
function dis_play_ed(vol) {
	if(vol=="11") {
		jQuery("#t_m_p_l_1").show();
		jQuery("#t_m_p_l_2").hide();
		jQuery("#show_di_v").hide();
		jQuery("tr.co_lo_hi_d").show();
	} else if(vol=="12") {
		jQuery("#t_m_p_l_2").show();
		jQuery("#t_m_p_l_1").hide();
		jQuery("#show_di_v").hide();
		jQuery("tr.co_lo_hi_d").show();
	}
}
<?php
$js .= ob_get_clean();
wp_add_inline_script( 'tempplate-settings-script', $js ); ?>

<?php 
wp_register_style( 'template-settings-style', false );
wp_enqueue_style( 'template-settings-style' );
$css = " ";
ob_start(); ?>

.lbl_temp {
	font-size: 15px;
	font-family: Courier New;
	margin-right: 0px;
	font-weight: bold;
}
label > input {
	display:none;
}
label > input + span {
	cursor:pointer;
	border:5px solid transparent;
}
label > input:checked + span {
	display:inline;
	background: #2580a2 url("<?php echo WEBLIZAR_ABOUT_ME_PLUGIN_URL.'settings/images/hover.png'; ?>") right center no-repeat;
	padding-right: 30px;
	border: 3px solid #000;
	padding-top: 10px;
	padding-bottom: 10px;
	padding-left: 30px;
}
li {
	padding-bottom:10px;
	color: #fff;
	margin: 0;
	padding: 12px 0px;
}
#temp_menu {
	font-weight: bolder;
}
#cssmenu {
	background: #333;
	list-style: none;
	margin: 0;
	padding: 0;
	float:left;
	height: auto;
	width: auto;
	margin-right: 150px;
}
<?php
$css .= ob_get_clean();
wp_add_inline_style( 'template-settings-style', $css ); ?>

<div  id='cssmenu' align="center" >
	<ul id="temp_menu">
		<li>
			<label class="lbl_temp arrow-left ">
				<input id="Tem_pl_at_e"name="Tem_pl_at_e" type="radio" value="11"  onclick=" dis_play_ed(this.value);"  <?php checked( '11',$Tem_pl_at_e ); ?> style="display:none;" />
				<span><?php esc_html_e('Template 1', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></span>
			</label>
		</li>

		<li>
			<label class="lbl_temp">
				<input id="Tem_pl_at_e" name="Tem_pl_at_e" type="radio" value="12" onclick="dis_play_ed(this.value);"   <?php checked( '12',$Tem_pl_at_e ); ?> style="display:none;"/>
				<span><?php esc_html_e('Template 2', 'WEBLIZAR_ABOUT_DOMAIN' ); ?></span>
			</label>
		</li>
	</ul>
</div>

<div id="t_m_p_l_1">
	<?php
	include("template1.php"); ?>
</div>

<div id="t_m_p_l_2">
	<?php
	include("template2.php"); ?>
</div>

<div id="show_di_v">
	<?php
	if($Tem_pl_at_e=='11') {
		include("template1.php");
	} elseif($Tem_pl_at_e=='12') {
		include("template2.php");
	}
	?>
</div>
<?php 
wp_register_script( 'tempplate-settings-script2', false );
wp_enqueue_script( 'tempplate-settings-script2' );
$js = " ";
ob_start(); ?>	
	jQuery("#t_m_p_l_1").hide();
	jQuery("#t_m_p_l_2").hide();
<?php
$js .= ob_get_clean();
wp_add_inline_script( 'tempplate-settings-script2', $js ); ?>