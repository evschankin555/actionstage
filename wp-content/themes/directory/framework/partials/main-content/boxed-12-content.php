<?php
/*
 * Boxed-12 content.
 * Fullwidth boxed content.
 */
?>
<div class="full-section-60">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Main Item Content -->
				<section>
					<?php if ( is_single() ): ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-item' ); ?>>
						<section id="page-meta">
							<?php get_template_part( '/framework/partials/common-partials/meta' ); ?>
						</section>
						<?php endif; ?>

						<?php if ( has_post_thumbnail() ): ?>
							<section id="page-featured-image">
								<?php get_template_part( '/framework/partials/common-partials/featured-image' ); ?>
							</section>
						<?php endif; ?>

						<section id="main-entry-content">
							<?php the_content(); ?>
						</section>

						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'directory' ) . '</span>', 'after' => '</div>' ) ); ?>
					</article>
				</section>

				<!-- Tags -->
				<?php if ( has_tag() ): ?>
					<section id="page-tags">
						<?php echo get_the_tag_list( ' ', ' ', ' ' ); ?>
					</section>
				<?php endif; ?>

				<section id="page-comments-area">
					<?php comments_template( '', true ); ?>
				</section>

			</div>
		</div>
	</div>

</div>

