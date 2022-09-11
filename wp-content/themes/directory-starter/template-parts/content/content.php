<article <?php post_class('content-box  '.dt_content_classes()); ?>>
	<header>
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title border-bottom pb-2 h2">', '</h1>' );
		else :
			the_title( sprintf( '<h2 class="entry-title border-bottom pb-2 h3"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		endif;
		?>
	</header>
	<div class="entry-content entry-summary">
		<?php
		if (is_singular() || (function_exists('is_bbpress') && is_bbpress())) {
			the_content();
		} else {
			directory_theme_post_thumbnail();
			the_excerpt();
		}
		?>
		<?php
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'directory-starter' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
		?>
	</div>
	<footer class="entry-footer mt-2 pt-2 text-muted">
		<?php directory_theme_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'directory-starter' ), '<span class="edit-link"><i class="fas fa-pencil-alt"></i> ', '</span>' ); ?>
	</footer>
</article>