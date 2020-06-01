<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <h3>YearBook Planner Account</h3>
      <?php yb_get_validation(); ?>
      <form name="create-new-yearbook-plan" action="<?php echo $action_url;?>" method="post">
        <input type="hidden" name="_method" value="<?php echo $method;?>">
        <?php YB_View::get_instance()->admin_partials('partials/account/form-fields.php', $data); ?>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="send_login_details"> Send the new user an email about their account.
          </label>
        </div>
        <button type="submit" class="btn btn-primary btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
