<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <h1>Export</h1>
      <h2>Choose What to Export</h2>

      <?php //YB_View::get_instance()->admin_partials('partials/account/list-users.php', $data); ?>
      <?php
        if($articles) {
          echo '<ul>';
          foreach($articles as $k => $v){
            echo '<li>';
              echo '<a href="?export=1&id='.$v->ID.'">';
                echo $v->post_title;
              echo '</a>';
            echo '</li>';
          }
          echo '</ul>';
        }
      ?>
    </div>
  </div>
</div>
