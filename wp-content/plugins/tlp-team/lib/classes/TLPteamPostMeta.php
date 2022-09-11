<?php
if (!class_exists('TLPteamPostMeta')):

    /**
     *
     */
    class TLPteamPostMeta
    {

        function __construct() {
            add_action('add_meta_boxes', array($this, 'team_meta_boxes'));
            add_action('save_post', array($this, 'save_team_meta_data'), 10, 2);
            add_action('admin_print_scripts-post-new.php', array($this, 'tpl_team_script'), 11);
            add_action('admin_print_scripts-post.php', array($this, 'tpl_team_script'), 11);
            add_action('edit_form_after_title', array($this, 'team_after_title'));
        }

        function team_after_title($post) {
            if (TLPTeam()->post_type !== $post->post_type) {
                return;
            }
            $html = null;
            $html .= '<div class="postbox" style="margin-bottom: 0;"><div class="inside">';
            $html .= '<p style="text-align: center;"><a style="color: red; text-decoration: none; font-size: 14px;" href="https://radiustheme.com/tlp-team-pro-for-wordpress/" target="_blank">Please check the pro features</a></p>';
            $html .= '</div></div>';

            echo $html;
        }

        function team_meta_boxes() {
            add_meta_box(
                'tlp_team_meta',
                __('Member Info', "tlp-team"),
                array($this, 'tlp_team_meta'),
                'team',
                'normal',
                'high');
        }

        function tlp_team_meta($post) {
            wp_nonce_field(TLPTeam()->nonceText(), 'tlp_nonce');
            $meta = get_post_meta($post->ID);
            ?>
            <div class="member-field-holder">

                <div class="tlp-field-holder">
                    <div class="tlp-label">
                        <label for="short_bio"><?php esc_html_e('Short Bio:', "tlp-team"); ?></label>
                    </div>
                    <div class="tlp-field">
                        <textarea name="short_bio" rows="5" class="tlpfield"
                                  value=""><?php echo(@$meta['short_bio'][0] ? @$meta['short_bio'][0] : null) ?></textarea>
                        <span class="desc"><?php esc_html_e('Add some short bio', "tlp-team"); ?></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tlp-label">
                        <label for="designation"><?php esc_html_e('Designations', "tlp-team"); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" name="designation" class="tlpfield"
                               value="<?php echo(@$meta['designation'][0] ? @$meta['designation'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>


                <div class="tlp-field-holder">
                    <div class="tlp-label">
                        <label for="email"><?php esc_html_e('Email', "tlp-team"); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="email" name="email" class="tlpfield"
                               value="<?php echo(@$meta['email'][0] ? @$meta['email'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tlp-label">
                        <label for="web_url"><?php esc_html_e('Personal Web URL', "tlp-team"); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="url" name="web_url" class="tlpfield"
                               value="<?php echo(@$meta['web_url'][0] ? @$meta['web_url'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tlp-label">
                        <label for="url"><?php esc_html_e('Telephone', "tlp-team"); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" name="telephone" class="tlpfield"
                               value="<?php echo(@$meta['telephone'][0] ? @$meta['telephone'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>

                <div class="tlp-field-holder">
                    <div class="tlp-label">
                        <label for="location"><?php esc_html_e('Location', "tlp-team"); ?>:</label>
                    </div>
                    <div class="tlp-field">
                        <input type="text" name="location" class="tlpfield"
                               value="<?php echo(@$meta['location'][0] ? @$meta['location'][0] : null) ?>">
                        <span class="desc"></span>
                    </div>
                </div>
                <div class="tlp-field-holder">
                    <h3 class="tlp-field-title"><?php esc_html_e('Social Links', "tlp-team"); ?></h3>
                </div>
                <?php
                $s = unserialize(get_post_meta($post->ID, 'social', true));
                foreach (TLPTeam()->socialLink() as $id => $label) {
                    ?>
                    <div class="tlp-field-holder">
                        <div class="tlp-label">
                            <label for="location"><?php echo $label; ?></label>
                        </div>
                        <div class="tlp-field">
                            <input type="url" name="social[<?php echo $id; ?>]" class="tlpfield"
                                   value="<?php echo(!empty($s[$id]) ? $s[$id] : null) ?>">
                        </div>
                    </div>
                <?php } ?>

            </div>
            <?php
        }

        function save_team_meta_data($post_id, $post) {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (!TLPTeam()->verifyNonce()) {
                return $post_id;
            }
            if (TLPTeam()->post_type != $post->post_type) {
                return $post_id;
            }


            if (isset($_REQUEST['short_bio'])) {
                update_post_meta($post_id, 'short_bio', wp_kses_post($_REQUEST['short_bio']));
            }

            if (isset($_REQUEST['email'])) {
                update_post_meta($post_id, 'email', sanitize_text_field($_REQUEST['email']));
            }


            if (isset($_REQUEST['designation'])) {
                update_post_meta($post_id, 'designation', sanitize_text_field($_REQUEST['designation']));
            }

            if (isset($_REQUEST['web_url'])) {
                update_post_meta($post_id, 'web_url', sanitize_text_field($_REQUEST['web_url']));
            }

            if (isset($_REQUEST['telephone'])) {
                update_post_meta($post_id, 'telephone', sanitize_text_field($_REQUEST['telephone']));
            }

            if (isset($_REQUEST['location'])) {
                update_post_meta($post_id, 'location', sanitize_text_field($_REQUEST['location']));
            }

            if (isset($_REQUEST['social'])) {
                $s = array_filter($_REQUEST['social']);
                update_post_meta($post_id, 'social', serialize($s));
            }

        }

        function tpl_team_script() {
            global $post_type;
            if ($post_type == TLPTeam()->post_type) {
                TLPTeam()->tlp_style();
                TLPTeam()->tlp_script();
            }
        }
    }
endif;
