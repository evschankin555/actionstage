<!-- begin Tag Groups plugin -->
<script>
  if (typeof jQuery !== 'undefined' && typeof jQuery.ui !== 'undefined' && typeof jQuery.ui.accordion !== 'undefined' && typeof jQuery.widget !== 'undefined' && typeof TagGroupsBase !== 'undefined') {
    TagGroupsBase.accordion('<?php echo $id ?>', <?php echo $options_js_object ?>, <?php echo $delay ? 'true' : 'false' ?>);
  } else {
    jQuery(document).ready(function(){
      setTimeout(function(){TagGroupsBase.accordion('<?php echo $id ?>', <?php echo $options_js_object ?>, <?php echo $delay ? 'true' : 'false' ?>)}, 500);
    });
  }
</script>
<!-- end Tag Groups plugin -->