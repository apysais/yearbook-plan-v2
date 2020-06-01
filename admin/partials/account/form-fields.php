<h3>Account Details</h3>
<div class="form-group">
  <label for="InputName">Name</label>
  <input type="text" class="form-control input-sm" name="account_name" value="<?php echo $account_name;?>" id="InputName" >
</div>
<h3>Login Details</h3>
<div class="form-group">
  <label for="InputUserName">Username</label>
  <?php if($method == 'update') { ?>
    <p><?php echo $username;?></p>
  <?php }else{ ?>
        <input type="text" class="form-control input-sm" name="username" value="<?php echo $username;?>" id="InputUserName">
  <?php } ?>

</div>
<div class="form-group">
  <label for="InputEmail">Email address</label>
  <input type="email" class="form-control input-sm" name="email" value="<?php echo $email;?>" id="InputEmail" >
</div>
<div class="form-group">
  <label for="InputPassword">Password</label>
  <?php if($show_random_password) { ?>
    <p>Random Password: <?php echo $password;?></p>
  <?php } ?>
  <input type="password" class="form-control input-sm" name="password" value="<?php echo $password;?>" id="InputPassword" >
</div>
