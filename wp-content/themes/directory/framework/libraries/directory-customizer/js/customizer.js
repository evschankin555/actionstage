(function ($) {

    // Site title and description.
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.site-title a').text(to);
        });
    });

    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });

    wp.customize('show-top-bar', function (value) {
        value.bind(function (to) {
            $('#directory-top-bar').show();
        });
    });

    /*****
     * TOP BAR
     */
    wp.customize('top-bar-bg', function (value) {
        value.bind(function (to) {
            $('#directory-top-bar').css('background-color', to);
        });
    });

    wp.customize('top-bar-link-color', function (value) {
        value.bind(function (to) {
            $('#college-top-navigation ul li a').css('color', to);
        });
    });

    /**
     * TOP BAR social icons
     */
    wp.customize('social-icons-color', function (value) {
        value.bind(function (to) {
            $('#directory-socials-list li a').css('color', to);
        });
    });

    /**
     * Site title & tagline
     */
    wp.customize('site-title-color', function (value) {
        value.bind(function (to) {
            $('#directory-logo > h1 > a').css('color', to);
        });
    });

    wp.customize('site-tagline-color', function (value) {
        value.bind(function (to) {
            $('#directory-logo > h5').css('color', to);
        });
    });

    /**
     * Header Bg
     */
    wp.customize('header-bg', function (value) {
        value.bind(function (to) {
            $('#directory-header').css('background', to);
        });
    });

    /**
     * Main navigation
     */
    wp.customize('main-nav-link-color', function (value) {
        value.bind(function (to) {
            $('#directory-header a').css('color', to);
        });
    });

    wp.customize('submenu-bg', function (value) {
        value.bind(function (to) {
            $('#directory-main-navigation ul ul').css('background', to);
        });
    });

    wp.customize('submenu-border-color', function (value) {
        value.bind(function (to) {
            $('#directory-main-navigation ul ul').css('border-color', to);
        });
    });

    /** Main elements **/

    wp.customize('main-headers-color', function (value) {
        value.bind(function (to) {
            $('#main-entry-content h1').css('color', to);
            $('#main-entry-content h2').css('color', to);
            $('#main-entry-content h3').css('color', to);
            $('#main-entry-content h4').css('color', to);
            $('#main-entry-content h5').css('color', to);
            $('#main-entry-content h6').css('color', to);
        });
    });

    wp.customize('main-body-color', function (value) {
        value.bind(function (to) {
            $('#main-entry-content').css('color', to);
        });
    });

    wp.customize('main-links-color', function (value) {
        value.bind(function (to) {
            $('#main-entry-content a').css('color', to);
        });
    });

    /** Extra page elements **/

    wp.customize('single-title-container-bg', function (value) {
        value.bind(function (to) {
            $('#single-title-container').css('background', to);
            $('#single-breadcrumb-container').css('background', to);
        });
    });

    wp.customize('single-title-color', function (value) {
        value.bind(function (to) {
            $('h1#single-title').css('color', to);
        });
    });

    wp.customize('breadcrumb-links-color', function (value) {
        value.bind(function (to) {
            $('#single-breadcrumb li a').css('color', to);
        });
    });

    /** Comments **/

    wp.customize('comments-title', function (value) {
        value.bind(function (to) {
            $('.comments-title').css('color', to);
        });
    });

    wp.customize('comments-body', function (value) {
        value.bind(function (to) {
            $('.directory_comment_body').css('color', to);
        });
    });

    wp.customize('comments-button-bg', function (value) {
        value.bind(function (to) {
            $('.form-submit > input#submit').css('background', to);
        });
    });

    wp.customize('comments-button-text', function (value) {
        value.bind(function (to) {
            $('.form-submit > input#submit').css('color', to);
        });
    });

    wp.customize('comments-button-border', function (value) {
        value.bind(function (to) {
            $('.form-submit > input#submit').css('border-color', to);
        });
    });

    /** Main Widgets **/

    wp.customize('widget-title', function (value) {
        value.bind(function (to) {
            $('.widget h3').css('color', to);
        });
    });

    wp.customize('widget-body', function (value) {
        value.bind(function (to) {
            $('.widget').css('color', to);
        });
    });

    wp.customize('widget-link-color', function (value) {
        value.bind(function (to) {
            $('.widget a').css('color', to);
        });
    });

    /** Footer Widgets **/

    wp.customize('footer-bg', function (value) {
        value.bind(function (to) {
            $('#directory-footer-area').css('background', to);
        });
    });

    wp.customize('footer-widget-title', function (value) {
        value.bind(function (to) {
            $('.footerwidget h3').css('color', to);
        });
    });

    wp.customize('footer-widget-body', function (value) {
        value.bind(function (to) {
            $('.footerwidget').css('color', to);
        });
    });

    wp.customize('footer-widget-link-color', function (value) {
        value.bind(function (to) {
            $('.footerwidget a').css('color', to);
        });
    });

    /** Copyright area **/

    wp.customize('copyright-area-bg', function (value) {
        value.bind(function (to) {
            $('#directory-copyright').css('background', to).css('border-top-color', to);
        });
    });

    wp.customize('copyright-area-body', function (value) {
        value.bind(function (to) {
            $('#directory-copyright').css('color', to);
        });
    });
    wp.customize('copyright-area-links-color', function (value) {
        value.bind(function (to) {
            $('#directory-copyright a').css('color', to);
        });
    });

})(jQuery);
