<?php
$image = $inner_title = null;
if($imgHtml) {
    if ($disable_detail_page_link) {
        $image = $imgHtml;
    } else {
        $image = '<a title="' . $title . '" href="' . $pLink . '">'.$imgHtml.'</a>';
    }
}
if ($disable_detail_page_link) {
    $inner_title = '<h3 class="name">' . $title . '</h3>';
} else {
    $inner_title = '<h3 class="name"><a title="' . $title . '" href="' . $pLink . '">' . $title . '</a></h3>';
}
$designation_html = null;
if ($designation) {
    $designation_html .= sprintf('<div class="designation">%s</div>', $designation);
}
?>
<div class="team-member tlp-equal-height <?php echo esc_attr($grid) ?>">
    <div class="single-team-area">
        <?php echo $image; ?>
        <div class="tlp-content">
            <?php echo $inner_title; ?>
            <?php echo $designation_html ?>
        </div>
    </div>
</div>
