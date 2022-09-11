<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!function_exists('awpa_get_author_role')) {

    /**
     * @param $author_id
     * @return mixed
     */
    function awpa_get_author_role($author_id)
    {
        $user = new WP_User($author_id);
        return array_shift($user->roles);

    }
}


if (!function_exists('awpa_get_author_contact_info')) {
    /**
     * @param $author_id
     * @return array
     */
    function awpa_get_author_contact_info($author_id)
    {
        $author_facebook = get_the_author_meta('awpa_contact_facebook', $author_id);
        $author_twitter = get_the_author_meta('awpa_contact_twitter', $author_id);
        $author_linkedin = get_the_author_meta('awpa_contact_linkedin', $author_id);
        $author_instagram = get_the_author_meta('awpa_contact_instagram', $author_id);
        $author_youtube = get_the_author_meta('awpa_contact_youtube', $author_id);
        $author_email = get_the_author_meta('user_email', $author_id);
        $author_website = get_the_author_meta('user_url', $author_id);

        $contact_info = array();

        if (!empty($author_facebook)) {
            $contact_info['facebook'] = esc_url($author_facebook);
        }

        if (!empty($author_twitter)) {
            $contact_info['twitter'] = esc_url($author_twitter);
        }

        if (!empty($author_linkedin)) {
            $contact_info['linkedin'] = esc_url($author_linkedin);
        }

        if (!empty($author_instagram)) {
            $contact_info['instagram'] = esc_url($author_instagram);
        }

        if (!empty($author_youtube)) {
            $contact_info['youtube'] = esc_url($author_youtube);
        }

        if (!empty($author_website)) {
            $contact_info['website'] = esc_url($author_website);
        }


        if (!empty($author_email)) {
            $contact_info['email'] = esc_attr($author_email);
        }

        return $contact_info;
    }
}


if (!function_exists('awpa_get_author_block')) {
    /**
     * @param $author_id
     * @return array
     */
    function awpa_get_author_block($author_id, $image_layout = 'square', $show_role = false, $show_email = false)
    {

        if (empty($author_id)) {
            global $post;
            $author_id = get_post_field('post_author', $post->ID);
        }

        $author_name = get_the_author_meta('display_name', $author_id);
        $author_website = get_the_author_meta('user_url', $author_id);

        $author_role = '';
        if (isset($show_role) && $show_role == true) {
            $author_role = awpa_get_author_role($author_id);
            $author_role = esc_attr($author_role);
        }

        $author_email = '';
        if (isset($show_email) && $show_email == true) {
            $author_email = get_the_author_meta('user_email', $author_id);
            $author_email = sanitize_email($author_email);
        }


        $contact_info = awpa_get_author_contact_info($author_id);
        $author_posts_url = get_author_posts_url($author_id);
        $author_avatar = get_avatar($author_id, 150);
        $author_desc = get_the_author_meta('description', $author_id);
        ?>

        <div class="wp-post-author">

            <div class="awpa-img awpa-author-block <?php echo $image_layout; ?>">
                <a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo $author_avatar; ?></a>
            </div>
            <div class="wp-post-author-meta awpa-author-block">
                <h4 class="awpa-display-name">
                    <a href="<?php echo $author_posts_url; ?>"><?php echo esc_attr($author_name); ?></a>
                </h4>

                <?php if (!empty($author_role)): ?>
                    <p class="awpa-role"><?php echo $author_role; ?></p>
                <?php endif; ?>

                <div class="wp-post-author-meta-bio">
                    <?php
                    $author_desc = wptexturize($author_desc);
                    $author_desc = wpautop($author_desc);
                    echo wp_kses_post($author_desc);
                    ?>
                </div>
                <div class="wp-post-author-meta-more-posts">
                    <p class="awpa-more-posts">
                        <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"
                           class="awpa-more-posts"><?php esc_html_e("See author's posts", 'wp-post-author'); ?></a>
                    </p>
                </div>
                <?php if (!empty($contact_info)): ?>
                    <ul class="awpa-contact-info">
                        <?php foreach ($contact_info as $key => $value): ?>
                            <?php if ($key == 'email'): ?>
                                <?php if (isset($show_email) && $show_email == true): ?>
                                    <li class="awpa-<?php echo $key; ?>-li">
                                        <a href="mailto:<?php echo $value; ?>"
                                           class="awpa-<?php echo $key; ?> awpa-icon-<?php echo $key; ?>"></a>
                                    </li>
                                <?php endif; ?>
                            <?php else: ?>

                                <li class="awpa-<?php echo $key; ?>-li">
                                    <a href="<?php echo $value; ?>"
                                       class="awpa-<?php echo $key; ?> awpa-icon-<?php echo $key; ?>"></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <?php


    }
}


