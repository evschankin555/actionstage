<?php
/*
 * This is the partial that contains the logo.
 */
$is_sticky =  get_theme_mod('sticky-menu','');
$logo = get_theme_mod('logo-upload','');
$alt_logo  =  get_theme_mod('sticky-logo-upload','');
?>
<section id="directory-logo" <?php echo ($alt_logo != '' ? 'data-sticky-logo="'.$alt_logo.'"' : false); ?> data-logo = "<?php echo $logo; ?>">
    <?php echo directory_logo(); ?>
</section>