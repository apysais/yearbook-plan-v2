<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <h1>Settings</h1>

      <!-- clean db -->
        <?php
          $base_url_cleandb = admin_url('network/admin.php?page=yearbook-settings&action=cleandb');
          $nonce_clean_db   = wp_nonce_url( $base_url_cleandb, 'cleandb', 'cleandb-nonce' );
        ?>
        <a href="<?php echo $nonce_clean_db; ?>" class="btn btn-danger cleanup-db btn-sm" name="clean-db" >Clean DB</a>
      <!-- clean db -->


    </div>
  </div>
</div>
