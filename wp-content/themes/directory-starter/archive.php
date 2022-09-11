<?php get_header(); ?>

<?php do_action('dt_archive_before_main_content'); ?>

<?php
$dt_enable_blog_sidebar = esc_attr(get_theme_mod('dt_enable_blog_sidebar', DT_ENABLE_BLOG_SIDEBAR)) == '1' && is_active_sidebar( 'sidebar-primary' ) ? true : false;
$dt_blog_sidebar_position = esc_attr(get_theme_mod('dt_blog_sidebar_position', DT_BLOG_SIDEBAR_POSITION));

if ($dt_enable_blog_sidebar) {
  $content_class = 'col-lg-8 col-md-9';
} else {
  $content_class = 'col-lg-12';
}
?>
<section class="<?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?> py-4">
    <div class="row">
        <?php if ($dt_enable_blog_sidebar && $dt_blog_sidebar_position == 'left') { ?>
          <div class="col-lg-4 col-md-3">
            <div class="sidebar blog-sidebar page-sidebar">
              <?php get_sidebar(); ?>
            </div>
          </div>
        <?php } ?>
        <div class="<?php echo esc_attr($content_class); ?>">
            <div class="content-archive">
                <?php if ( have_posts() ) { ?>
                    <header <?php post_class('content-box  p-3 mb-3 '.dt_content_classes()); ?>>
                        <?php
                        the_archive_title( '<h1 class="page-title entry-title pb-0 mb-0 h2">', '</h1>' );
                        ?>
                    </header><!-- .page-header -->
                    <?php
                    while ( have_posts() ) : the_post();

                        // Include the page content template.
                        get_template_part( 'template-parts/post/content' );

                        // End the loop.
                    endwhile;

                    // Previous/next page navigation.
                    the_posts_pagination( array(
                        'prev_text'          => __( 'Previous', 'directory-starter' ),
                        'next_text'          => __( 'Next', 'directory-starter' ),
                    ) );
                }else{
                    get_template_part( 'template-parts/content/content', 'none' );
                }
                ?>
            </div>
        </div>
        <?php if ($dt_enable_blog_sidebar && $dt_blog_sidebar_position == 'right') { ?>
          <div class="col-lg-4 col-md-3">
            <div class="sidebar blog-sidebar page-sidebar">
              <?php get_sidebar(); ?>
            </div>
          </div>
        <?php } ?>
    </div>
</section>

<?php do_action('dt_blog_after_main_content'); ?>

<?php get_footer(); ?>