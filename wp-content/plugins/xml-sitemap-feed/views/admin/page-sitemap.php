<style type="text/css">
<?php include XMLSF_DIR . '/assets/styles/admin.css'; ?>
</style>
<div class="wrap">

	<h1><?php _e('XML Sitemap','xml-sitemap-feed'); ?></h1>

	<p>
		<?php printf( /* translators: Plugin name */ __('These settings control the XML Sitemaps generated by the %s plugin.','xml-sitemap-feed'),__('XML Sitemap & Google News','xml-sitemap-feed')); ?>
		<?php printf( /* translators: Writing Settings URL */ __('For ping options, go to %s.','xml-sitemap-feed'),'<a href="'.admin_url('options-writing.php').'#xmlsf_ping">'.translate('Writing Settings').'</a>'); ?>
	</p>

	<nav class="nav-tab-wrapper">
		<a href="?page=xmlsf&tab=post_types" class="nav-tab <?php echo $active_tab == 'post_types' ? 'nav-tab-active' : ''; ?>"><?php _e('Post types','xml-sitemap-feed'); ?></a>
		<a href="?page=xmlsf&tab=taxonomies" class="nav-tab <?php echo $active_tab == 'taxonomies' ? 'nav-tab-active' : ''; ?>"><?php _e('Taxonomies','xml-sitemap-feed'); ?></a>
		<a href="?page=xmlsf&tab=advanced" class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>"><?php echo translate('Advanced'); ?></a>
	</nav>

	<div class="main">
		<form method="post" action="options.php">

			<?php settings_fields( 'xmlsf_'.$active_tab ); ?>

			<?php do_settings_sections( 'xmlsf_'.$active_tab ); ?>

			<?php submit_button(); ?>

		</form>
	</div>

	<div class="sidebar">
		<h3><span class="dashicons dashicons-welcome-view-site"></span> <?php echo translate('View'); ?></h3>
		<p>
			<?php
			printf (
			/* translators: Sitemap name with URL */
			__( 'Open your %s', 'xml-sitemap-feed' ),
			'<strong><a href="'.$url.'" target="_blank">'.__('XML Sitemap Index','xml-sitemap-feed') . '</a></strong><span class="dashicons dashicons-external"></span>'
			); ?>
		</p>

		<h3><span class="dashicons dashicons-admin-tools"></span> <?php echo translate('Tools'); ?></h3>
		<form action="" method="post">
			<?php wp_nonce_field( XMLSF_BASENAME.'-help', '_xmlsf_help_nonce' ); ?>
			<p>
				<input type="submit" name="xmlsf-ping-sitemap" class="button button-small" value="<?php _e( 'Ping search engines', 'xml-sitemap-feed' ); ?>" />
			</p>
			<p>
				<input type="submit" name="xmlsf-flush-rewrite-rules" class="button button-small" value="<?php _e( 'Flush rewrite rules', 'xml-sitemap-feed' ); ?>" />
			</p>
			<p>
				<input type="submit" name="xmlsf-check-conflicts" class="button button-small" value="<?php _e( 'Check for conflicts', 'xml-sitemap-feed' ); ?>" />
			</p>
			<p>
				<?php //printf( __('%1$s or %2$s all cached Sitemap metadata.'), '<input type="submit" name="xmlsf-prime-meta" class="button button-small" value="'.__( 'Rebuild', 'xml-sitemap-feed' ).'"/>', '<input type="submit" name="xmlsf-clear-meta" class="button button-small" value="'.__( 'Clear', 'xml-sitemap-feed' ).'"/>'); ?>
				<input type="submit" name="xmlsf-clear-post-meta" class="button button-small" value="<?php _e( 'Clear post meta caches', 'xml-sitemap-feed' ); ?>" />
				<input type="submit" name="xmlsf-clear-term-meta" class="button button-small" value="<?php _e( 'Clear term meta cache', 'xml-sitemap-feed' ); ?>" />
			</p>
			<p>
				<input type="hidden" name="xmlsf-clear-settings" value="sitemap" />
				<input type="submit" name="xmlsf-clear-settings-submit" class="button button-small button-link-delete" value="<?php _e( 'Reset settings', 'xml-sitemap-feed' ); ?>" onclick="javascript:return confirm('<?php _e('This will revert your sitemap settings to the plugin defaults.','xml-sitemap-feed'); ?>\n\n<?php echo translate('Are you sure you want to do this?'); ?>')" />
			</p>
		</form>

		<?php include XMLSF_DIR . '/views/admin/sidebar-links.php'; ?>

		<?php include XMLSF_DIR . '/views/admin/sidebar-help.php'; ?>

		<?php include XMLSF_DIR . '/views/admin/help-tab-sidebar.php'; ?>

		<?php include XMLSF_DIR . '/views/admin/sidebar-contribute.php'; ?>

	</div>

</div>
