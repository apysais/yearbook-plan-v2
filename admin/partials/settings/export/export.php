<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <h1>Export Result</h1>
      <?php //YB_View::get_instance()->admin_partials('partials/account/list-users.php', $data); ?>
      <?php
        if($result) {
          echo '<ul>';
          foreach($result as $k => $v){
            echo '<li>';
              echo $v;
            echo '</li>';
          }
          echo '</ul>';
        }
        echo $go_back;
      ?>
    </div>
  </div>
</div>
