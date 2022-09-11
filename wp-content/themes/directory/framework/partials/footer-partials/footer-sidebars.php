<?php if (directory_active_footer() !== false): ?>
    <section id="directory-footer-area" class="full-section-60">
        <?php 
        $directory_footer_info = directory_active_footer();
        $directory_footer_class = $directory_footer_info['class'];
        $directory_sidebars_count = $directory_footer_info['sidebars_count'];
        ?>

        <div class="container">
            <div class="row">
                <div id="directory-footer-sidebars">
                    <?php for($i=1; $i<$directory_sidebars_count+1;$i++): ?>

                        <div class="<?php echo $directory_footer_class; ?> directory-footer-sidebar">
                            <?php if (!dynamic_sidebar('directory-footer-'.$i)): ?>
                                <div class="pre-widget">

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
      </div>
    </section>
<?php endif; ?>