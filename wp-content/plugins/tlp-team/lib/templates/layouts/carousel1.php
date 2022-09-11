<?php
$image  = null;
if ( $imgHtml ) {
	$image .= '<div class="round-img">';
	if ( $disable_detail_page_link ) {
		$image .= $imgHtml;
	} else {
		$image .= '<a title="' . $title . '" href="' . $pLink . '">' . $imgHtml . '</a>';
	}
	$image .= '</div>';
}

$designation_html = null;
if ( $designation ) {
	$designation_html .= sprintf( '<div class="designation">%s</div>', $designation );
}
$short_bio_html = null;
if ( $short_bio ) {
	$short_bio_html .= sprintf( '<div class="short-bio"><p>%s</p></div>', $short_bio );
}
$sLink_html = null;
if ( $sLink ) {
	foreach ( $sLink as $id => $link ) {
		$sLink_html .= "<a href='{$sLink[$id]}' title='$id' target='_blank'><i class='fa fa-$id'></i></a>";
	}
	$sLink_html = $sLink_html ? sprintf( '<div class="tpl-social">%s</div>', $sLink_html ) : null;
}
?>
<div class="team-member tlp-equal-height <?php echo esc_attr( $grid ) ?>">
    <div class="single-team-area">
		<?php printf("%s", $image); ?>
        <div class="tlp-team-content">
            <h3 class="name">
				<?php if ( $disable_detail_page_link ) {
					echo esc_html( $title );
				} else {
					printf( '<a title="%1$s" href="%2$s">%1$s</a>', esc_html( $title ), esc_url( $pLink ) );
				} ?>
            </h3>
			<?php echo $designation_html ?>
			<?php echo $short_bio_html ?>
			<?php echo $sLink_html; ?>
        </div>
    </div>
</div>
