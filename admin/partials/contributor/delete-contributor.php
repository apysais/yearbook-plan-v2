<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <form name="delete-contributor" action="<?php echo $action_url;?>" method="post">
        <h5>Delete Contributor, <?php echo $user_info->display_name;?></h5>
        <?php if($count_user_posts > 0) { ?>
          <div class="alert alert-danger" role="alert">
            <h4>Currently there are <?php echo $count_user_posts;?> task assigned to this user.</h4>
          </div>
        <?php } ?>
        <input type="hidden" name="_method" value="<?php echo $method;?>">
        <?php wp_nonce_field( 'delete_this_user_' . $user_id, 'delete_user' ); ?>
        <button type="submit" class="btn btn-primary">Confirm Deletion</button>
        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
      </form>
    </div>
  </div>
</div>
