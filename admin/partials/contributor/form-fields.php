<h3>Contributor Details</h3>
<div class="form-group">
  <label for="InputName">Name</label>
  <input type="text" class="form-control input-sm" name="contributor_name" value="<?php echo $contributor_name;?>" id="InputName" autocomplete="off">
</div>
<?php if($is_admin) { ?>
    <label for="selectAccount">Assign to School Admin</label>
    <?php if($accounts) { ?>
        <select name="school_admin" class="form-control input-sm">
          <?php foreach($accounts as $k => $v) { ?>
                <option value="<?php echo $v->ID;?>" <?php echo ($school_admin_id==$v->ID) ? 'selected':'';?>><?php echo $obj_account_meta->account_name(['user_id'=>$v->ID,'single'=>true]);?></option>
          <?php } ?>
        </select>
    <?php } ?>
<?php }else{ ?>
<?php } ?>
<h3>Login Details</h3>
<div class="form-group">
  <label for="InputUserName">Username</label>
  <?php if($method == 'update') { ?>
    <p><?php echo $username;?></p>
  <?php }else{ ?>
        <input type="text" class="form-control input-sm" name="username" value="<?php echo $username;?>" id="InputUserName" autocomplete="off">
  <?php } ?>
</div>
<div class="form-group">
  <label for="InputEmail">Email address</label>
  <input type="email" class="form-control input-sm" name="email" value="<?php echo $email;?>" id="InputEmail" autocomplete="off">
</div>
<div class="form-group">
  <label for="InputPassword">Password</label>
  <?php if($show_random_password) { ?>
    <p>Random Password: <?php echo $password;?></p>
  <?php } ?>
  <input type="password" class="form-control input-sm" name="password" value="<?php echo $password;?>" id="InputPassword" autocomplete="off">
</div>
