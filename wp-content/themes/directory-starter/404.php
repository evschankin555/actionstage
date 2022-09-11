<?php get_header(); ?>

<section class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> py-4">
	<div class="content-box content-404 p-3 mb-3 <?php echo dt_content_classes();?>">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Not Found', 'directory-starter' ); ?></h1>
		</header>

		<div class="page-wrapper">
			<div class="page-content">
				<h2 class=""><?php esc_html_e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'directory-starter' ); ?></h2>
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'directory-starter' ); ?></p>

				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>