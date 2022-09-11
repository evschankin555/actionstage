<?php
/*
 * This is the top bar area. It contains social icons.
 */
?>
<?php if(get_theme_mod('show-top-bar','1') == '1'): ?>

<section class="full-section-10" id="directory-top-bar">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav id="directory-top-navigation">
                    <?php
                    $directory_menu_args = array(
                        'theme_location' => 'top',
                        'container' => false,
                        'menu_id' => false,
                        'menu_class' => 'responsive-menu',
                        'echo' => true);
                    wp_nav_menu($directory_menu_args);
                    ?>
                </nav>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>