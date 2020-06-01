<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <form name="delete-yearbook-block" action="<?php echo $action_url;?>" method="post">
        <h5>Delete Block : <?php echo $posts->post_title;?></h5>
        <input type="hidden" name="_method" value="<?php echo $method;?>">
        <input type="hidden" name="yb_id" value="<?php echo $yb_id;?>">
        <input type="hidden" name="school_id" value="<?php echo $school_id;?>">
        <?php wp_nonce_field( 'delete_this_block_' . $posts->ID, 'delete_block' ); ?>
        <button type="submit" class="btn btn-primary">Confirm Deletion</button>
        <input type="hidden" name="block_id" value="<?php echo $posts->ID;?>">
      </form>
    </div>
  </div>
</div>
