<footer id="footer" class="site-footer bg-dark p-0" role="contentinfo">
	<div class="footer-widgets <?php if(get_theme_mod('dt_fw_border_top_color', DT_FW_BORDER_TOP_COLOR)){echo "border-top ";} if(get_theme_mod('dt_fw_border_bottom_color', DT_FW_BORDER_TOP_COLOR)){echo "border-bottom ";} ?>">
		<div class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?>">
			<div class="row">
			<?php $class = apply_filters('dt_footer_widget_class', 'col-lg-3 col-md-3 col-sm-6 col-xs-12')?>
			<?php if(FOOTER_SIDEBAR_COUNT > 0) { ?>
				<div class="<?php echo $class; ?>">
					<?php dynamic_sidebar('sidebar-footer1');?>
				</div>
			<?php } ?>
			<?php if(FOOTER_SIDEBAR_COUNT > 1) { ?>
				<div class="<?php echo $class; ?>">
					<?php dynamic_sidebar('sidebar-footer2');?>
				</div>
			<?php } ?>
			<?php if(FOOTER_SIDEBAR_COUNT > 2) { ?>
				<div class="<?php echo $class; ?>">
					<?php dynamic_sidebar('sidebar-footer3');?>
				</div>
			<?php } ?>
			<?php if(FOOTER_SIDEBAR_COUNT > 3) { ?>
				<div class="<?php echo $class; ?>">
					<?php dynamic_sidebar('sidebar-footer4');?>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="copyright text-center border-top <?php echo (has_nav_menu( 'footer-links' )) ? 'footer-links-active' : ''; ?>">
		<div class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> <?php echo (has_nav_menu( 'footer-links' )) ? 'd-flex justify-content-between align-items-center' : ''; ?>">
			<p class="copyright-text p-0 m-0 py-3">
			<?php do_action('dt_footer_copyright'); ?>
			</p>
			<?php
			if (has_nav_menu( 'footer-links' )) {
				wp_nav_menu( array(
						'theme_location' => 'footer-links',
						'container_class' => 'ds_footer_links navbar navbar-expand-lg navbar-dark',
						'menu_class' => 'navbar-nav ml-auto text-nowrap flex-wrap'
				) );
			}
			?>
		</div>
	</div>

</footer>
<?php wp_footer(); ?>
</body>
</html>