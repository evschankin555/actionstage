<?php
/**
 * Kadence functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kadence
 */

add_action('pmxi_saved_post', 'wp_all_import_post_saved', 1000, 1);

function wp_all_import_post_saved($id) {
    do_action('search_filter_update_post_cache', $id);
}

define( 'KADENCE_VERSION', '1.0.7' );
define( 'KADENCE_MINIMUM_WP_VERSION', '5.2' );
define( 'KADENCE_MINIMUM_PHP_VERSION', '7.0' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], KADENCE_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), KADENCE_MINIMUM_PHP_VERSION, '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Load the `kadence()` entry point function.
require get_template_directory() . '/inc/class-theme.php';

// Load the `kadence()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'Kadence\kadence' );

function fr_scripts() {
	wp_enqueue_style(  'fr-custom', get_template_directory_uri() . '/assets/css/custom.css', array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'fr_scripts' );

add_action( 'kadence_after_header', 'fr_searchandfilter' );

add_filter('wpseo_canonical', 'my_wpseo_canonical');

add_filter('comment_form_default_fields', 'wpcourses_unset_url_field');
function wpcourses_unset_url_field ( $fields ) {
  if ( isset($fields['url'] ))
  unset ( $fields['url'] );
  return $fields;
}

/*** Функция вывода rel="canonical" ***/ 
remove_action('wp_head', 'rel_canonical');
function mayak_wp_canonical(){
if ( !is_singular() )
		return;
	global $wp_the_query;
	if ( !$id = $wp_the_query->get_queried_object_id() )
		return;
	$link = get_permalink( $id );
	if ( $page = get_query_var('cpage') )
		$link = get_comments_pagenum_link( $page );
	echo "<link rel='canonical' href='$link' />\n";
}
add_action('wp_head', 'mayak_wp_canonical',3);
function mayak_canonical(){
		if (is_home() ) {
			$mayak_chief_link = get_option('home');
			$mayak_home_link = mayak_link_paged($mayak_chief_link);
			{
		echo "".'<link rel="canonical" href="'.$mayak_home_link.'" />'."\n"; 
	}
} else if (is_category()) {
			$mayak_cat_link = get_category_link(get_query_var('cat'));
			$mayak_category_link = mayak_link_paged($mayak_cat_link);
			{
		echo "".'<link rel="canonical" href="'.$mayak_category_link.'" />'."\n"; 
	}
} else if (function_exists('is_tag') && is_tag()){
			$tag = get_term_by('slug',get_query_var('tag'),'post_tag');
		if (!empty($tag->term_id)) {
	        $tag_link = get_tag_link($tag->term_id);
	        } 
			$mayak_tag_link = mayak_link_paged($tag_link);
			$mayak_tag_link = trailingslashit($mayak_tag_link);
		   {
		echo "".'<link rel="canonical" href="'.$mayak_tag_link.'" />'."\n"; 
	}
} else if (is_author()){
			global $cache_userdata;
	        $userid = get_query_var('author');
	        $mayak_auth_link = get_author_posts_url ( 'ID' );
		$mayak_author_link = mayak_link_paged($mayak_auth_link);
        {
		echo "".'<link rel="canonical" href="'.$mayak_author_link.'" />'."\n"; 
	}
} 
else if (is_date()){
if (get_query_var('m')) {
		        $m = preg_replace('/[^0-9]/', '', get_query_var('m'));
		        switch (strlen($m)) {
		            case 0: 
		                $mayak_date_link = get_year_link($m);
						$mayak_date_link = mayak_link_paged( $mayak_date_link );
		                break;
		            case 1: 
		                $mayak_date_link = get_month_link(substr($m, 0, 4), substr($m, 4, 2));
						$mayak_date_link = mayak_link_paged( $mayak_date_link );
		                break;
		            case 2: 
		                $mayak_date_link = get_day_link( substr($m, 0, 4), substr($m, 4, 2), substr($m, 6, 2));
						$mayak_date_link = mayak_link_paged( $mayak_date_link );					 
		                break;
		            default:
		                $mayak_date_link = '';
		        }
				}
				if (is_day()) {
		        $mayak_date_link = get_day_link(get_query_var('year'),	get_query_var('monthnum'), get_query_var('day'));
				$mayak_date_link = mayak_link_paged($mayak_date_link);					 
		    } else if (is_month()) {
		        $mayak_date_link = get_month_link(get_query_var('year'), get_query_var('monthnum'));
				$mayak_date_link = mayak_link_paged($mayak_date_link);					   
		    } else if (is_year()) {
		        $mayak_date_link = get_year_link(get_query_var('year'));
				$mayak_date_link = mayak_link_paged($mayak_date_link);
		    }
		{
		echo "".'<link rel="canonical" href="'.$mayak_date_link.'" />'."\n"; 
		}
	}
}
function mayak_link_paged($link) {
			$mayak_page = get_query_var('paged');
			$mayak_check = function_exists('user_trailingslashit');
	    if ($mayak_page && $mayak_page > 1) {
	        $link = trailingslashit($link) ."page/". "$mayak_page";
	        if ($mayak_check) {
	            $link = user_trailingslashit($link, 'paged');
	        } else {
	            $link .= '/';
	        }
		}
			return $link;
	}
add_action('wp_head', 'mayak_canonical');

/*** Конец функции вывода rel="canonical" ***/

#function my_wpseo_canonical($canonical) {
#    if (is_paged()) {
#        if (is_home()) {
#            return home_url();
#        }

#        if (is_archive()) {
#            $url = get_category_link(get_queried_object_id());
#            return $url;
#        }
#    }

#    return $canonical;
#}

function fr_searchandfilter(){
	if(!is_single()){
	?>
		<div class="content-container site-container fr-filter">
			<?php echo do_shortcode('[searchandfilter id="3239"]'); ?>
		</div>
	<?php
	}
}