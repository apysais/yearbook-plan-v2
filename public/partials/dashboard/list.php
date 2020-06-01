<?php get_header(); ?>
<div class="bootstrap-iso">
  <div class="container">
    <?php
      $partial_template = YB_View::get_instance()->public_part_partials('partials/dashboard/list-partials.php');
      YB_View::get_instance()->display($partial_template, $data);
    ?>
  </div>
</div>
<?php get_footer(); ?>
