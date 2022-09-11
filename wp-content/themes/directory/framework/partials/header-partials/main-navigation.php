<?php
/*
 * Main Navigation
 */
?>
<nav id="directory-main-navigation">
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

