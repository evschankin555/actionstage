<?php
if (!class_exists('TLPTeamHelper')) :

    class TLPTeamHelper
    {
        function verifyNonce() {
            $nonce = isset($_REQUEST[$this->nonceId()]) ? $_REQUEST[$this->nonceId()] : null;
            $nonceText = $this->nonceText();
            if (!wp_verify_nonce($nonce, $nonceText)) {
                return false;
            }

            return true;
        }

        function nonceText() {
            return "tlp_team_nonce";
        }

        function nonceId() {
            return "tlp_nonce";
        }

        function rtFieldGenerator($fields = array()) {
            $html = null;
            if (is_array($fields) && !empty($fields)) {
                $tlpField = new TLPTeamField();
                foreach ($fields as $fieldKey => $field) {
                    $html .= $tlpField->Field($fieldKey, $field);
                }
            }

            return $html;
        }

        /**
         * @param $post_id
         * @param $mates
         * @param $request
         * Update meta fields
         */
        function updateMetaFields($post_id, $mates, $request) {
            if (is_array($mates) && !empty($mates)) {
                foreach ($mates as $metaKey => $field) {
                    $rValue = !empty($request[$metaKey]) ? $request[$metaKey] : null;
                    $value = $this->sanitize($field, $rValue);
                    if (empty($field['multiple'])) {
                        update_post_meta($post_id, $metaKey, $value);
                    } else {
                        delete_post_meta($post_id, $metaKey);
                        if (is_array($value) && !empty($value)) {
                            foreach ($value as $item) {
                                add_post_meta($post_id, $metaKey, $item);
                            }
                        }
                    }
                }
            }
        }


        /**
         * Sanitize field value
         *
         * @param array $field
         * @param null  $value
         *
         * @return array|null
         * @internal param $value
         */
        function sanitize($field = array(), $value = null) {
            $newValue = null;
            if (is_array($field)) {
                $type = (!empty($field['type']) ? $field['type'] : 'text');
                if (empty($field['multiple'])) {
                    if ($type == 'text' || $type == 'number' || $type == 'select' || $type == 'checkbox' || $type == 'radio') {
                        $newValue = sanitize_text_field($value);
                    } else if ($type == 'url') {
                        $newValue = esc_url($value);
                    } else if ($type == 'slug') {
                        $newValue = sanitize_title_with_dashes($value);
                    } else if ($type == 'textarea') {
                        $newValue = wp_kses_post($value);
                    } else if ($type == 'custom_css') {
                        $newValue = esc_textarea($value);
                    } else if ($type == 'colorpicker') {
                        $newValue = $this->sanitize_hex_color($value);
                    } else if ($type == 'image_size') {
                        $newValue = array();
                        foreach ($value as $k => $v) {
                            if ($k == 'width' || $k == 'height') {
                                $newValue[$k] = absint($v);
                            } else {
                                $newValue[$k] = esc_attr($v);
                            }
                        }
                    } else if ($type == 'style' || $type == 'multiple_options') {
                        $newValue = array();
                        foreach ($value as $k => $v) {
                            $nV = null;
                            if ($k == 'color') {
                                $nV = $this->sanitize_hex_color($v);
                            } else {
                                $nV = $this->sanitize(array('type' => 'text'), $v);
                            }
                            if ($nV) {
                                $newValue[$k] = $nV;
                            }
                        }
                        if (empty($newValue)) {
                            $newValue = null;
                        }
                    } else {
                        $newValue = sanitize_text_field($value);
                    }

                } else {
                    $newValue = array();
                    if (!empty($value)) {
                        if (is_array($value)) {
                            foreach ($value as $key => $val) {
                                if ($type == 'style' && $key == 0) {
                                    if (function_exists('sanitize_hex_color')) {
                                        $newValue = sanitize_hex_color($val);
                                    } else {
                                        $newValue[] = $this->sanitize_hex_color($val);
                                    }
                                } else {
                                    $newValue[] = sanitize_text_field($val);
                                }
                            }
                        } else {
                            $newValue[] = sanitize_text_field($value);
                        }
                    }
                }
            }

            return $newValue;
        }


        function sanitize_hex_color($color) {
            if (function_exists('sanitize_hex_color')) {
                return sanitize_hex_color($color);
            } else {
                if ('' === $color) {
                    return '';
                }

                // 3 or 6 hex digits, or the empty string.
                if (preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
                    return $color;
                }
            }
        }

        function get_ttp_short_description($short_bio, $character_limit = null) {
            return $character_limit && strlen($short_bio) > $character_limit ? substr(strip_tags($short_bio), 0,
                $character_limit) : $short_bio; ////apply_filters( 'the_content', $short_bio )
        }

        function getScTeamMetaFields() {

            return array_merge(
                TLPTeam()->get_sc_layout_settings_meta_fields(),
                TLPTeam()->get_sc_query_filter_meta_fields(),
                TLPTeam()->get_sc_field_style_meta()
            );
        }

        /**
         * @return array
         * Image size
         */
        function get_image_sizes() {
            global $_wp_additional_image_sizes;

            $sizes = array();
            foreach (get_intermediate_image_sizes() as $_size) {
                if (in_array($_size, array('thumbnail', 'medium', 'large'))) {
                    $sizes[$_size]['width'] = get_option("{$_size}_size_w");
                    $sizes[$_size]['height'] = get_option("{$_size}_size_h");
                    $sizes[$_size]['crop'] = (bool)get_option("{$_size}_crop");
                } elseif (isset($_wp_additional_image_sizes[$_size])) {
                    $sizes[$_size] = array(
                        'width'  => $_wp_additional_image_sizes[$_size]['width'],
                        'height' => $_wp_additional_image_sizes[$_size]['height'],
                        'crop'   => $_wp_additional_image_sizes[$_size]['crop'],
                    );
                }
            }

            $imgSize = array();
            foreach ($sizes as $key => $img) {
                $imgSize[$key] = ucfirst($key) . " ({$img['width']}*{$img['height']})";
            }
            $imgSize['ttp_custom'] = __("Custom image size", "tlp-team");

            return $imgSize;
        }

        function getMemberList() {
            $members = array();
            $memberQ = get_posts(array(
                'post_type'      => TLPTeam()->post_type,
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC'
            ));
            if (!empty($memberQ) && is_array($memberQ)) {
                foreach ($memberQ as $member) {
                    $members[$member->ID] = $member->post_title;
                }
            }

            return $members;
        }

        /* Convert hexdec color string to rgb(a) string */
        function TLPhex2rgba($color, $opacity = false) {

            $default = 'rgb(0,0,0)';

            //Return default if no color provided
            if (empty($color)) {
                return $default;
            }

            //Sanitize $color if "#" is provided
            if ($color[0] == '#') {
                $color = substr($color, 1);
            }

            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
            } elseif (strlen($color) == 3) {
                $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
            } else {
                return $default;
            }

            //Convert hexadec to rgb
            $rgb = array_map('hexdec', $hex);

            //Check if opacity is set(rgba or rgb)
            if ($opacity) {
                if (abs($opacity) > 1) {
                    $opacity = 1.0;
                }
                $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
            } else {
                $output = 'rgb(' . implode(",", $rgb) . ')';
            }

            //Return rgb(a) color string
            return $output;
        }

        /**
         * @param     $query
         * @param int $args
         * @param     $scMeta
         *
         * @return string|null
         */
        function custom_pagination($query, $args, $scMeta) {
            $html = null;
            $range = isset($args['posts_per_page']) ? $args['posts_per_page'] : 4;
            $showitems = ($range * 2) + 1;
            global $paged;
            if (empty($paged)) {
                $paged = 1;
            }
            $pages = $query->max_num_pages;
            if (!$pages) {
                global $wp_query;
                $pages = $wp_query->max_num_pages;
                $pages = $pages ? $pages : 1;
            }

            if (1 != $pages) {
                $li = null;
                if (apply_filters('tlp_team_pagination_page_count', true)) {
                    $li .= sprintf('<li class="disabled hidden-xs"><span><span aria-hidden="true">%s</span></span></li>',
                        sprintf(__('Page %d of %d', "tlp-team"), $paged, $pages)
                    );
                }
                if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&laquo;<span class="hidden-xs">%2$s</span></a></li>',
                        get_pagenum_link(1),
                        __("First", "tlp-team")
                    );
                }

                if ($paged > 1 && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&lsaquo;<span class="hidden-xs">%2$s</span></a></li>',
                        get_pagenum_link($paged - 1),
                        __("Previous", "tlp-team")
                    );
                }


                for ($i = 1; $i <= $pages; $i++) {
                    if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                        $li .= $paged == $i ? sprintf('<li class="active"><span>%d</span></li>', $i)
                            : sprintf('<li><a href="%s">%d</a></li>', get_pagenum_link($i), $i);

                    }

                }


                if ($paged < $pages && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&lsaquo;<span class="hidden-xs">%2$s </span></a></li>',
                        get_pagenum_link($paged + 1),
                        __("Next", "tlp-team")
                    );
                }

                if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
                    $li .= sprintf('<li><a href="%1$s" aria-label="%2$s">&raquo;<span class="hidden-xs">%2$s </span></a></li>',
                        get_pagenum_link($pages),
                        __("Last", "tlp-team")
                    );
                }

                $html = sprintf('<div class="tlp-pagination-wrap" data-total-pages="%d" data-posts-per-page="%d">%s</div>',
                    $query->max_num_pages,
                    $args['posts_per_page'],
                    $li ? sprintf('<ul class="tlp-pagination">%s</ul>', $li) : ''
                );

            }

            return apply_filters('tlp_team_pagination_html', $html, $query, $args, $scMeta);

        }

        /**
         * @param null   $post_id
         * @param        $meta_key
         * @param string $type
         *
         * @return bool
         */
        function meta_exist($post_id, $meta_key, $type = "post") {
            if (!$post_id) {
                return false;
            }

            return metadata_exists($type, $post_id, $meta_key);
        }

        function getTTPShortCodeList() {
            $scList = array();
            $scQ = get_posts(array(
                'post_type'      => TLPTeam()->getScPostType(),
                'order_by'       => 'title',
                'order'          => 'ASC',
                'post_status'    => 'publish',
                'posts_per_page' => -1
            ));
            if (!empty($scQ)) {
                $scList = wp_list_pluck($scQ, 'post_title', 'ID');
            }

            return $scList;
        }

        /**
         * Generate ShortCode css
         *
         * @param integer $scID
         *
         * @return void
         */
        function generatorShortCodeCss($scID) {
            $cssFile = TLP_TEAM_PLUGIN_PATH . '/assets/css/sc.css';
            if ($css = TLPTeam()->render('sc-css', compact('scID'), true)) {
                $css = sprintf('/*sc-%2$d-start*/%1$s/*sc-%2$d-end*/', $css, $scID);
                if (file_exists($cssFile) && ($oldCss = file_get_contents($cssFile))) {
                    if(strpos($oldCss, '/*sc-' . $scID . '-start') !== false) {
                        $oldCss = preg_replace('/\/\*sc-' . $scID . '-start[\s\S]+?sc-' . $scID . '-end\*\//', '', $oldCss);
                        $oldCss = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $oldCss);
                    }
                    $css = $oldCss . $css;
                }
                file_put_contents($cssFile, $css);
            }
        }

        /**
         * Generate ShortCode css
         *
         * @param integer $scID
         *
         * @return void
         */
        function removeGeneratorShortCodeCss($scID) {
            $cssFile = TLP_TEAM_PLUGIN_PATH . '/assets/css/sc.css';
            if (file_exists($cssFile) && ($oldCss = file_get_contents($cssFile)) && strpos($oldCss, '/*sc-' . $scID . '-start') !== false) {
                $css = preg_replace('/\/\*sc-' . $scID . '-start[\s\S]+?sc-' . $scID . '-end\*\//', '', $oldCss);
                $css = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $css);
                file_put_contents($cssFile, $css);
            }
        }
    }
endif;
