<?php
header('Content-type: text/css');
ob_start("compress");

    function compress( $minify )
    {
	/* remove comments */
    	$minify = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify );

        /* remove tabs, spaces, newlines, etc. */
    	$minify = str_replace( array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $minify );

        return $minify;
    }


    include('bootstrap.min.css');
    include('bootstrap-theme.min.css');
    include('font-awesome.min.css');

    include('animate.min.css');
    include('top-bar.css');
    include('navigation-menu.css');

    include('jquery.sidr.dark.css');
    include('listing.css');

    include('wordpress-default-min.css');
    include('responsive-grid.css');
    include('responsive.css');

ob_end_flush();