<?php

$post_thumb_id = get_post_thumbnail_id(get_the_ID());
$listing_image = wp_get_attachment_image_src($post_thumb_id, 'medium');
$address = get_post_meta(get_the_ID(), 'add_listing_address_listing_address', true);
?>
<div class="full-section-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <section class="single-listing-tabs">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo __('ABOUT', 'directory'); ?></a></li>
                        <li role="presentation"><a href="#info" aria-controls="info" role="tab" data-toggle="tab"><?php echo __('LISTING INFO', 'directory'); ?></a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home">
                            <?php the_content(); ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="info">
                            <ul id="listing-info-list">
                                <?php if ($address != ''): ?>
                                    <li>
                                        <span class="fa fa-map-marker"></span> <?php echo __('Address:', 'directory'); ?> <?php echo $address; ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                </section>
            </div>
        </div>
    </div>
</div>