if (!function_exists('awpa_get_author_block_custom')) {
    /**
     * @param $author_id
     * @return array
     */
    function awpa_get_author_block_custom($instance)
    {

        $image_layout = isset($instance['awpa-post-author-image-layout']) ? $instance['awpa-post-author-image-layout'] : 'square';
        $author_name = isset($instance['awpa-post-author-name']) ? $instance['awpa-post-author-name'] : '';
        $author_role = isset($instance['awpa-post-author-role']) ? $instance['awpa-post-author-role'] : '';
        $author_email = isset($instance['awpa-post-author-email']) ? $instance['awpa-post-author-email'] : '';
        $author_website = isset($instance['awpa-post-author-website']) ? $instance['awpa-post-author-website'] : '';
        $author_desc = isset($instance['awpa-post-author-desc']) ? $instance['awpa-post-author-desc'] : '';
        $author_facebook = isset($instance['awpa-post-author-facebook']) ? $instance['awpa-post-author-facebook'] : '';
        $author_twitter = isset($instance['awpa-post-author-twitter']) ? $instance['awpa-post-author-twitter'] : '';
        $author_instagram = isset($instance['awpa-post-author-instagram']) ? $instance['awpa-post-author-instagram'] : '';
        $author_youtube = isset($instance['awpa-post-author-youtube']) ? $instance['awpa-post-author-youtube'] : '';
        $author_linkedin = isset($instance['awpa-post-author-linkedin']) ? $instance['awpa-post-author-linkedin'] : '';

        $image_id = isset($instance['awpa-post-author-image']) ? $instance['awpa-post-author-image'] : '';
        $image_src = '';
        if (!empty($image_id)) {
            $image_attributes = wp_get_attachment_image_src($image_id, 'large');
            $image_src = $image_attributes[0];
        }


        $contact_info = array();

        if (!empty($author_facebook)) {
            $contact_info['facebook'] = esc_url($author_facebook);
        }

        if (!empty($author_twitter)) {
            $contact_info['twitter'] = esc_url($author_twitter);
        }

        if (!empty($author_linkedin)) {
            $contact_info['linkedin'] = esc_url($author_linkedin);
        }

        if (!empty($author_instagram)) {
            $contact_info['instagram'] = esc_url($author_instagram);
        }

        if (!empty($author_youtube)) {
            $contact_info['youtube'] = esc_url($author_youtube);
        }

        if (!empty($author_website)) {
            $contact_info['website'] = esc_url($author_website);
        }

        if (!empty($author_email)) {
            $contact_info['email'] = esc_attr($author_email);
        }

        ?>

        <div class="wp-post-author">

            <?php if (!empty($image_src)) : ?>

                <figure class="awpa-img awpa-author-block awpa-bg-image awpa-data-bg <?php echo esc_attr($image_layout); ?>">
                    <img src="<?php echo esc_attr($image_src); ?>" alt="<?php echo esc_attr($author_name); ?>"/>
                </figure>

            <?php endif; ?>

            <div class="wp-post-author-meta awpa-author-block">
                <h4 class="awpa-display-name">
                    <?php echo esc_attr($author_name); ?>
                </h4>

                <?php if (!empty($author_role)): ?>
                    <p class="awpa-role"><?php echo $author_role; ?></p>
                <?php endif; ?>


                <div class="wp-post-author-meta-bio">
                    <?php
                    $author_desc = wptexturize($author_desc);
                    $author_desc = wpautop($author_desc);
                    echo wp_kses_post($author_desc);
                    ?>
                </div>
                <?php if (!empty($contact_info)): ?>
                    <ul class="awpa-contact-info">
                        <?php foreach ($contact_info as $key => $value): ?>
                            <?php if ($key == 'email'): ?>
                                <li class="awpa-<?php echo $key; ?>-li">
                                    <a href="mailto:<?php echo $value; ?>"
                                       class="awpa-<?php echo $key; ?> awpa-icon-<?php echo $key; ?>"></a>
                                </li>
                            <?php else: ?>

                                <li class="awpa-<?php echo $key; ?>-li">
                                    <a href="<?php echo $value; ?>"
                                       class="awpa-<?php echo $key; ?> awpa-icon-<?php echo $key; ?>"></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <?php


    }
}


add_filter('the_content', 'awpa_add_author');
if (!function_exists('awpa_add_author')) {
    function awpa_add_author($content)
    {
        if (is_single()) {
            $options = get_option('awpa_setting_options');
            if (!isset($options['hide_from_post_content'])) {

                $title = (isset($options['awpa_global_title'])) ? $options['awpa_global_title'] : '';
                $align = (isset($options['awpa_global_align'])) ? $options['awpa_global_align'] : '';
                $image_layout = (isset($options['awpa_global_image_layout'])) ? $options['awpa_global_image_layout'] : '';
                $show_role = (isset($options['awpa_global_show_role'])) ? $options['awpa_global_show_role'] : '';
                $show_email = (isset($options['awpa_global_show_email'])) ? $options['awpa_global_show_email'] : '';

                $post_author = do_shortcode('[wp-post-author title="' . $title . '" align="' . $align . '" image-layout="' . $image_layout . '" show-role="' . $show_role . '" show-email="' . $show_email . '"]');
                $content .= $post_author;

            }
        }


        return $content;
    }
}

