<?php
/*
 * Template Name: Directory Home Widgetized
 */
get_header(); ?>
	<div id="directory-home-map">

	</div>

	<section id="home-widget-area-section" class="full-section-60">
		<div class="container">
			<?php if ( have_posts() ):
				while ( have_posts() ):the_post(); ?>

					<div class="row">
						<div class="col-lg-12">
							<?php the_content(); ?>
						</div>
					</div>
				<?php endwhile;
			endif; ?>
			<div class="row full-section-30">
				<div class="col-lg-3 home-sidebar-1-area">
					<?php if ( ! dynamic_sidebar( 'home-sidebar-1' ) ): ?>

					<?php endif; ?>
				</div>

				<div class="col-lg-3 home-sidebar-2-area">
					<?php if ( ! dynamic_sidebar( 'home-sidebar-2' ) ): ?>

					<?php endif; ?>
				</div>

				<div class="col-lg-3 home-sidebar-3-area">
					<?php if ( ! dynamic_sidebar( 'home-sidebar-3' ) ): ?>

					<?php endif; ?>
				</div>

				<div class="col-lg-3 home-sidebar-4-area">
					<?php if ( ! dynamic_sidebar( 'home-sidebar-4' ) ): ?>

					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php get_footer();