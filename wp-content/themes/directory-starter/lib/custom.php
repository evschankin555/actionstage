<?php
function ds_add_top_header() {
    $enable_header_top = esc_attr(get_theme_mod('dt_enable_header_top', DT_ENABLE_HEADER_TOP));
    if ($enable_header_top != '1') {
        return;
    }
    ?>
    <div class="ds-top-header ">
        <nav class="navbar navbar-expand-lg navbar-dark <?php if(get_theme_mod('dt_container_full', DT_CONTAINER_FULL)){echo 'container-fluid';}else{ echo "container";}?>">
            <?php
            if ( display_header_text() )  {
                echo get_bloginfo( 'description', 'display' );
            }
            ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#top-nav" aria-controls="top-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="top-nav">
                <?php if ( has_nav_menu( 'header-top' ) ) {
                    wp_nav_menu( array(
                        'container'      => false,
                        'theme_location' => 'header-top',
                        'menu_class' => 'navbar-nav ml-auto  text-nowrap flex-wrap',
                        'fallback_cb'   => false
                    ) );
                }
                ?>
            </div>
        </nav>
    </div>



    <?php
}
add_action('dt_before_header', 'ds_add_top_header');