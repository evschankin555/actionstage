<?php $post_id = get_the_ID(); 
$tab_border_color = ColorDarken($settings['title_bg_color'],19);
$selected_tab_border_color = ColorDarken($settings['select_title_bg_color'],25);
$tab_content_border_color = ColorDarken($settings['tabs_descrip_bg_color'],25);
?>

				<style>				
					<?php 
					require('style.php');					
					?>
				</style>
				<div id="tab_container_<?php echo $post_id; ?>" >
	 
					<ul class="wpsm_nav wpsm_nav-tabs" role="tablist" id="myTab_<?php echo $post_id; ?>">
						<?php
						$i=1;
						$j=1;
						if ( $settings['tabs_list'] ) {
						foreach (  $settings['tabs_list'] as $index => $item )
						{
						?>	
							<li role="presentation" <?php if($i==1){ ?> class="active" <?php } ?> >
								<a href="#tabs_desc_<?php echo $post_id; ?>_<?php echo $i; ?>" aria-controls="tabs_desc_<?php echo $post_id; ?>_<?php echo $i; ?>" role="tab" data-toggle="tab" class="tabs-anchor-class">
									
											<?php if($settings['tabs_ic_pt_align']=="1"){ ?>
												<?php if($settings['dis_op_title_icon']=="1" || $settings['dis_op_title_icon']=="3") { ?>
													<?php if($item['tabs_responsive_show_above_icon']=="yes") { ?>	<i class="<?php echo implode(" ",$item['tabs_responsive_icon']); ?>"></i> <?php }?>
												<?php } 
											} ?>
											
											<?php if($settings['dis_op_title_icon']=="1" || $settings['dis_op_title_icon']=="2") { ?>
											
											<span><?php echo $item['tabs_responsive_title']; ?></span>
											
											<?php } ?>
											
											<?php if($settings['tabs_ic_pt_align']=="2"){ ?>
												<?php if($settings['dis_op_title_icon']=="1" || $settings['dis_op_title_icon']=="3") { ?>
													<?php if($item['tabs_responsive_show_above_icon']=="yes") { ?>	<i class="<?php echo implode(" ",$item['tabs_responsive_icon']) ?>"></i> <?php }?>
												<?php } 
											} ?>			
										
									
								</a>
							</li>
						<?php $i++; } ?>
					 </ul>

					  <!-- Tab panes -->
					  <div class="tab-content tab-content-border" id="tab-content_<?php echo $post_id; ?>">
						<?php  foreach( $settings['tabs_list'] as $index => $item )
						{
							$tab_content_setting_key = $this->get_repeater_setting_key( 'tabs_responsive_description', 'tabs_list', $index );
							$this->add_inline_editing_attributes( $tab_content_setting_key,'none' );
							
						?>
						 <div role="tabpanel" class="tab-pane <?php if($j==1){ ?> in active <?php } ?>" id="tabs_desc_<?php echo $post_id; ?>_<?php echo $j; ?>">
								<span <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>><?php  echo do_shortcode($item['tabs_responsive_description']); ?></span>
						 </div>
						<?php $j++; }
						}
						?>	
					 </div>
					 
				 </div>
<script>		
jQuery(function () {
	jQuery('#myTab_<?php echo $post_id; ?> a:first').tab('show')
});		
<?php if($settings['tabs_animation']!="6") { ?>		 
jQuery(function(){
	var b="<?php echo $settings['tabs_animation'] ?>";
	var c;
	var a;			
	d(jQuery("#myTab_<?php echo $post_id; ?> a"),jQuery("#tab-content_<?php echo $post_id; ?>"));function d(e,f,g){
		e.click(function(i){
			i.preventDefault();
			jQuery(this).tab("show");
			var h=jQuery(this).data("easein");
			if(c){c.removeClass(a);}
			if(h){f.find("div.active").addClass("animated "+h);a=h;}
			else{if(g){f.find("div.active").addClass("animated "+g);a=g;}else{f.find("div.active").addClass("animated "+b);a=b;}}c=f.find("div.active");
		});
	}
});
<?php } ?>		
</script>