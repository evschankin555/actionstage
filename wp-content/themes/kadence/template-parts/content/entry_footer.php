<?php
/**
 * Template part for displaying a post's footer
 *
 * @package kadence
 */

namespace Kadence;

?>
<footer class="entry-footer">
	<?php
	if ( 'post' === get_post_type() && kadence()->option( 'post_tags' ) ) {
		get_template_part( 'template-parts/content/entry_tags', get_post_type() );
	}
	?>
			<h2 align="center">
			Погода для места «<?php the_field('name_water'); ?>»
		</h2>
		<iframe width="100%" height="450"
        src="https://embed.windy.com/embed2.html?lat=<?php the_field('lat'); ?>&lon=<?php the_field('long'); ?>&detailLat=<?php the_field('lat'); ?>&detailLon=<?php the_field('long'); ?>&width=650&height=450&zoom=11&level=surface&overlay=temp&product=ecmwf&menu=&message=true&marker=true&calendar=24&pressure=true&type=map&location=coordinates&detail=&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1"
        frameborder="0"></iframe>
</footer><!-- .entry-footer -->
