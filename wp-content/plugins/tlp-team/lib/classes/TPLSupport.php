<?php
if (!class_exists('TPLSupport')) :

    class TPLSupport
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

        function get_image_sizes() {
            global $_wp_additional_image_sizes;

            $sizes = array();
            $interSizes = get_intermediate_image_sizes();
            if (!empty($interSizes)) {
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
            }

            $imgSize = array();
            if (!empty($sizes)) {
                foreach ($sizes as $key => $img) {
                    $imgSize[$key] = ucfirst($key) . " ({$img['width']}*{$img['height']})";
                }
            }
            $imgSize['rt_custom'] = "Custom image size";

            return $imgSize;
        }

        function getFeatureImageSrc($post_id = null, $fImgSize = 'team-thumb', $customImgSize = array()) {
            $imgSrc = null;
            $cSize = false;
            if ($fImgSize == 'rt_custom') {
                $fImgSize = 'full';
                $cSize = true;
            }

            if ($aID = get_post_thumbnail_id($post_id)) {
                $image = wp_get_attachment_image_src($aID, $fImgSize);
                $imgSrc = $image[0];
            }

            if ($imgSrc && $cSize) {
                $w = (!empty($customImgSize['width']) ? absint($customImgSize['width']) : null);
                $h = (!empty($customImgSize['height']) ? absint($customImgSize['height']) : null);
                $c = (!empty($customImgSize['crop']) && $customImgSize['crop'] == 'soft' ? false : true);
                if ($w && $h) {
                    $imgSrc = TLPTeam()->rtImageReSize($imgSrc, $w, $h, $c, true);
                }
            }

            return $imgSrc;
        }

        /**
         * @param        $post_id
         * @param string $fImgSize
         * @param null   $defaultImgId
         * @param array  $customImgSize
         *
         * @return string|null
         */
        function getFeatureImageHtml($post_id, $fImgSize = 'medium', $defaultImgId = null, $customImgSize = array()) {

            $imgHtml = $imgSrc = $attachment_id = null;
            $cSize = false;
            if ($fImgSize == 'ttp_custom') {
                $fImgSize = 'full';
                $cSize = true;
            }
            $post_title = get_the_title($post_id);
            $attr = [
                'class' => 'img-responsive rt-team-img',
                'alt'   => $post_title
            ];
            if ($aID = get_post_thumbnail_id($post_id)) {
                $imgHtml = wp_get_attachment_image($aID, $fImgSize, false, $attr);
                $attachment_id = $aID;
            }
            if (!$imgHtml && $defaultImgId) {
                $imgHtml = wp_get_attachment_image($defaultImgId, $fImgSize, false, $attr);
                $attachment_id = $defaultImgId;
            }
            if ($imgHtml && $cSize) {
                preg_match('@src="([^"]+)"@', $imgHtml, $match);
                $imgSrc = array_pop($match);
                $w = !empty($customImgSize['width']) ? absint($customImgSize['width']) : null;
                $h = !empty($customImgSize['height']) ? absint($customImgSize['height']) : null;
                $c = !empty($customImgSize['crop']) && $customImgSize['crop'] == 'soft' ? false : true;
                if ($w && $h) {
                    $image = TLPTeam()->rtImageReSize($imgSrc, $w, $h, $c, false);
                    if (!empty($image)) {
                        $attachment = get_post($attachment_id);
                        list($src, $width, $height) = $image;
                        $hwstring = image_hwstring($width, $height);
                        $attr = apply_filters('wp_get_attachment_image_attributes', $attr, $attachment, $fImgSize);
                        $attr['src'] = $src;
                        $attr = array_map('esc_attr', $attr);
                        $imgHtml = rtrim("<img $hwstring");
                        foreach ($attr as $name => $value) {
                            $imgHtml .= " $name=" . '"' . $value . '"';
                        }
                        $imgHtml .= ' />';
                    }
                }
            }
            if (!$imgHtml) {
                $hwstring = image_hwstring(160, 160);
                $attr = apply_filters('wp_get_attachment_image_attributes', $attr, false, $fImgSize);
                $attr['src'] = esc_url(TLPTeam()->assetsUrl . 'images/demo.jpg');
                $imgHtml = rtrim("<img $hwstring");
                foreach ($attr as $name => $value) {
                    $imgHtml .= " $name=" . '"' . $value . '"';
                }
            }

            return $imgHtml;
        }


        function rtImageReSize($url, $width = null, $height = null, $crop = null, $single = true, $upscale = false) {
            $rtResize = new TLPTeamReSizer();

            return $rtResize->process($url, $width, $height, $crop, $single, $upscale);
        }

    }
endif;
