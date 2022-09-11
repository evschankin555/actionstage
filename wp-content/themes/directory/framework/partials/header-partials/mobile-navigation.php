<div id="mobile-header">
    <a id="responsive-menu-button" href="#directory-mobile-navigation"><i class="fa fa-bars"></i></a>
    <nav id="directory-mobile-navigation">
        <?php
        $directory_menu_args = array(
            'theme_location' => 'main',
            'container' => false,
            'menu_id' => false,
            'menu_class' => 'responsive-menu',
            'echo' => true);
        wp_nav_menu($directory_menu_args);
        ?>
    </nav>
</div>