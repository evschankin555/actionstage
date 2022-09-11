<?php $settings = get_option(TLPTeam()->options['settings']); ?>
<div class="wrap">
    <h2><?php esc_html_e('TLP Team Settings', "tlp-team"); ?></h2>

    <div class="rt-settings-container">
        <div class="rt-setting-content">
            <form id="tlp-team-settings" method="post">

                <h3><?php esc_html_e('General settings', "tlp-team"); ?></h3>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label
                                    for="primary-color"><?php esc_html_e('Primary Color', "tlp-team"); ?></label></th>
                        <td class="">
                            <input name="primary_color" id="primary_color" type="text"
                                   value="<?php echo(isset($settings['primary_color']) ? ($settings['primary_color'] ? $settings['primary_color'] : '#0367bf') : '#0367bf'); ?>"
                                   class="tlp-color">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="imgWidth"><?php esc_html_e('Image Size', "tlp-team"); ?></label>
                        </th>
                        <td>
                            <select id="rt-feature-img-size" name="feature_img_size">
                                <?php
                                $fSize = !empty($settings['feature_img_size']) ? $settings['feature_img_size'] : TLPTeam()->options['feature_img_size'];
                                $imgSizes = TLPTeam()->get_image_sizes();
                                foreach ($imgSizes as $key => $size) {
                                    $slt = $key == $fSize ? "selected" : null;
                                    echo "<option value='{$key}' {$slt}>{$size}</option>";
                                }
                                $fw = !empty($settings['rt_custom_img_size']['width']) ? absint($settings['rt_custom_img_size']['width']) : null;
                                $fh = !empty($settings['rt_custom_img_size']['height']) ? absint($settings['rt_custom_img_size']['height']) : null;
                                $fc = !empty($settings['rt_custom_img_size']['crop']) ? $settings['rt_custom_img_size']['crop'] : 'soft';
                                ?>
                            </select>
                            <div class="rt-custom-image-size-wrap rt-hidden">
                                <div class="item">
                                    <label>Width</label>
                                    <input type="number" name="rt_custom_img_size[width]" class="small-text"
                                           value="<?php echo $fw; ?>">
                                </div>
                                <div class="item">
                                    <label>Height</label>
                                    <input type="number" name="rt_custom_img_size[height]" class="small-text"
                                           value="<?php echo $fh; ?>">
                                </div>
                                <div class="item">
                                    <label>Crop</label>
                                    <select name="rt_custom_img_size[crop]">
                                        <option value="soft" <?php echo $fc == "soft" ? "selected" : null; ?>>Soft
                                            crop
                                        </option>
                                        <option value="hard" <?php echo $fc == "hard" ? "selected" : null; ?>>Hard
                                            crop
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="slug"><?php esc_html_e('Slug', "tlp-team"); ?></label></th>
                        <td class="">
                            <input name="slug" id="slug" type="text"
                                   value="<?php echo(isset($settings['slug']) ? ($settings['slug'] ? sanitize_title_with_dashes($settings['slug']) : 'team') : 'team'); ?>"
                                   size="15" class="">
                            <p class="description"><?php esc_html_e('Slug configuration', "tlp-team"); ?></p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label
                                    for="link_detail_page"><?php esc_html_e('Link To Detail Page', "tlp-team"); ?></label>
                        </th>
                        <td class="">
                            <fieldset>
                                <legend class="screen-reader-text"><span>Link To Detail Page</span></legend>
                                <?php
                                $opt = array('yes' => "Yes", 'no' => "No");
                                $i = 0;
                                $pds = (isset($settings['link_detail_page']) ? ($settings['link_detail_page'] ? $settings['link_detail_page'] : 'yes') : 'yes');
                                foreach ($opt as $key => $value) {
                                    $select = (($pds == $key) ? 'checked="checked"' : null);
                                    echo "<label title='$value'><input type='radio' $select name='link_detail_page' value='$key' > $value</label>";
                                    if ($i == 0) {
                                        echo "<br>";
                                    }
                                    $i++;
                                }
                                ?>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="css"><?php esc_html_e('Custom Css ', "tlp-team"); ?></label></th>
                        <td>
                            <textarea name="custom_css" cols="40"
                                      rows="6"><?php echo(isset($settings['custom_css']) ? ($settings['custom_css'] ? $settings['custom_css'] : null) : null); ?></textarea>
                            <p class="description"
                               style="color: red"><?php esc_html_e("Please use default customizer to add your css. This option is deprecated.", 'tlp-team'); ?></p>
                        </td>
                    </tr>

                </table>
                <p class="submit"><input type="submit" name="submit" id="tlpSaveButton"
                                         class="rt-admin-btn button button-primary"
                                         value="<?php esc_html_e('Save Changes', "tlp-team"); ?>"></p>

                <?php wp_nonce_field(TLPTeam()->nonceText(), 'tlp_nonce'); ?>
            </form>
            <div id="response" class="updated"></div>
        </div>

        <div class="rt-pro-feature-content">
            <?php TLPTeam()->rt_plugin_team_sc_pro_information('settings'); ?>
        </div>
    </div>
</div>

