<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <h3>YearBook Planner Account</h3>
      <?php yb_get_validation(); ?>
      <form name="create-new-yearbook-plan" action="<?php echo $action_url;?>" method="post">
        <input type="hidden" name="_method" value="<?php echo $method;?>">
        <?php YB_View::get_instance()->admin_partials('partials/account/form-fields.php', $data); ?>
        <button type="submit" class="btn btn-primary">Submit</button>
        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
      </form>
    </div>
  </div>
</div>
