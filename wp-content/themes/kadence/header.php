<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kadence
 */
 
namespace Kadence;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js" <?php kadence()->print_microdata( 'html' ); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(70710358, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/70710358" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-TSTBJ6XT65"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-TSTBJ6XT65');
</script>
	<link rel="stylesheet" href="https://fishing-report.ru/wp-content/fontawesome/css/all.min.css">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
/**
 * Kadence before wrapper hook.
 */
do_action( 'kadence_before_wrapper' );
?>
<div id="wrapper" class="site">
	<?php
	/**
	 * Kadence before header hook.
	 *
	 * @hooked kadence_do_skip_to_content_link - 2
	 */
	do_action( 'kadence_before_header' );

	/**
	 * Kadence header hook.
	 *
	 * @hooked Kadence/header_markup - 10
	 */
	do_action( 'kadence_header' );

	do_action( 'kadence_after_header' );
	?>

	<div id="inner-wrap" class="wrap hfeed kt-clear">
		<?php
		/**
		 * Hook for top of inner wrap.
		 */
		do_action( 'kadence_before_content' );
		?>
