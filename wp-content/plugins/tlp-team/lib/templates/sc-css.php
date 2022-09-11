<?php

/**
 * @var integer $scID
 * @var bool    $old
 * @var array   $old
 * @var bool    $preview
 */
$css = null;
$selector = '.tlp-team.rt-team-container-' . $scID;

if (!empty($old)) {
    $name = isset($scMeta['name-color']) && !empty($scMeta['name-color']) ? $scMeta['name-color'] : null;
    $designation = isset($scMeta['designation-color']) && !empty($scMeta['designation-color']) ? $scMeta['designation-color'] : null;
    $sd = isset($scMeta['sd-color']) && !empty($scMeta['sd-color']) ? $scMeta['sd-color'] : null;
    if ($name) {
        $css .= "$selector .single-team-area h3,
							$selector .single-team-area h3 a{ color: {$name};}";
    }
    if ($designation) {
        $css .= "$selector .single-team-area .designation{ color: {$designation};}";
    }
    if ($sd) {
        $css .= "$selector .single-team-area .short-bio{ color: {$sd};}";
    }

} else {
    if (empty($scID) && !empty($scMeta)) {
        $primaryColor = isset($scMeta['primary_color']) && !empty($scMeta['primary_color']) ? $scMeta['primary_color'] : null;
        $button = isset($scMeta['ttp_button_style']) && !empty($scMeta['ttp_button_style']) ? $scMeta['ttp_button_style'] : null;
        $name = isset($scMeta['name']) && !empty($scMeta['name']) ? $scMeta['name'] : null;
        $designation = isset($scMeta['designation']) && !empty($scMeta['designation']) ? $scMeta['designation'] : null;
        $short_bio = isset($scMeta['short_bio']) && !empty($scMeta['short_bio']) ? $scMeta['short_bio'] : null;
    } else {
        $scMeta = get_post_meta($scID);
        $primaryColor = isset($scMeta['primary_color'][0]) && !empty($scMeta['primary_color'][0]) ? $scMeta['primary_color'][0] : null;
        $button = isset($scMeta['ttp_button_style'][0]) && !empty($scMeta['ttp_button_style'][0]) ? unserialize($scMeta['ttp_button_style'][0]) : null;
        $name = isset($scMeta['name'][0]) && !empty($scMeta['name'][0]) ? unserialize($scMeta['name'][0]) : null;
        $designation = isset($scMeta['designation'][0]) && !empty($scMeta['designation'][0]) ? unserialize($scMeta['designation'][0]) : null;
        $short_bio = isset($scMeta['short_bio'][0]) && !empty($scMeta['short_bio'][0]) ? unserialize($scMeta['short_bio'][0]) : null;
    }

    if ($primaryColor) {
        $css .= "$selector .tlp-content,$selector .layout1 .tlp-content{background:{$primaryColor };}";
        $css .= "$selector .short-desc,$selector .tlp-team-isotope .tlp-content, $selector .tpl-social a, $selector .tpl-social li a.fa{background: $primaryColor;}";
    }

    /* button */
    if (!empty($button)) {
        if (isset($button['bg']) && !empty($button['bg'])) {
            $css .= "$selector .tlp-pagination-wrap .tlp-pagination > li > a, $selector .tlp-pagination-wrap .tlp-pagination > li > span,$selector .tlp-isotope-buttons.button-group button,$selector .owl-theme .owl-nav [class*=owl-],$selector .owl-theme .owl-dots .owl-dot span{";
            $css .= "background-color: {$button['bg']};";
            $css .= "}";
        }
        if (!empty($button['hover_bg'])) {
            $css .= "$selector .tlp-pagination-wrap .tlp-pagination > li > a:hover, $selector .tlp-pagination-wrap .tlp-pagination > li > span:hover,$selector .owl-theme .owl-dots .owl-dot span:hover,$selector .owl-theme .owl-nav [class*=owl-]:hover,$selector .tlp-isotope-buttons.button-group button:hover{";
            $css .= "background-color: {$button['hover_bg']};";
            $css .= "}";
        }
        if (!empty($button['active_bg'])) {
            $css .= "$selector .tlp-isotope-buttons.button-group button.selected,$selector .owl-theme .owl-dots .owl-dot.active span,$selector .tlp-pagination-wrap .tlp-pagination > .active > span{";
            $css .= "background-color: {$button['active_bg']};";
            $css .= "}";
        }
        if (!empty($button['text'])) {
            $css .= "$selector .tlp-pagination-wrap .tlp-pagination > li > a, $selector .tlp-pagination-wrap .tlp-pagination > li > span,$selector .tlp-isotope-buttons.button-group button,$selector .owl-theme .owl-nav [class*=owl-]{";
            $css .= "color: {$button['text']};";
            $css .= "}";
        }
        if (!empty($button['hover_text'])) {
            $css .= "$selector .tlp-pagination-wrap .tlp-pagination > li > a:hover, $selector .tlp-pagination-wrap .tlp-pagination > li > span:hover,$selector .tlp-isotope-buttons.button-group button:hover,$selector .owl-theme .owl-nav [class*=owl-]:hover{";
            $css .= "color: {$button['hover_text']};";
            $css .= "}";
        }
    }

    // Name
    if (!empty($name)) {
        $cCss = null;
        $cCss .= isset($name['color']) && !empty($name['color']) ? "color:" . $name['color'] . ";" : null;
        $cCss .= isset($name['align']) && !empty($name['align']) ? "text-align:" . $name['align'] . ";" : null;
        $cCss .= isset($name['size']) && !empty($name['size']) ? "font-size:" . $name['size'] . "px;" : null;
        $cCss .= isset($name['weight']) && !empty($name['weight']) ? "font-weight:" . $name['weight'] . ";" : null;
        if ($cCss) {
            $css .= "$selector .single-team-area h3,$selector .single-team-area h3 a{ {$cCss} }";
        }
        if (isset($name['hover_color']) && !empty($name['hover_color'])) {
            $css .= "$selector .single-team-area h3:hover,$selector .single-team-area h3 a:hover{ color: {$name['hover_color']}; }";
        }

    }
    // Designation
    if (!empty($designation)) {
        $cCss = null;
        $cCss .= isset($designation['color']) && !empty($designation['color']) ? "color:" . $designation['color'] . ";" : null;
        $cCss .= isset($designation['align']) && !empty($designation['align']) ? "text-align:" . $designation['align'] . ";" : null;
        $cCss .= isset($designation['size']) && !empty($designation['size']) ? "font-size:" . $designation['size'] . "px;" : null;
        $cCss .= isset($designation['weight']) && !empty($designation['weight']) ? "font-weight:" . $designation['weight'] . ";" : null;

        $css .= "$selector .designation,$selector .designation a{ {$cCss} }";

        if (isset($designation['hover_color']) && !empty($designation['hover_color'])) {
            $css .= "$selector .designation:hover,$selector .designation a:hover{ color: {$designation['hover_color']}; }";
        }
    }

    // Short biography
    if (!empty($short_bio)) {
        $cCss = null;
        $cCss .= isset($short_bio['color']) && !empty($short_bio['color']) ? "color:" . $short_bio['color'] . ";" : null;
        $cCss .= isset($short_bio['align']) && !empty($short_bio['align']) ? "text-align:" . $short_bio['align'] . ";" : null;
        $cCss .= isset($short_bio['size']) && !empty($short_bio['size']) ? "font-size:" . $short_bio['size'] . "px;" : null;
        $cCss .= isset($short_bio['weight']) && !empty($short_bio['weight']) ? "font-weight:" . $short_bio['weight'] . ";" : null;
        $css .= "$selector .short-bio p,$selector .short-bio p a{{$cCss}}";
        if (isset($short_bio['hover_color']) && !empty($short_bio['hover_color'])) {
            $css .= "$selector .designation:hover,$selector .designation a:hover{ color: {$short_bio['hover_color']}; }";
        }
    }
}

if ($css) {
    echo $css;
}