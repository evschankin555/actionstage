<?php
/*
 * Boxed-9 content.
 * Contains both main content and sidebar.
 */
if (is_home() || is_category() || is_tag() || is_archive() || is_search()):
    $sidebar = 'sidebar';
else:
    $sidebar = 'sidebar';
endif;
?>
<div class="full-section-60">
    <div class="container">
        <div class="row">
            <div class="<?php echo (is_tax() ? 'col-lg-12': 'col-lg-9'   ); ?>">

                <?php
                if (is_tax()):

                    $taxonomy = get_query_var('taxonomy');
                    $term = get_query_var('term');
                    $l_args = array(
                        'post_type' => 'listing',
                        'posts_per_page' => 12,
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'slug',
                                'terms' => $term))
                    );

                    $l_query = new WP_Query($l_args);

                    if ($l_query->have_posts()): ?>

                    <div class="listing-items-container">
                        <?php while ($l_query->have_posts()):$l_query->the_post(); ?>

                         <?php
                            $id = get_the_ID();
                            $address = get_post_meta(get_the_ID(), '_directory_listing_address', true);
                            $post_thumb_id = get_post_thumbnail_id(get_the_ID());
                            $listing_image = wp_get_attachment_image_src($post_thumb_id, 'medium');

                            $content = get_the_content();
                            $exc = wp_trim_words($content,10,'...');
                            ?>

                         <div class="col-md-4 single-listing-item"
                              data-address="<?php echo $address;?>">
                             <figure class="snip1268">
                                 <div class="image">
                                     <?php get_template_part('/framework/partials/common-partials/featured-image'); ?>
                                     <div class="icons">
                                         <a href="#" data-target="#myModal" data-toggle="modal" title="<?php echo __('Fast View','directory') ?>"> <i class="demo-icon icon-icons_preview-01"></i></a>
                                         </div>
                                     <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="view-listing"><?php echo __('View Listing','directory'); ?></a>
                                 </div>
                                 <figcaption>
                                     <h2><?php the_title(); ?></h2>
                                     <?php $content = get_the_content();
                                        echo '<p>'.wp_trim_words($content,15,'...').'</p>';
                                     ?>
                                 </figcaption>

                                 <!-- Modal -->
                                 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                     <div class="modal-dialog" role="document">
                                         <div class="modal-content">
                                             <div class="modal-header">
                                                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                 <?php if(has_post_thumbnail()): ?>
                                                     <?php the_post_thumbnail('listing-image',array('class'=>'img-responsive listing-modal-image')); ?>
                                                 <?php endif; ?>
                                                 <h4 class="modal-title" id="myModalLabel"><?php the_title(); ?></h4>
                                             </div>
                                             <div class="modal-body">
                                                 <?php echo get_the_excerpt(); ?>
                                             </div>
                                             <div class="modal-footer">
                                                 <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close','directory'); ?></button>
                                             </div>
                                         </div>
                                     </div>
                                 </div><!-- .Modal -->

                             </figure>

                         </div>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <p><?php echo __('There are not any listings available', 'directory'); ?></p>

                    </div> <!-- Listing items container -->
                    <?php endif; // end taxonomy
                    wp_reset_postdata();

                elseif (is_home() || is_category() || is_tag() || is_search() || is_archive()): // is the blog index? ?>
                    <?php if (have_posts()): ?>

                    <section id="directory-blog-container">
                        <?php while (have_posts()):the_post(); ?>
                            <article id="post-<?php the_ID(); ?>"  <?php post_class('blog-item'); ?>>

                                <section class="blog-item-title">
                                    <h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                </section>

                                <section class="blog-item-meta">
                                    <?php get_template_part('/framework/partials/common-partials/meta'); ?>
                                </section>

                                <?php if (has_post_thumbnail()): ?>
                                    <section class="blog-item-featured-image">
                                        <?php get_template_part('/framework/partials/common-partials/featured-image'); ?>
                                    </section>
                                <?php endif; ?>

                                <section class="blog-item-main-content">
                                    <?php the_excerpt(); ?>
                                </section>

                                <a class="blog-item-read-more directory-btn" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
                                    <?php echo __('READ MORE', 'directory'); ?>
                                </a>
                                <hr/>
                            </article>
                        <?php endwhile; ?>

                        <section class="pagination">
                            <?php directory_pagination(); ?>
                        </section>


                    </section>

                <?php else: ?>
                    <p><?php echo __('There are not any posts available', 'directory'); ?></p>
                <?php endif; ?>

                <?php else: // is not blog page ?>
                    <!-- Main Item Content -->
                    <section>
                        <?php if (is_single()): ?>
                        <article id="post-<?php the_ID(); ?>"  <?php post_class('blog-item'); ?>>
                            <section id="page-meta">
                                <?php get_template_part('/framework/partials/common-partials/meta'); ?>
                            </section>
                        <?php endif; ?>

                        <?php if (has_post_thumbnail()): ?>
                            <section id="page-featured-image">
                                <?php get_template_part('/framework/partials/common-partials/featured-image'); ?>
                            </section>
                        <?php endif; ?>

                        <section id="main-entry-content">
                            <?php the_content(); ?>
                        </section>


                            <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . __('Pages:', 'directory') . '</span>', 'after' => '</div>')); ?>

                        <!-- Tags -->
                        <?php if (has_tag()): ?>
                            <section class="blog-item-tags">
                                <?php echo get_the_tag_list(' ', ' ', ' '); ?>
                            </section>
                        <?php endif; ?>
                        </article>
                    </section>

                <?php endif;  // is home(blog) or single page/post ?>

                <section id="page-comments-area">
                    <?php comments_template('', true); ?>
                </section>


            </div>
            <?php if(!is_tax()): ?>
            <div class="col-lg-3">

                <!-- Sidebar -->
                <aside>
                    <?php get_sidebar(); ?>
                </aside>

            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

