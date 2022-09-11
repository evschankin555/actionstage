/*
 ** This is the main js file. However , we use the compressed one with the same name and the .min extension **
 * TOC
 ** 1. function directory_menu_height
 ** 2. Add Responsive Menu
 */

function directory_menu_height() {

    var outer_header_height = jQuery('section#directory-header').outerHeight();
    var inner_logo_height = jQuery('section#directory-logo').height();
    var total_menu_item_height = inner_logo_height + 20;

    jQuery('section#directory-header').css('max-height', outer_header_height + 'px');
    jQuery('#directory-main-navigation').css('line-height', inner_logo_height + 'px');
    jQuery('#directory-main-navigation ul li a').not('.sub-menu li a').not('.sub-menu ul li ul li a').css('height', total_menu_item_height + 'px');
}

function directory_responsive_menu() {
    jQuery('#responsive-menu-button').sidr({
        name: 'directory-mobile-navigation'
    });
}

function directory_sticky_menu() {

    var width = jQuery(window).width();
    if (width > 992) {
        jQuery('.isSticky').sticky({topSpacing: 0});
    }

}


function directory_matchHeight() {
    jQuery('.matchHeight').matchHeight();
}

jQuery(window).load(function () {

    jQuery('#site-loader').fadeOut();
    directory_menu_height();
    jQuery('ul.nav-tabs li:first-child').addClass('active');
});

jQuery(document).ready(function () {
    'use-strict';

    directory_responsive_menu();
    directory_sticky_menu();
    directory_matchHeight();
});