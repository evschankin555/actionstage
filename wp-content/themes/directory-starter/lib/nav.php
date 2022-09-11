<?php
$menus = array(
		'primary-menu' => __( 'Primary Menu', 'directory-starter' ),   // main menu for header
		'footer-links' => __( 'Footer Links', 'directory-starter' )    // links menu for footer
);
$enable_header_top = esc_attr(get_theme_mod('dt_enable_header_top', DT_ENABLE_HEADER_TOP));
if ($enable_header_top == '1') {
	$menus['header-top'] = __( 'Header Top', 'directory-starter' );
}
// register nav menus
register_nav_menus($menus);

add_filter( 'nav_menu_css_class', 'ds_menu_li_class' , 10, 3 );
add_filter( 'nav_menu_submenu_css_class', 'ds_sub_menu_ul_class' , 10, 3 );
add_filter( 'nav_menu_link_attributes', 'ds_menu_a_class' , 10, 3 );

/**
 * Modify the menu classes to add bootstrap styling.
 *
 * @param $atts
 * @param $item
 * @param $args
 *
 * @return mixed
 */
function ds_menu_a_class( $atts, $item, $args ) {

//	print_r($args);

	isset( $atts['class'] ) ? $atts['class'] .= " nav-link" : $atts['class'] = " nav-link";

	if (isset($args->menu_class) && strpos($args->menu_class, 'navbar-nav') === false) {
		$atts['class'] .= ' p-0';
	}

	// add sub-menu class if needed
	if ( in_array( 'menu-item-has-children', $item->classes ) ) {
		$atts['class'] .= ' dropdown-toggle';
		$atts['role']          = 'button';
		$atts['data-toggle']   = 'dropdown';
		$atts['aria-haspopup'] = 'true';
		$atts['aria-expanded'] = 'false';
	}

	// add menu dropdown item
	if ( ! empty( $item->menu_item_parent ) ) {
		$atts['class'] .= ' dropdown-item'; // deliberately remove other classes for styling
	}


	return $atts;
}

/**
 * Modify the menu classes to add bootstrap styling.
 *
 * @param $classes
 * @param $args
 * @param $depth
 *
 * @return array
 */
function ds_sub_menu_ul_class( $classes, $args, $depth ) {

	// add needed bootstrap class
	if (isset($args->menu_class) && strpos($args->menu_class, 'navbar-nav') === false) {
		$classes[] = 'dropdown-menu dropdown-caret-0';
	}else{
		$classes[] = 'dropdown-menu dropdown-menu-right dropdown-caret-0';
	}


	return $classes;
}

/**
 * Modify the menu classes to add bootstrap styling.
 *
 * @param $classes
 * @param $item
 * @param $args
 *
 * @return array
 */
function ds_menu_li_class( $classes, $item, $args ) {

	// add needed bootstrap class
	$classes[] = 'nav-item';

	// add sub-menu class if needed
	if ( in_array( 'menu-item-has-children', $classes ) ) {
		$classes[] = 'dropdown';
	}



	return $classes;
}