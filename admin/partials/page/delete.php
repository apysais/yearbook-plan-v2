<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <form name="delete-yearbook" action="<?php echo $action_url;?>" method="post">
        <h5>Delete YearBook : <?php echo $yearbook_post->post_title;?></h5>
        <input type="hidden" name="_method" value="<?php echo $method;?>">
        <?php wp_nonce_field( 'delete_this_yearbook_' . $yearbook_id, 'delete_yearbook' ); ?>
        <button type="submit" class="btn btn-primary">Confirm Deletion</button>
        <input type="hidden" name="yearbook_id" value="<?php echo $yearbook_id;?>">
      </form>
    </div>
  </div>
</div>
