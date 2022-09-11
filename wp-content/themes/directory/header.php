<!DOCTYPE html>
<html <?php language_attributes(); ?> prefix="og: http://ogp.me/ns#">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php if ( get_theme_mod( 'show-loader', '1' ) == '1' ): ?>

	<!-- Loader ...-->
	<canvas id="site-loader"></canvas>

<?php endif; ?>

<!-- Load Header area partials -->


<?php get_template_part( 'framework/partials/header-partials/mobile-navigation' ); ?>


<section class="full-section-20<?php echo directory_sticky_menu(); ?>" id="directory-header">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">

				<!-- Logo -->
				<?php get_template_part( 'framework/partials/header-partials/logo' ); ?>

			</div>
			<div class="col-lg-9 clearfix">

				<!-- Main Navigation -->
				<?php get_template_part( 'framework/partials/header-partials/main-navigation' ); ?>

			</div>
		</div>
	</div>
</section>
<?php get_template_part( 'framework/partials/header-partials/top-bar' ); ?>

<!-- Load Header Image -->

<!-- Load Header Image
- You can turn the header image on from the customizer settings.
- If the slider is enabled then the header is not visible.
- Header image should be of 1920x820px
-->
<!--  Enable map or not -->
<?php if ( is_front_page() && get_theme_mod('show-map','0') == '1'): ?>
	<div class="front-map">

	</div>
<?php endif; ?>

<!-- all listings , not shown here-->
<?php
$directory_listing_args  = array( 'post_type' => 'listing', 'posts_per_page' => - 1 );
$directory_listing_query = new WP_Query( $directory_listing_args );

if ( $directory_listing_query->have_posts() ): ?>
	<div class="hidden_listings">
		<?php
		while ( $directory_listing_query->have_posts() ):
			$directory_listing_query->the_post();
			$address = get_post_meta( get_the_ID(), 'add_listing_address_listing_address', true ); ?>

			<span class="single-hidden-listing" data-name="<?php the_title(); ?>" data-address="<?php echo sanitize_text_field( $address ) ?>"></span>

			<?php
		endwhile;
		?>
	</div>
	<?php
endif;
wp_reset_postdata();
?>

<?php if ( get_header_image() != '' && is_front_page() ): ?>
	<section id="directory-header-image">
		<?php echo directory_get_header_image(); ?>
		<div class="photographer-slide-inner">
			<h2 class="photographer-slide-title" <?php echo( ! empty( $color_vars ) ? 'style="color:' . $color_vars["title-color"] . "" : "" ); ?>">
			<?php the_title(); ?>
			</h2>
		</div>
	</section>
<?php endif; ?>
